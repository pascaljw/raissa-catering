@extends('layouts.app') {{-- Sesuaikan dengan nama layout customer-mu --}}
@section('title', 'Pilihan Paket Kuliner')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <div class="mb-8 text-center md:text-left">
        <h1 class="font-display text-3xl font-bold text-charcoal mb-2">Pilihan Paket Layanan</h1>
        <p class="text-gray-500 text-sm">Silakan pilih paket hidangan terbaik yang sesuai dengan kebutuhan acara Anda.</p>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($packages as $package)
            {{-- Tampilkan hanya paket yang aktif untuk customer --}}
            @if($package->is_active)
            <div class="card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow flex flex-col justify-between">
                
                <div class="h-48 bg-cream flex items-center justify-center relative bg-gray-50 border-b border-gray-100">
                    @if($package->image)
                        <img src="{{ $package->image_url }}" class="w-full h-full object-cover" alt="{{ $package->name }}">
                    @else
                        <span class="text-6xl select-none">🍱</span>
                    @endif
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div class="mb-4">
                        <h3 class="font-bold text-lg text-charcoal mb-1.5">{{ $package->name }}</h3>
                        <p class="text-primary font-bold text-lg mb-2 text-orange-600">
                            Rp {{ number_format($package->price_per_box, 0, ',', '.') }}<span class="text-xs font-normal text-gray-400">/kotak</span>
                        </p>
                        
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded font-medium">
                                Min. Order: {{ $package->min_order }} kotak
                            </span>
                            <span class="text-xs text-orange-600 bg-orange-50 px-2.5 py-1 rounded font-medium capitalize">
                                🍳 {{ str_replace('_', ' ', $package->event_type) }}
                            </span>
                        </div>

                        @if(!empty($package->menu_items))
                        <div class="mt-4 pt-3 border-t border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Menu Include:</p>
                            <ul class="text-xs text-gray-600 space-y-1.5">
                                @foreach($package->menu_items as $menu_item)
                                    <li class="flex items-center gap-1.5">
                                        <span class="text-orange-500 font-bold">•</span> {{ $menu_item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('customer.checkout', ['package' => $package->slug]) }}" 
                           class="block w-full text-center bg-orange-500 text-white py-2.5 rounded-lg text-sm font-semibold hover:bg-orange-600 transition-colors shadow-sm shadow-orange-100">
                            Pilih Paket
                        </a>
                    </div>
                </div>
            </div>
            @endif
        @empty
        <div class="col-span-full text-center py-20 text-gray-400 bg-white rounded-xl border border-dashed border-gray-200">
            <p class="text-5xl mb-4">🍽️</p>
            <p class="text-base font-medium text-gray-500">Maaf, saat ini belum ada paket menu yang tersedia.</p>
            <p class="text-xs text-gray-400 mt-1">Silakan kembali beberapa saat lagi.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection