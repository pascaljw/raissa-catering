@extends('layouts.admin') {{-- Sesuaikan dengan nama layout dashboard admin-mu --}}
@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="p-6 max-w-5xl mx-auto min-h-screen bg-gray-50/50">
    
    {{-- Breadcrumb & Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="no-print text-sm font-medium text-orange-600 hover:underline">← Kembali ke Daftar Pesanan</a>
            <div class="flex items-center gap-3 mt-1">
                <h1 class="text-2xl font-bold text-gray-900">Pesanan #{{ $order->order_number }}</h1>
                <span class="px-3 py-1 rounded-full text-xs font-semibold uppercase border
                    @switch($order->status)
                        @case('completed') bg-green-50 text-green-700 border-green-200 @break
                        @case('cancelled') bg-red-50 text-red-700 border-red-200 @break
                        @case('delivering') bg-orange-50 text-orange-700 border-orange-200 @break
                        @case('dp_paid') bg-blue-50 text-blue-700 border-blue-200 @break
                        @case('processing') bg-blue-50 text-blue-700 border-blue-200 @break
                        @case('confirmed') bg-indigo-50 text-indigo-700 border-indigo-200 @break
                        @default bg-amber-50 text-amber-700 border-amber-200
                    @endswitch
                ">
                    @if($order->status == 'pending') Menunggu Konfirmasi Admin
                    @elseif($order->status == 'confirmed') Dikonfirmasi — Menunggu Pembayaran
                    @elseif($order->status == 'dp_paid' || $order->status == 'processing') Diproses (DP Lunas)
                    @elseif($order->status == 'completed') Selesai
                    @else {{ strtoupper($order->status) }} @endif
                </span>
            </div>
        </div>
        
        {{-- Tombol Cetak / Aksi Cepat --}}
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="no-print px-4 py-2 bg-white border border-gray-200 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors shadow-sm flex items-center gap-1.5">
                🖨️ Cetak Nota
            </button>
        </div>
    </div>

    {{-- Notifikasi Error / Sukses Sistem --}}
    @if(session('success'))
        <div class="no-print mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium">
            ✓ {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="no-print mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 space-y-6">
            
            {{-- Detail Paket Katering --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">🍱 Menu Paket Menu</h2>
                <div class="flex gap-4 items-start">
                    <div class="w-16 h-16 bg-gray-50 rounded-xl border border-gray-100 flex items-center justify-center text-3xl select-none">🍱</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 text-base">{{ $order->package->name ?? 'Paket Tidak Ditemukan' }}</h3>
                        <p class="text-gray-500 text-sm mt-0.5">Harga Dasar: Rp {{ number_format($order->price_per_box, 0, ',', '.') }} / kotak</p>
                        <p class="text-sm font-semibold text-orange-600 mt-1">Kuantitas: {{ $order->quantity }} Kotak (Boks)</p>
                        
                        @if($order->selected_addons)
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach($order->selected_addons as $addon)
                            <span class="text-xs bg-orange-50 text-orange-600 px-2.5 py-1 rounded-full font-medium border border-orange-100/70">
                                + {{ $addon['name'] }} (Rp {{ number_format($addon['price'], 0, ',', '.') }})
                            </span>
                            @endforeach
                        </div>
                        @endif
                        @if($order->is_custom && $order->custom_request)
                        <div class="mt-4 bg-orange-50 border border-orange-100 rounded-2xl p-4 text-sm text-orange-800">
                            <p class="font-semibold mb-2">🔧 Permintaan Paket Custom</p>
                            <p>{{ $order->custom_request }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Detail Informasi Logistik Acara --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">📍 Lokasi & Jadwal Pengiriman</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 block text-xs uppercase tracking-wider">Nama Acara</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">{{ $order->event_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs uppercase tracking-wider">Tanggal Acara</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">📆 {{ \Carbon\Carbon::parse($order->event_date)->format('d F Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs uppercase tracking-wider">Gedung / Tempat</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">{{ $order->event_location }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs uppercase tracking-wider">Jam Tiba Lokasi</span>
                        <span class="font-semibold text-orange-600 mt-0.5 block">⏰ Pukul {{ $order->delivery_time }} WITA</span>
                    </div>
                    <div class="sm:col-span-2 border-t border-gray-50 pt-2">
                        <span class="text-gray-400 block text-xs uppercase tracking-wider">Alamat Lengkap Pengiriman</span>
                        <span class="font-medium text-gray-800 mt-0.5 block leading-relaxed bg-gray-50 p-3 rounded-lg border border-gray-100">{{ $order->event_address }}</span>
                    </div>
                    @if($order->notes)
                    <div class="sm:col-span-2">
                        <span class="text-orange-500 block text-xs font-semibold uppercase tracking-wider">Catatan Khusus dari Pelanggan</span>
                        <p class="text-sm font-medium text-orange-700 bg-orange-50/50 p-3 rounded-lg border border-dashed border-orange-200 mt-1">{{ $order->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Data Kontak Pembeli --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">👤 Informasi Pelanggan</h2>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs block">Nama Pemesan</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">{{ $order->contact_name }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs block">Nomor WhatsApp</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">📞 {{ $order->contact_phone }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs block">Email Akun</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">{{ $order->user->email ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            
            {{-- PANEL KONFIRMASI PESANAN BARU (Hanya tampil saat pesanan belum dikonfirmasi admin) --}}
            @if($order->needsConfirmation())
            <div class="bg-gradient-to-br from-indigo-50 via-blue-50 to-purple-50 rounded-xl border-2 border-indigo-200 shadow-lg p-6 no-print relative overflow-hidden">
                {{-- Decorative accent --}}
                <div class="absolute top-0 right-0 w-20 h-20 bg-indigo-100/50 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-14 h-14 bg-purple-100/50 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                
                <div class="relative">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="text-xl">📋</span>
                        <h2 class="text-base font-bold text-indigo-900">Konfirmasi Pesanan</h2>
                    </div>
                    <p class="text-xs text-indigo-600/70 mb-4 leading-relaxed">Tinjau pesanan dan tentukan skema pembayaran untuk pelanggan.</p>
                    
                    <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" id="confirmOrderForm">
                        @csrf
                        
                        {{-- Pilihan Skema Pembayaran --}}
                        <div class="space-y-2.5 mb-4">
                            <label class="block text-xs font-bold text-indigo-800 uppercase tracking-wider">Skema Pembayaran</label>
                            
                            {{-- Opsi DP 50% --}}
                            <label class="group relative flex items-start gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all duration-200 bg-white border-gray-200 hover:border-indigo-300 hover:shadow-md has-[:checked]:border-indigo-500 has-[:checked]:bg-indigo-50/60 has-[:checked]:shadow-md">
                                <input type="radio" name="payment_scheme" value="dp" checked class="mt-0.5 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-0">
                                <div class="flex-1">
                                    <p class="font-bold text-sm text-gray-900">Uang Muka (DP 50%)</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Pelanggan bayar setengah dulu, sisa dilunasi nanti.</p>
                                    <div class="mt-2 flex items-baseline gap-1.5">
                                        <span class="text-lg font-extrabold text-indigo-600">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium">dari Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </label>
                            
                            {{-- Opsi Bayar Lunas --}}
                            <label class="group relative flex items-start gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all duration-200 bg-white border-gray-200 hover:border-emerald-300 hover:shadow-md has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50/60 has-[:checked]:shadow-md">
                                <input type="radio" name="payment_scheme" value="full" class="mt-0.5 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0">
                                <div class="flex-1">
                                    <p class="font-bold text-sm text-gray-900">Bayar Lunas (100%)</p>
                                    <p class="text-xs text-gray-500 mt-0.5">Pelanggan langsung melunasi seluruh tagihan.</p>
                                    <div class="mt-2 flex items-baseline gap-1.5">
                                        <span class="text-lg font-extrabold text-emerald-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                        <span class="text-[10px] text-gray-400 font-medium">bayar penuh</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        {{-- Catatan Admin --}}
                        <div class="mb-4">
                            <label class="block text-xs font-bold text-indigo-800 uppercase tracking-wider mb-1.5">Catatan (Opsional)</label>
                            <textarea name="admin_confirmation_notes" rows="2" placeholder="Contoh: Pelanggan VIP, kasih diskon pengiriman..." 
                                class="w-full text-sm border-indigo-200 rounded-lg focus:border-indigo-500 focus:ring-indigo-500 placeholder:text-gray-400 bg-white/80 resize-none"></textarea>
                        </div>
                        
                        {{-- Tombol Konfirmasi --}}
                        <button type="submit" onclick="return confirm('Konfirmasi pesanan ini? Pelanggan akan menerima notifikasi untuk melakukan pembayaran.')"
                            class="w-full bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold py-3 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-indigo-200/50 hover:shadow-indigo-300/50 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Konfirmasi Pesanan
                        </button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Info Konfirmasi yang Sudah Dilakukan --}}
            @if($order->isConfirmed())
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-3 pb-2 border-b border-gray-100 flex items-center gap-2">
                    <span class="text-green-500">✓</span> Dikonfirmasi
                </h2>
                <div class="space-y-2.5 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs block">Skema Pembayaran</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">
                            @if($order->payment_scheme === 'dp')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100">💳 DP 50%</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs font-bold border border-emerald-100">💰 Bayar Lunas</span>
                            @endif
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs block">Dikonfirmasi Oleh</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">{{ $order->confirmedByUser->name ?? 'Admin' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs block">Waktu Konfirmasi</span>
                        <span class="font-semibold text-gray-800 mt-0.5 block">{{ $order->confirmed_at->format('d M Y, H:i') }} WITA</span>
                    </div>
                    @if($order->admin_confirmation_notes)
                    <div>
                        <span class="text-gray-400 text-xs block">Catatan Admin</span>
                        <p class="text-sm font-medium text-gray-700 bg-gray-50 p-2.5 rounded-lg border border-gray-100 mt-1">{{ $order->admin_confirmation_notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Form Update Status Manajemen Dapur/Kurir --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-6 no-print">
                <h2 class="text-base font-bold text-gray-900 mb-3 pb-1">⚙️ Update Status Alur Pesanan</h2>
                @php $isFinalStatus = in_array($order->status, ['completed', 'cancelled']); @endphp
                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST" class="space-y-3">
                    @csrf
                    @method('PATCH')
                    <div>
                        <select name="status" {{ $isFinalStatus ? 'disabled' : '' }} class="w-full text-sm border-gray-200 rounded-lg shadow-sm focus:border-orange-500 focus:ring-orange-500 {{ $isFinalStatus ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu Konfirmasi</option>
                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi (Menunggu Bayar)</option>
                            <option value="processing" {{ $order->status == 'processing' || $order->status == 'dp_paid' ? 'selected' : '' }}>Diproses (Dapur Produksi)</option>
                            <option value="delivering" {{ $order->status == 'delivering' ? 'selected' : '' }}>Kurir Sedang Mengantar</option>
                            <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Selesai / Tiba di Lokasi</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Batal / Gagal</option>
                        </select>
                    </div>
                    <button type="submit" {{ $isFinalStatus ? 'disabled' : '' }} class="w-full {{ $isFinalStatus ? 'bg-gray-300 text-gray-600 cursor-not-allowed' : 'bg-orange-500 hover:bg-orange-600 text-white' }} font-semibold py-2 rounded-lg text-sm transition-colors shadow-sm">
                        Simpan Perubahan Status
                    </button>
                    @if($isFinalStatus)
                    <p class="text-xs text-gray-500">Status pesanan sudah final, tidak dapat diubah lagi.</p>
                    @endif
                </form>
            </div>

            {{-- Ringkasan Perhitungan Biaya & Tombol Konfirmasi Pembayaran Manual COD --}}
            <div class="bg-white rounded-xl border border-gray-200/60 shadow-sm p-6">
                <h2 class="text-base font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">💰 Keuangan & Tagihan</h2>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal Paket</span><span class="font-medium text-gray-800">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                    @if($order->addon_total > 0)
                    <div class="flex justify-between"><span class="text-gray-500">Total Addon</span><span class="font-medium text-gray-800">Rp {{ number_format($order->addon_total, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="border-t border-gray-100 pt-2 font-bold text-sm flex justify-between">
                        <span>Total Tagihan</span>
                        <span class="text-gray-900">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-3 space-y-1.5 border border-gray-100 mt-2">
                        @if($order->payment_scheme === 'full')
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>Skema: Bayar Lunas</span>
                            <span class="font-semibold text-emerald-700">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                        @else
                        <div class="flex justify-between text-xs text-gray-600"><span>Uang Muka (DP 50%)</span><span class="font-semibold text-gray-800">Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-xs text-gray-600"><span>Sisa Pelunasan</span><span class="font-semibold text-orange-600">Rp {{ number_format($order->remaining_amount, 0, ',', '.') }}</span></div>
                        @endif
                        <div class="flex justify-between text-xs text-gray-600 border-t border-gray-200/60 pt-1.5">
                            <span>Status Bayar</span>
                            <span class="font-bold uppercase tracking-wider text-[10px] 
                                {{ $order->payment_status === 'fully_paid' ? 'text-green-600' : ($order->payment_status === 'dp_paid' ? 'text-blue-600' : 'text-amber-600') }}">
                                {{ $order->payment_status === 'fully_paid' ? 'LUNAS TOTAL' : ($order->payment_status === 'dp_paid' ? 'DP LUNAS' : 'BELUM BAYAR') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- KONTROL PELUNASAN TUNAI (COD) OLEH ADMIN --}}
                @if($order->payment_status !== 'fully_paid')
                <div class="mt-5 pt-4 border-t border-gray-100 no-print">
                    <p class="text-xs text-gray-500 mb-2 leading-relaxed">Jika kurir menerima uang pelunasan secara tunai (cash) di lokasi acara, konfirmasi di bawah ini:</p>
                    <form action="{{ route('admin.orders.confirm-cash', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa pesanan katering ini telah dilunasi secara tunai (Cash)?')">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 rounded-lg text-xs transition-colors shadow-sm">
                            💵 Konfirmasi Pelunasan Tunai (COD)
                        </button>
                    </form>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection