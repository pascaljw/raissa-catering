@extends('layouts.admin')
@section('title', 'Daftar Pesanan')

@section('content')
<div class="p-6">
    {{-- Filter --}}
    <form method="GET" class="flex gap-3 mb-6 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nomor/nama..." class="border rounded-lg px-4 py-2 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-primary/30">
        <select name="status" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="">Semua Status</option>
            @foreach(['pending'=>'Menunggu DP','dp_paid'=>'DP Dibayar','confirmed'=>'Dikonfirmasi','processing'=>'Diproses','delivering'=>'Dikirim','delivered'=>'Tiba','completed'=>'Selesai','cancelled'=>'Dibatalkan'] as $val => $label)
            <option value="{{ $val }}" {{ request('status')===$val?'selected':'' }}>{{ $label }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="border rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary/30">
        <button class="bg-primary text-white px-4 py-2 rounded-lg text-sm">Filter</button>
        <a href="{{ route('admin.orders.index') }}" class="border px-4 py-2 rounded-lg text-sm text-gray-500 hover:bg-gray-50">Reset</a>
    </form>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-4 py-3 text-left">No. Pesanan</th>
                    <th class="px-4 py-3 text-left">Pelanggan</th>
                    <th class="px-4 py-3 text-left">Paket</th>
                    <th class="px-4 py-3 text-left">Tanggal Acara</th>
                    <th class="px-4 py-3 text-right">Total</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Pembayaran</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 font-mono font-medium text-charcoal">{{ $order->order_number }}</td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-charcoal">{{ $order->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->contact_phone }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p>{{ $order->package->name }}</p>
                        <p class="text-xs text-gray-400">{{ $order->quantity }} kotak</p>
                    </td>
                    <td class="px-4 py-3">
                        <p>{{ $order->event_date->format('d M Y') }}</p>
                        <p class="text-xs text-gray-400">{{ $order->delivery_time }}</p>
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-charcoal">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ match($order->status) {
                                'completed'  => 'bg-green-100 text-green-700',
                                'cancelled'  => 'bg-red-100 text-red-700',
                                'delivering' => 'bg-orange-100 text-orange-700',
                                'confirmed'  => 'bg-blue-100 text-blue-700',
                                default      => 'bg-yellow-100 text-yellow-700',
                            } }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium
                            {{ match($order->payment_status) {
                                'fully_paid' => 'bg-green-100 text-green-700',
                                'dp_paid'    => 'bg-blue-100 text-blue-700',
                                default      => 'bg-gray-100 text-gray-600',
                            } }}">
                            {{ match($order->payment_status) {
                                'unpaid'      => 'Belum Bayar',
                                'dp_pending'  => 'DP Pending',
                                'dp_paid'     => 'DP Lunas',
                                'full_pending'=> 'Pelunasan Pending',
                                'fully_paid'  => 'Lunas',
                                default       => $order->payment_status,
                            } }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-primary text-xs hover:underline font-medium">Detail →</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400">Tidak ada pesanan ditemukan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $orders->links() }}</div>
</div>
@endsection
