@extends('layouts.admin')
@section('title', 'Dashboard Admin')

@section('content')
<div class="p-6">
    <h1 class="font-display text-2xl font-bold text-charcoal mb-6">Dashboard</h1>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="card p-5">
            <p class="text-sm text-gray-500 mb-1">Total Pesanan</p>
            <p class="font-display text-3xl font-bold text-charcoal">{{ $stats['total_orders'] }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500 mb-1">Menunggu DP</p>
            <p class="font-display text-3xl font-bold text-yellow-500">{{ $stats['pending_orders'] }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500 mb-1">Pengiriman Hari Ini</p>
            <p class="font-display text-3xl font-bold text-blue-500">{{ $stats['today_deliveries'] }}</p>
        </div>
        <div class="card p-5">
            <p class="text-sm text-gray-500 mb-1">Pendapatan Bulan Ini</p>
            <p class="font-display text-2xl font-bold text-primary">Rp {{ number_format($stats['monthly_revenue'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Pesanan Terbaru --}}
        <div class="card">
            <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                <h2 class="font-semibold text-charcoal">Pesanan Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-primary text-sm">Lihat Semua</a>
            </div>
            <div class="divide-y divide-gray-50">
                @foreach($recentOrders as $order)
                <div class="p-4 flex items-center justify-between hover:bg-cream transition-colors">
                    <div>
                        <p class="font-medium text-charcoal text-sm">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->user->name }} · {{ $order->package->name }}</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-600' :
                               ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600') }}">
                            {{ $order->status_label }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">{{ $order->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Pengiriman Mendatang --}}
        <div class="card">
            <div class="p-4 border-b border-gray-100">
                <h2 class="font-semibold text-charcoal">Pengiriman Mendatang</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($upcomingDeliveries as $order)
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-medium text-charcoal text-sm">{{ $order->event_name }}</p>
                            <p class="text-xs text-gray-400">{{ $order->order_number }} · {{ $order->quantity }} kotak</p>
                            <p class="text-xs text-gray-500 mt-1">📍 {{ $order->event_location }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-primary text-sm">{{ $order->event_date->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->delivery_time }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-400 text-sm">Tidak ada pengiriman mendatang</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
