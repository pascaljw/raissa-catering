@extends('layouts.admin')
@section('title', 'Laporan Keuangan & Pesanan')

@section('content')
<div class="p-6 max-w-6xl mx-auto min-h-screen bg-gray-50/50">
    
    {{-- Header Laporan --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Laporan Rekapitulasi</h1>
            <p class="text-sm text-gray-500 mt-0.5">Pantau ringkasan omzet pendapatan dan status operasional katering.</p>
        </div>
        
        {{-- Tombol Unduh Excel --}}
        <div>
            <a href="{{ route('admin.reports.export') }}" class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg transition-colors shadow-sm flex items-center gap-2">
                📊 Ekspor ke Excel
            </a>
        </div>
    </div>

    {{-- Grid Kartu Ringkasan Statistik (KPI Cards) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Omzet / Pendapatan Masuk --}}
        <div class="bg-white p-5 rounded-xl border border-gray-200/80 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pendapatan (Lunas)</span>
            <div class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            <p class="text-[11px] text-gray-400 mt-1">Dari seluruh transaksi berstatus 'Paid'</p>
        </div>

        {{-- Total Pesanan Masuk --}}
        <div class="bg-white p-5 rounded-xl border border-gray-200/80 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Pesanan</span>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $totalOrders }} Pesanan</div>
            <p class="text-[11px] text-gray-400 mt-1">Akumulasi keseluruhan orderan</p>
        </div>

        {{-- Pesanan Masih Pending --}}
        <div class="bg-white p-5 rounded-xl border border-gray-200/80 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Menunggu Pembayaran</span>
            <div class="text-2xl font-bold text-amber-600 mt-1">{{ $pendingOrders }} Order</div>
            <p class="text-[11px] text-gray-400 mt-1">Pesanan baru yang belum bayar DP</p>
        </div>

        {{-- Pesanan Selesai --}}
        <div class="bg-white p-5 rounded-xl border border-gray-200/80 shadow-sm">
            <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Katering Selesai</span>
            <div class="text-2xl font-bold text-blue-600 mt-1">{{ $completedOrders }} Acara</div>
            <p class="text-[11px] text-gray-400 mt-1">Selesai diantarkan ke lokasi</p>
        </div>
    </div>

    {{-- Tabel Data Detail Laporan --}}
    <div class="bg-white rounded-xl border border-gray-200/80 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50">
            <h2 class="text-sm font-bold text-gray-800">Rincian Transaksi Logistik</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-500 border-collapse">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-3.5">ID Order</th>
                        <th class="px-6 py-3.5">Pelanggan</th>
                        <th class="px-6 py-3.5">Paket Menu</th>
                        <th class="px-6 py-3.5 text-center">Jumlah</th>
                        <th class="px-6 py-3.5 text-right">Total Tagihan</th>
                        <th class="px-6 py-3.5 text-center">Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-900 whitespace-nowrap">
                            #{{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-800">{{ $order->contact_name }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">📞 {{ $order->contact_phone }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $order->package->name ?? 'Paket Kustom' }}
                        </td>
                        <td class="px-6 py-4 text-center text-gray-800 whitespace-nowrap">
                            {{ $order->quantity }} box
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900 whitespace-nowrap">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                @if($order->payment_status === 'fully_paid') bg-green-50 text-green-700 border border-green-100
                                @elseif($order->payment_status === 'dp_paid') bg-blue-50 text-blue-700 border border-blue-100
                                @else bg-amber-50 text-amber-700 border border-amber-100 @endif
                            ">
                                {{ $order->payment_status === 'fully_paid' ? 'Lunas Total' : ($order->payment_status === 'dp_paid' ? 'DP Lunas' : 'Belum Bayar') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Belum ada data pesanan untuk dilaporkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100 bg-gray-50/30">
            {{ $orders->links() }}
        </div>
    </div>

</div>
@endsection