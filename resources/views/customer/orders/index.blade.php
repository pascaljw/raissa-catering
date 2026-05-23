@extends('layouts.app')
@section('title', 'Riwayat Pesanan Saya')

@section('content')
<div class="p-6 max-w-6xl mx-auto min-h-screen">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold text-charcoal">Riwayat Pesanan</h1>
            <p class="text-gray-500 text-sm mt-0.5">Pantau status pembayaran dan pengiriman katering Anda di sini.</p>
        </div>
        <div>
            <a href="{{ route('packages.index') }}" class="inline-block bg-orange-500 text-white px-4 py-2.5 rounded-lg text-sm font-semibold hover:bg-orange-600 transition-colors shadow-sm shadow-orange-100">
                + Pesan Paket Baru
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full border-collapse text-left text-sm text-gray-500">
                <thead class="bg-gray-50 text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4">ID Pesanan</th>
                        <th scope="col" class="px-6 py-4">Paket</th>
                        <th scope="col" class="px-6 py-4">Jumlah</th>
                        <th scope="col" class="px-6 py-4">Total Harga</th>
                        <th scope="col" class="px-6 py-4">Status</th>
                        <th scope="col" class="px-6 py-4">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                    @forelse($orders as $order)
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                            #{{ $order->order_number }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-charcoal">{{ $order->package->name ?? 'Paket Tidak Ditemukan' }}</div>
                            <div class="text-xs text-gray-400 mt-0.5">Tgl Kirim: {{ \Carbon\Carbon::parse($order->event_date)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 text-charcoal whitespace-nowrap">
                            {{ $order->quantity }} kotak
                        </td>
                        <td class="px-6 py-4 font-semibold text-charcoal whitespace-nowrap">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status == 'pending')
                                <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 border border-amber-100">
                                    Menunggu Pembayaran
                                </span>
                            @elseif($order->status == 'processing' || $order->status == 'dp_paid')
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700 border border-blue-100">
                                    Diproses (DP Lunas)
                                </span>
                            @elseif($order->status == 'completed')
                                <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700 border border-green-100">
                                    Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2.5 py-1 text-xs font-medium text-gray-600 border border-gray-100">
                                    Batal
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                                   class="text-xs font-semibold text-orange-600 border border-orange-200 px-3 py-1.5 rounded-md hover:bg-orange-50 transition-colors">
                                    Detail
                                </a>
                                
                                @if($order->status == 'pending')
                                    <a href="{{ route('customer.orders.show', $order->order_number) }}" 
                                       class="text-xs font-semibold bg-orange-500 text-white px-3 py-1.5 rounded-md hover:bg-orange-600 transition-colors shadow-sm">
                                        Bayar
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center text-gray-400">
                            <p class="text-4xl mb-3">📋</p>
                            <p class="text-base font-medium text-gray-500">Kamu belum memiliki riwayat pesanan.</p>
                            <p class="text-xs text-gray-400 mt-0.5">Yuk, mulai jelajahi menu paket katering terbaik kami!</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection