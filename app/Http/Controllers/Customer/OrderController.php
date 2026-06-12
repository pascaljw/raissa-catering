<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderLineItem;
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

        $items = $package->items()->get();
        $laukItems = $items->where('category', 'lauk')->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'additional_price' => $item->additional_price,
            'description' => $item->description,
        ])->values();

        $drinkItems = $items->where('category', 'minuman')->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'additional_price' => $item->additional_price,
            'description' => $item->description,
        ])->values();

        $fruitItems = $items->where('category', 'buah')->map(fn($item) => [
            'id' => $item->id,
            'name' => $item->name,
            'additional_price' => $item->additional_price,
            'description' => $item->description,
        ])->values();

        return view('customer.orders.create', compact('package', 'blockedDates', 'bookedDates', 'laukItems', 'drinkItems', 'fruitItems'));
    }

    // POST /orders - Simpan pesanan baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'package_id'      => 'required|exists:packages,id',
            'quantity'        => 'required|integer|min:1',
            'delivery_date'   => 'required|date|after:today',
            'delivery_time'   => 'required|date_format:H:i',
            'contact_name'    => 'required|string|max:255',
            'contact_phone'   => 'required|string|max:20',
            'address'         => 'required|string',
            'notes'           => 'nullable|string',
            'custom_request'  => 'nullable|string|max:1000',
            'selected_items'  => 'nullable|array',
            'selected_items.*' => 'integer|exists:items,id',
            'selected_addons' => 'nullable|array',
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

        $itemTotal = 0;
        $selectedItems = [];
        $selectedItemIds = collect($request->input('selected_items', []))->filter()->values();

        if ($selectedItemIds->isNotEmpty()) {
            $availableItemIds = $package->items()->pluck('id');
            if ($selectedItemIds->diff($availableItemIds)->isNotEmpty()) {
                return back()->withErrors(['selected_items' => 'Item terpilih tidak tersedia untuk paket ini.'])->withInput();
            }

            $items = Item::whereIn('id', $selectedItemIds)->get()->keyBy('id');
            foreach ($selectedItemIds as $itemId) {
                $item = $items->get($itemId);
                if (! $item) {
                    continue;
                }

                $quantity = 1;
                $lineTotal = $item->additional_price * $quantity;
                $itemTotal += $lineTotal;

                $selectedItems[] = [
                    'item_id'          => $item->id,
                    'item_name'        => $item->name,
                    'category'         => $item->category,
                    'quantity'         => $quantity,
                    'unit_price'       => $item->additional_price,
                    'additional_price' => $lineTotal,
                    'total_price'      => $lineTotal,
                ];
            }
        }

        $subtotal    = $package->price_per_box * $request->quantity;
        $total       = $subtotal + $addonTotal + $itemTotal;
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
                    'custom_request'  => $request->custom_request,
                    'is_custom'       => filled($request->custom_request) || ! empty($selectedItems),
                    'selected_addons' => $selectedAddons,
                    'status'          => 'pending',
                    'payment_status'  => 'unpaid',
                ]);

                if (! empty($selectedItems)) {
                    foreach ($selectedItems as $selectedItem) {
                        OrderLineItem::create(array_merge($selectedItem, [
                            'order_id'   => $order->id,
                            'package_id' => $package->id,
                        ]));
                    }
                }

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