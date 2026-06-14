@extends('layouts.app')
@section('title', 'Formulir Pemesanan')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="p-6 max-w-5xl mx-auto">
        
        <div class="mb-8">
            <a href="{{ route('packages.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-semibold transition-colors mb-2 inline-block">
                ← Kembali ke Pilihan Paket
            </a>
            <h1 class="text-3xl font-bold text-gray-800">Formulir Pemesanan</h1>
            <p class="text-gray-500 text-sm mt-1">Silakan lengkapi detail data pengantaran pesanan katering Raissa Catering Anda.</p>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm h-fit">
                <h2 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2 border-gray-100">Detail Pengiriman</h2>
                
                {{-- Order Form Validator Component --}}
                <x-order-form-validator />
                
                <form action="{{ route('customer.orders.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="package_id" value="{{ $package->id }}">

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="contact_name" class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Penerima / Kontak</label>
                            <input type="text" name="contact_name" id="contact_name" 
                                   value="{{ auth()->user()->name }}" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none text-sm transition-all"
                                   required>
                        </div>
                        <div>
                            <label for="contact_phone" class="block text-sm font-semibold text-gray-700 mb-1.5">No. WhatsApp Aktif</label>
                            <input type="text" name="contact_phone" id="contact_phone" 
                                   placeholder="Contoh: 08123456789"
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none text-sm transition-all"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-1.5">Jumlah Pesanan (Kotak)</label>
                        <input type="number" name="quantity" id="quantity" 
                               min="{{ $package->min_order }}" 
                               value="{{ $package->min_order }}" 
                               class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none text-sm transition-all"
                               required>
                        <p class="text-xs text-gray-400 mt-1.5">*Minimal pemesanan untuk paket ini adalah {{ $package->min_order }} kotak.</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label for="delivery_date" class="block text-sm font-semibold text-gray-700 mb-1.5">Tanggal Pengantaran</label>
                            <input type="date" name="delivery_date" id="delivery_date" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none text-sm transition-all"
                                   required>
                        </div>
                        <div>
                            <label for="delivery_time" class="block text-sm font-semibold text-gray-700 mb-1.5">Jam Tiba di Lokasi</label>
                            <input type="time" name="delivery_time" id="delivery_time" 
                                   class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none text-sm transition-all"
                                   required>
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Lengkap Tujuan Pengiriman</label>
                        <textarea name="address" id="address" rows="3" 
                                  placeholder="Tuliskan nama jalan, perumahan, blok, nomor rumah, RT/RW, atau patokan lokasi..."
                                  class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none text-sm transition-all"
                                  required></textarea>
                    </div>

                    @if(request()->boolean('custom'))
                    <div class="bg-orange-50 border border-orange-100 rounded-2xl p-4">
                        <label for="custom_request" class="block text-sm font-semibold text-orange-700 mb-1.5">Permintaan Paket Custom</label>
                        <textarea name="custom_request" id="custom_request" rows="3"
                                  placeholder="Contoh: Saya ingin menu prasmanan nasi liwet, ayam rica-rica, sayur asem, dan sambal matah."
                                  class="w-full px-4 py-3 rounded-xl border border-orange-200 bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none text-sm transition-all">{{ old('custom_request') }}</textarea>
                        <p class="text-xs text-orange-500 mt-2">Isi jika Anda ingin paket disesuaikan dengan kebutuhan menu acara.</p>
                    </div>
                    @endif

                    @if(request()->boolean('custom'))
                    <div class="mb-6">
                        <x-pos-custom-menu :base-price="$package->price_per_box" :lauk-items="$laukItems" :drink-items="$drinkItems" :fruit-items="$fruitItems" />
                    </div>
                    @endif

                    <div>
                        <label for="notes" class="block text-sm font-semibold text-gray-700 mb-1.5">Catatan Khusus Tambahan (Opsional)</label>
                        <textarea name="notes" id="notes" rows="2" 
                                  placeholder="Contoh: Sambal dipisah, sendok plastik tidak perlu disediakan, dsb."
                                  class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 outline-none text-sm transition-all">{{ old('notes') }}</textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-orange-500 text-white py-3 rounded-xl text-sm font-semibold hover:bg-orange-600 transition-colors shadow-sm font-display">
                            Konfirmasi Pemesanan & Bayar DP via Xendit
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden h-fit">
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Ringkasan Pilihan Menu</h2>
                </div>
                
                <div class="h-36 bg-orange-50/50 flex items-center justify-center border-b border-gray-100">
                    <span class="text-6xl select-none">🍱</span>
                </div>

                <div class="p-5 space-y-4">
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg">{{ $package->name }}</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Raissa Catering Premium Edition</p>
                    </div>

                    <div class="border-t border-dashed border-gray-200 pt-3 space-y-2.5 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Harga per Kotak</span>
                            <span class="font-bold text-gray-800">Rp {{ number_format($package->price_per_box, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Batas Minimum Order</span>
                            <span class="text-gray-800 font-medium">{{ $package->min_order }} kotak</span>
                        </div>
                    </div>

                    <div class="bg-orange-50 p-3 rounded-xl border border-orange-100/70">
                        <p class="text-xs text-orange-800 leading-relaxed">
                            💡 <strong>Metode Sistem Keuangan:</strong> Setelah menekan tombol konfirmasi, Invoice DP resmi sebesar 50% akan otomatis dibuat menggunakan akun Xendit.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

{{-- Include Order Form Validator Script --}}
@vite(['resources/js/order-form-validator.js'])
@endsection