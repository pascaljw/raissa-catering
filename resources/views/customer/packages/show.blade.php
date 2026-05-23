@extends('layouts.app')
@section('title', $package->name)

@section('content')
<div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Tombol Kembali --}}
    <div class="mb-6">
        <a href="{{ route('home') }}" class="text-sm font-medium text-primary hover:text-primary-dark inline-flex items-center gap-1 transition-colors">
            ← Kembali ke Beranda
        </a>
    </div>

    {{-- Grid Detail Konten --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden grid md:grid-cols-2 gap-8 p-6 md:p-10">
        
        {{-- Sisi Kiri: Gambar Paket Menu --}}
        <div class="relative h-72 md:h-full min-h-[300px] bg-primary/5 rounded-2xl overflow-hidden shadow-inner flex items-center justify-center">
            @if($package->image)
                <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
            @else
                <div class="text-center">
                    <span class="text-8xl block mb-2">🍱</span>
                    <span class="text-xs text-gray-400 font-medium">Gambar hidangan belum tersedia</span>
                </div>
            @endif
            <span class="absolute top-4 left-4 bg-primary text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wider shadow-sm">
                {{ $package->event_type_label }}
            </span>
        </div>

        {{-- Sisi Kanan: Informasi & Form Order --}}
        <div class="flex flex-col justify-between">
            <div>
                <h1 class="font-display text-3xl font-bold text-charcoal leading-tight mb-2">
                    {{ $package->name }}
                </h1>
                
                <div class="flex items-center gap-4 border-b border-gray-100 pb-4 mb-5">
                    <div>
                        <span class="text-3xl font-extrabold text-primary">Rp {{ number_format($package->price_per_box, 0, ',', '.') }}</span>
                        <span class="text-sm text-gray-400">/ kotak</span>
                    </div>
                    <div class="h-8 w-px bg-gray-200"></div>
                    <div class="text-xs text-gray-500 font-medium">
                        📦 Minimal Order: <span class="text-charcoal font-bold text-sm">{{ $package->min_order }}</span> kotak
                    </div>
                </div>

                {{-- Fitur Tambahan: List Menu Dinamis Sesuai Input Admin --}}
                <div class="mb-8">
                    <h3 class="text-sm font-bold text-charcoal uppercase tracking-wider mb-2">Komposisi Kotak Hidangan:</h3>
                    
                    {{-- Cek jika deskripsi ada dan tidak kosong setelah di-trim --}}
                    @if($package->description && trim($package->description) != '')
                        <ul class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-600">
                            @php
                                // Pecah teks berdasarkan koma, jika tidak ada koma pecah berdasarkan baris baru
                                $menuItems = str_contains($package->description, ',') 
                                    ? explode(',', $package->description) 
                                    : explode("\n", $package->description);
                            @endphp

                            @foreach($menuItems as $item)
                                @if(trim($item) != '')
                                    <li class="flex items-center gap-2 bg-gray-50 p-2 rounded-lg border border-gray-100">
                                        🍱 {{ trim($item) }}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        {{-- TAMPILAN FAILSAFE: Jika admin mengosongkan status deskripsi menu --}}
                        <div class="bg-amber-50/50 border border-amber-100 text-amber-700 text-xs px-4 py-3 rounded-xl flex items-center gap-2 italic font-medium">
                            ⚠️ Komposisi belum terisi oleh pihak katering.
                        </div>
                    @endif
                </div>
            </div>

            {{-- Tombol Aksi Pesan Sekarang --}}
            <div class="border-t border-gray-100 pt-5 mt-auto">
                <a href="{{ route('customer.checkout', $package->slug) }}" class="w-full bg-primary hover:bg-primary-dark text-white font-bold py-3.5 px-6 rounded-xl transition-all text-center block shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                    🛒 Pesan Paket Sekarang
                </a>
                <p class="text-[11px] text-gray-400 text-center mt-2.5">
                    *Uang muka (DP) sebesar 50% akan dihitung otomatis di halaman checkout via payment gateway Xendit.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection