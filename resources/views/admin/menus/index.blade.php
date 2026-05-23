@extends('layouts.admin')
@section('title', 'Paket & Menu')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="font-display text-2xl font-bold text-charcoal">Paket & Menu</h1>
        <a href="{{ route('admin.menus.create') }}" class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-primary-dark transition-colors shadow-sm">
            + Tambah Paket
        </a>
    </div>

    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($packages as $package)
        <div class="card bg-white border border-gray-100 shadow-sm rounded-xl overflow-hidden transition-all duration-200 {{ !$package->is_active ? 'opacity-60 bg-gray-50/50 shadow-none' : 'hover:shadow-md' }}">
            
            <div class="h-40 bg-cream flex items-center justify-center relative bg-gray-50 border-b border-gray-100">
                @if($package->image)
                    <img src="{{ $package->image_url }}" class="w-full h-full object-cover" alt="{{ $package->name }}">
                @else
                    <span class="text-5xl select-none">🍱</span>
                @endif
                <span class="absolute top-2 right-2 text-xs px-2 py-1 rounded-full font-semibold shadow-sm
                    {{ $package->is_active ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                    {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <div class="p-4">
                <h3 class="font-bold text-charcoal text-base mb-1 truncate">{{ $package->name }}</h3>
                <p class="text-primary font-bold text-sm mb-1 text-orange-600">
                    Rp {{ number_format($package->price_per_box, 0, ',', '.') }}<span class="text-xs font-normal text-gray-400">/kotak</span>
                </p>
                <p class="text-xs text-gray-400 mb-4">
                    Min. {{ $package->min_order }} kotak · {{ $package->orders_count ?? 0 }} pesanan
                </p>

                <div class="flex items-center gap-2">
                    
                    <a href="{{ route('admin.menus.edit', $package->id) }}"
                       class="flex-1 text-center text-xs border border-primary text-primary py-2 rounded-lg hover:bg-primary hover:text-white transition-colors font-semibold">
                        Edit
                    </a>
                    
                    <form action="{{ route('admin.menus.toggle', $package->id) }}" method="POST" class="inline-block">
                        @csrf 
                        @method('PATCH')
                        <button type="submit" class="text-xs border border-gray-300 text-gray-600 px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors font-medium whitespace-nowrap">
                            {{ $package->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.menus.destroy', $package->id) }}" method="POST"
                          onsubmit="return confirm('Hapus paket katering ini?')" class="inline-block">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="text-xs border border-red-200 text-red-500 px-3 py-2 rounded-lg hover:bg-red-50 transition-colors font-medium">
                            Hapus
                        </button>
                    </form>

                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-16 text-gray-400 bg-white border border-dashed border-gray-200 rounded-xl">
            <p class="text-4xl mb-3">🍱</p>
            <p class="font-medium text-gray-500">Belum ada paket menu tersedia.</p>
            <p class="text-xs text-gray-400 mt-1">Silakan <a href="{{ route('admin.menus.create') }}" class="text-primary underline font-semibold">Tambah sekarang</a> untuk mengisi daftar katering.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection