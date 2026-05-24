<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Package;
use App\Services\XenditService;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Deklarasi properti service Xendit
    protected $xendit;

    // Konstruktor dengan penulisan kurung kurawal yang aman
    public function __construct(XenditService $xenditService) 
    {
        $this->xendit = $xenditService;
    }

    // GET /packages - Tampilkan semua paket katering
    public function packages()
    {
        $packages = Package::active()->with('addons', 'reviews')->get();
        return view('customer.packages.index', compact('packages'));
    }

    // GET /packages/{slug} - Detail paket katering
    public function packageDetail(Package $package)
    {
        $reviews = $package->reviews()->where('is_approved', true)
            ->with('user')->latest()->take(5)->get();
        return view('customer.packages.show', compact('package', 'reviews'));
    }

    // GET /checkout/{slug} - Form pemesanan katering
    public function checkout(Package $package)
    {
        $blockedDates = collect([]); 
        $bookedDates  = collect([]); 

        return view('customer.orders.create', compact('package', 'blockedDates', 'bookedDates'));
    }

    // POST /orders - Simpan pesanan baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'package_id'    => 'required|exists:packages,id',
            'quantity'      => 'required|integer|min:1',
            'delivery_date' => 'required|date|after:today',
            'delivery_time' => 'required|date_format:H:i',
            'contact_name'  => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            'address'       => 'required|string',
            'notes'         => 'nullable|string',
            'selected_addons'=> 'nullable|array',
        ]);

        $package = Package::findOrFail($request->package_id);

        if ($request->quantity < $package->min_order) {
            return back()->withErrors(['quantity' => "Minimum order {$package->min_order} kotak"]);
        }

        $addonTotal = 0;
        $selectedAddons = [];
        if ($request->selected_addons) {
            $addons = $package->addons()->whereIn('id', $request->selected_addons)->get();
            foreach ($addons as $addon) {
                $addonTotal += $addon->price * $request->quantity;
                $selectedAddons[] = ['id' => $addon->id, 'name' => $addon->name, 'price' => $addon->price];
            }
        }

        $subtotal    = $package->price_per_box * $request->quantity;
        $total       = $subtotal + $addonTotal;
        $dpAmount    = $total * 0.5;
        $remaining   = $total - $dpAmount;

        $orderNumber = Order::generateOrderNumber();
        $attempts = 0;
        $order = null;

        while ($attempts < 5) {
            try {
                $order = Order::create([
                    'order_number'    => $orderNumber,
                    'user_id'         => Auth::id(),
                    'package_id'      => $package->id,
                    'quantity'        => $request->quantity,
                    'price_per_box'   => $package->price_per_box,
                    'subtotal'        => $subtotal,
                    'addon_total'     => $addonTotal,
                    'total_amount'    => $total,
                    'dp_amount'       => $dpAmount,
                    'remaining_amount'=> $remaining,
                    'event_name'      => $request->input('event_name', 'Pesanan Katering ' . $package->name),
                    'event_location'  => $request->input('event_location', 'Lokasi Pengantaran'),
                    'event_address'   => $request->address,
                    'event_date'      => $request->delivery_date,
                    'delivery_time'   => $request->delivery_time,
                    'contact_name'    => $request->contact_name,
                    'contact_phone'   => $request->contact_phone,
                    'notes'           => $request->notes,
                    'selected_addons' => $selectedAddons,
                    'status'          => 'pending',
                    'payment_status'  => 'unpaid',
                ]);

                break;
            } catch (QueryException $e) {
                if ($e->errorInfo[1] === 1062 && str_contains($e->getMessage(), 'orders_order_number_unique')) {
                    $orderNumber = Order::generateOrderNumber();
                    $attempts++;
                    continue;
                }

                throw $e;
            }
        }

        if (! $order) {
            return back()->withErrors(['order' => 'Gagal membuat pesanan. Silakan coba lagi.']);
        }

        return redirect()->route('customer.orders.show', $order->order_number);
    }

    // GET /orders - Menampilkan riwayat pesanan customer
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with('package', 'payments')
            ->latest()->paginate(10);
        return view('customer.orders.index', compact('orders'));
    }

    // GET /orders/{orderNumber} - Menampilkan detail satu pesanan
    public function show(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with('package', 'payments')
            ->firstOrFail();
            
        return view('customer.orders.show', compact('order'));
    }

    // POST /orders/{orderNumber}/pay-dp - Integrasi Pembayaran Ke Link Invoice Xendit
    public function payDp(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        
        $result = $this->xendit->createDpInvoice($order);

        if ($result['success']) {
            return redirect()->away($result['invoice_url']);
        }

        return back()->withErrors(['payment' => $result['message']]);
    }

    // POST /orders/{orderNumber}/pay-full - Pelunasan tagihan online
    public function payFull(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $result = $this->xendit->createFullPaymentInvoice($order);

        if ($result['success']) {
            return redirect()->away($result['invoice_url']);
        }

        return back()->withErrors(['payment' => $result['message']]);
    }
}