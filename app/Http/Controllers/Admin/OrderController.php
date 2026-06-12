<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\XenditService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function __construct(protected XenditService $xendit) {}

    /**
     * GET /admin/orders
     * Menampilkan daftar semua pesanan masuk dengan fitur pencarian & filter
     */
    public function index(Request $request)
    {
        $query = Order::with('user', 'package', 'payments')->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date) {
            $query->whereDate('event_date', $request->date);
        }
        if ($request->search) {
            $query->where('order_number', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%"));
        }

        $orders = $query->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * GET /admin/orders/{order}
     * Menampilkan halaman detail satu pesanan tertentu untuk sisi Admin
     */
    public function show(Order $order)
    {
        $order->load('user', 'package', 'payments');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * PATCH /admin/orders/{order}/status
     * Mengubah status operasional dapur/kurir katering dari form detail admin
     */
    public function updateStatus(Request $request, Order $order)
    {
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return back()->withErrors(['status' => 'Status pesanan sudah final dan tidak dapat diubah lagi.']);
        }

        $request->validate([
            'status' => 'required|in:pending,dp_paid,confirmed,processing,delivering,delivered,completed,cancelled',
        ]);

        $newStatus = $request->status;

        $order->update([
            'status' => $newStatus,
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * POST /admin/orders/{order}/confirm-cash
     * Fitur: Mendukung konfirmasi DP Cash (saat pending) & Pelunasan Akhir Cash (saat jalan/COD)
     */
    public function confirmCash(Request $request, Order $order)
    {
        $request->validate(['notes' => 'nullable|string|max:500']);

        // SKENARIO 1: Pelanggan membayar DP secara Tunai/Cash langsung (Bypass Xendit)
        if ($order->status === 'pending' && $order->payment_status === 'unpaid') {
            
            // Catat data pembayaran DP ke database internal secara manual
            $order->payments()->create([
                'payment_reference' => 'CASH-DP-' . $order->order_number . '-' . time(),
                'amount'            => $order->dp_amount,
                'type'              => 'dp',
                'status'            => 'paid',
                'paid_at'           => now(),
                'notes'             => $request->notes ?? 'Pembayaran DP 50% secara manual/tunai langsung ke Admin.',
            ]);

            // Alihkan status ke tahap produksi dapur katering karena DP sudah aman
            $order->update([
                'status'         => 'processing',
                'payment_status' => 'dp_paid'
            ]);

            return back()->with('success', 'Konfirmasi DP Tunai sukses! Pesanan otomatis dialihkan ke status diproses.');
        }

        // SKENARIO 2: Pelanggan membayar sisa pelunasan (COD) saat makanan katering tiba di lokasi acara
        $allowedFullStatuses = ['processing', 'dp_paid', 'delivering', 'completed'];

        if (in_array($order->status, $allowedFullStatuses)) {
            if ($order->payment_status === 'fully_paid') {
                return back()->withErrors(['payment' => 'Pesanan katering ini sudah berstatus lunas total.']);
            }

            // Panggil fungsi bawaan XenditService untuk mengunci pelunasan akhir secara cash di sistem
            $this->xendit->confirmCashPayment($order, $request->notes ?? 'Sisa pelunasan dibayar tunai via Kurir (COD)');

            return back()->with('success', 'Pelunasan akhir secara tunai (COD) berhasil dikonfirmasi.');
        }

        return back()->withErrors(['payment' => 'Kondisi status pesanan saat ini tidak memenuhi syarat untuk konfirmasi tunai.']);
    }

    /**
     * GET /admin/reports
     * Menampilkan halaman rekap laporan operasional & keuangan katering
     * Diarahkan ke folder jamak sesuai proyek Anda: resources/views/admin/reports/index.blade.php
     */
    public function report()
    {
        // Ambil data transaksi katering untuk visualisasi tabel report
        $orders = Order::with('package', 'payments')->latest()->paginate(15);

        // Hitung akumulasi metrik internal katering untuk KPI Cards
        $totalOrders     = Order::count();
        $pendingOrders   = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        
        // Menghitung total omzet pendapatan riil dari pembayaran yang sukses (Paid)
        $totalRevenue    = Payment::where('status', 'paid')->sum('amount');

        return view('admin.reports.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalRevenue'
        ));
    }

    /**
     * GET /admin/reports/export
     * Mengeksekusi unduhan dokumen Excel secara langsung menggunakan PHP Stream Native (Tanpa Package Tambahan)
     */
    public function exportExcel()
    {
        return Excel::download(new OrdersExport(), 'Laporan_Katering_Raissa_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    /**
     * GET /admin/dashboard
     * Menampilkan halaman utama dashboard ringkasan statistik harian admin
     */
    public function dashboard()
    {
        $today = today();

        $stats = [
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'today_deliveries'=> Order::whereDate('event_date', $today)->count(),
            'monthly_revenue' => Payment::where('status', 'paid')
                ->whereMonth('created_at', $today->month)
                ->sum('amount'),
        ];

        $recentOrders = Order::with('user', 'package')
            ->latest()->take(10)->get();

        $upcomingDeliveries = Order::with('user', 'package')
            ->whereIn('status', ['processing', 'dp_paid', 'delivering'])
            ->whereDate('event_date', '>=', $today)
            ->orderBy('event_date')
            ->take(5)->get();

        return view('admin.dashboard.index', compact('stats', 'recentOrders', 'upcomingDeliveries'));
    }
}