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

        // SKENARIO 1: Pesanan yang sudah dikonfirmasi admin (status = confirmed), pelanggan bayar tunai/manual
        if ($order->status === 'confirmed' && $order->payment_status === 'unpaid') {

            if ($order->payment_scheme === 'full') {
                // Skema bayar lunas: langsung catat full payment
                $order->payments()->create([
                    'payment_reference' => 'CASH-FULL-' . $order->order_number . '-' . time(),
                    'amount'            => $order->total_amount,
                    'type'              => 'full_payment',
                    'method'            => 'cash',
                    'status'            => 'paid',
                    'paid_at'           => now(),
                    'admin_notes'       => $request->notes ?? 'Pembayaran lunas 100% dikonfirmasi manual oleh Admin.',
                ]);

                $order->update([
                    'status'         => 'completed',
                    'payment_status' => 'fully_paid',
                ]);

                return back()->with('success', 'Pembayaran lunas (100%) berhasil dikonfirmasi! Pesanan selesai.');
            }

            // Skema DP: catat pembayaran DP
            $order->payments()->create([
                'payment_reference' => 'CASH-DP-' . $order->order_number . '-' . time(),
                'amount'            => $order->dp_amount,
                'type'              => 'dp',
                'method'            => 'cash',
                'status'            => 'paid',
                'paid_at'           => now(),
                'admin_notes'       => $request->notes ?? 'Pembayaran DP 50% dikonfirmasi manual oleh Admin.',
            ]);

            $order->update([
                'status'         => 'processing',
                'payment_status' => 'dp_paid',
            ]);

            return back()->with('success', 'Konfirmasi DP berhasil! Pesanan dialihkan ke status diproses.');
        }

        // SKENARIO 1B: Pesanan pending lama (belum pakai flow konfirmasi baru)
        if ($order->status === 'pending' && $order->payment_status === 'unpaid') {
            
            $order->payments()->create([
                'payment_reference' => 'CASH-DP-' . $order->order_number . '-' . time(),
                'amount'            => $order->dp_amount,
                'type'              => 'dp',
                'method'            => 'cash',
                'status'            => 'paid',
                'paid_at'           => now(),
                'admin_notes'       => $request->notes ?? 'Pembayaran DP 50% secara manual/tunai langsung ke Admin.',
            ]);

            $order->update([
                'status'         => 'processing',
                'payment_status' => 'dp_paid'
            ]);

            return back()->with('success', 'Konfirmasi DP Tunai sukses! Pesanan otomatis dialihkan ke status diproses.');
        }

        // SKENARIO 2: Konfirmasi pembayaran online yang sudah masuk (dp_pending / full_pending dari Xendit)
        if (in_array($order->payment_status, ['dp_pending', 'full_pending'])) {
            $pendingPayment = $order->payments()->where('status', 'pending')->latest()->first();

            if ($pendingPayment) {
                $pendingPayment->update([
                    'status'  => 'paid',
                    'method'  => $pendingPayment->method ?? 'manual_transfer',
                    'paid_at' => now(),
                    'admin_notes' => $request->notes ?? 'Pembayaran dikonfirmasi manual oleh Admin (webhook tidak diterima).',
                ]);

                if ($pendingPayment->type === 'dp') {
                    $order->update(['status' => 'processing', 'payment_status' => 'dp_paid']);
                    return back()->with('success', 'Pembayaran DP berhasil dikonfirmasi manual!');
                } else {
                    $order->update(['status' => 'completed', 'payment_status' => 'fully_paid']);
                    return back()->with('success', 'Pelunasan berhasil dikonfirmasi manual! Pesanan selesai.');
                }
            }
        }

        // SKENARIO 3: Pelanggan membayar sisa pelunasan (COD) saat makanan tiba
        $allowedFullStatuses = ['processing', 'dp_paid', 'delivering', 'completed', 'confirmed'];

        if (in_array($order->status, $allowedFullStatuses)) {
            if ($order->payment_status === 'fully_paid') {
                return back()->withErrors(['payment' => 'Pesanan katering ini sudah berstatus lunas total.']);
            }

            $this->xendit->confirmCashPayment($order, $request->notes ?? 'Sisa pelunasan dibayar tunai via Kurir (COD)');

            return back()->with('success', 'Pelunasan akhir secara tunai (COD) berhasil dikonfirmasi.');
        }

        return back()->withErrors(['payment' => 'Kondisi status pesanan saat ini tidak memenuhi syarat untuk konfirmasi.']);
    }

    /**
     * POST /admin/orders/{order}/confirm
     * Admin mengkonfirmasi pesanan dan memilih skema pembayaran: DP 50% atau Bayar Lunas
     */
    public function confirm(Request $request, Order $order)
    {
        // Hanya pesanan pending yang belum dikonfirmasi bisa dikonfirmasi
        if ($order->status !== 'pending' || $order->payment_status !== 'unpaid') {
            return back()->withErrors(['confirm' => 'Pesanan ini tidak dalam kondisi yang bisa dikonfirmasi.']);
        }

        if ($order->isConfirmed()) {
            return back()->withErrors(['confirm' => 'Pesanan ini sudah dikonfirmasi sebelumnya.']);
        }

        $request->validate([
            'payment_scheme' => 'required|in:dp,full',
            'admin_confirmation_notes' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'status'                   => 'confirmed',
            'payment_scheme'           => $request->payment_scheme,
            'confirmed_at'             => now(),
            'confirmed_by'             => auth()->id(),
            'admin_confirmation_notes' => $request->admin_confirmation_notes,
        ];

        // Jika admin memilih bayar lunas, set remaining = total agar invoice full_payment benar
        if ($request->payment_scheme === 'full') {
            $updateData['dp_amount']        = 0;
            $updateData['remaining_amount'] = $order->total_amount;
        }

        $order->update($updateData);

        $schemeLabel = $request->payment_scheme === 'dp' ? 'Down Payment (DP 50%)' : 'Bayar Lunas (100%)';
        return back()->with('success', "Pesanan berhasil dikonfirmasi dengan skema pembayaran: {$schemeLabel}");
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