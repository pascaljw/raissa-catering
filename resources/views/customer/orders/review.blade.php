@extends('layouts.app')
@section('title', 'Review Pesanan ' . $order->order_number)

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12 min-h-screen">
    <div class="mb-8">
        <a href="{{ route('customer.orders.show', $order->order_number) }}" class="text-orange-600 text-sm font-medium hover:underline">← Kembali ke Detail Pesanan</a>
        <h1 class="font-display text-3xl font-bold text-charcoal mt-4">Berikan Review untuk Pesanan #{{ $order->order_number }}</h1>
        <p class="text-gray-500 mt-2">Beri rating dan komentar agar pelanggan lain tahu kualitas layanan kami.</p>
    </div>

    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
        <div class="mb-8">
            <h2 class="font-semibold text-charcoal text-lg mb-3">Paket yang dipesan</h2>
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-primary/10 rounded-3xl flex items-center justify-center text-2xl">🍱</div>
                <div>
                    <p class="font-semibold text-charcoal">{{ $order->package->name }}</p>
                    <p class="text-sm text-gray-500">{{ $order->quantity }} kotak · Tanggal {{ $order->event_date->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        <form action="{{ route('customer.orders.submit-review', $order->order_number) }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-charcoal mb-2">Rating Bintang</label>
                    <div class="grid grid-cols-5 gap-3">
                        @php
                            $ratingLabels = [
                                1 => 'Sangat Buruk',
                                2 => 'Buruk',
                                3 => 'Cukup',
                                4 => 'Bagus',
                                5 => 'Sangat Bagus',
                            ];
                        @endphp
                        @foreach(range(1, 5) as $value)
                            <label for="rating-{{ $value }}" class="group flex flex-col items-center gap-2 cursor-pointer">
                                <input id="rating-{{ $value }}" type="radio" name="rating" value="{{ $value }}" class="sr-only peer" {{ old('rating') == $value ? 'checked' : '' }} aria-label="Beri rating {{ $value }} bintang">
                                <span class="inline-flex items-center justify-center w-12 h-12 rounded-2xl border border-gray-200 text-xl text-gray-400 transition-all duration-150 peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white group-hover:border-primary group-hover:text-primary">
                                    ⭐
                                </span>
                                <span class="text-[11px] text-center text-gray-500 peer-checked:text-charcoal group-hover:text-charcoal">{{ $ratingLabels[$value] }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('rating')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="comment" class="block text-sm font-semibold text-charcoal mb-2">Komentar</label>
                    <textarea name="comment" id="comment" rows="5" class="w-full rounded-3xl border border-gray-200 bg-gray-50 p-4 text-sm text-charcoal focus:border-primary focus:ring-2 focus:ring-primary/20" placeholder="Tulis pengalaman Anda dengan layanan kami...">{{ old('comment') }}</textarea>
                    @error('comment')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <a href="{{ route('customer.orders.show', $order->order_number) }}" class="inline-flex items-center justify-center w-full sm:w-auto border border-gray-200 text-gray-700 px-6 py-3 rounded-2xl hover:bg-gray-100 transition">Batalkan</a>
                    <button type="submit" class="inline-flex items-center justify-center w-full sm:w-auto bg-orange-500 text-white px-6 py-3 rounded-2xl font-semibold hover:bg-orange-600 transition">Kirim Review</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
