@extends('layouts.app')
@section('title', 'Detail Pesanan ' . $order->order_number)

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10 min-h-screen">

    {{-- Header Halaman --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <a href="{{ route('customer.orders.index') }}" class="text-orange-600 text-sm font-medium hover:underline">← Kembali ke Pesanan</a>
            <h1 class="font-display text-2xl font-bold text-charcoal mt-1">Pesanan #{{ $order->order_number }}</h1>
        </div>
        <span class="px-4 py-2 rounded-full text-sm font-semibold inline-block self-start sm:self-auto
            @switch($order->status)
                @case('completed') bg-green-100 text-green-700 @break
                @case('cancelled') bg-red-100 text-red-700 @break
                @case('delivering') bg-orange-100 text-orange-700 @break
                @case('dp_paid') bg-blue-100 text-blue-700 @break
                @case('processing') bg-blue-100 text-blue-700 @break
                @case('confirmed') bg-indigo-100 text-indigo-700 @break
                @default bg-amber-100 text-amber-700
            @endswitch
        ">
            {{-- Menampilkan label status pesanan --}}
            @if($order->status == 'pending') Menunggu Konfirmasi
            @elseif($order->status == 'confirmed') Dikonfirmasi — Menunggu Pembayaran
            @elseif($order->status == 'dp_paid' || $order->status == 'processing') Diproses (DP Lunas)
            @elseif($order->status == 'completed') Selesai
            @else {{ $order->status }} @endif
        </span>
    </div>

    <div class="grid md:grid-cols-3 gap-6">

        {{-- Detail Konten Katering Utama --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-semibold text-charcoal mb-4 text-base border-b border-gray-50 pb-2">Detail Paket</h2>
                <div class="flex gap-4">
                    <div class="w-20 h-20 bg-gray-50 rounded-xl flex items-center justify-center text-3xl select-none border border-gray-100">🍱</div>
                    <div class="flex-1">
                        <h3 class="font-bold text-charcoal text-base">{{ $order->package->name }}</h3>
                        <p class="text-gray-500 text-sm mt-0.5">{{ $order->quantity }} kotak × Rp {{ number_format($order->price_per_box, 0, ',', '.') }}</p>
                        
                        @if($order->selected_addons)
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach($order->selected_addons as $addon)
                            <span class="text-xs bg-orange-50 text-orange-600 px-2.5 py-1 rounded-full font-medium border border-orange-100">+ {{ $addon['name'] }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-semibold text-charcoal mb-4 text-base border-b border-gray-50 pb-2">Detail Acara & Pengiriman</h2>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-gray-400 text-xs uppercase tracking-wider">Nama Acara</dt><dd class="font-medium text-charcoal mt-0.5">{{ $order->event_name }}</dd></div>
                    <div><dt class="text-gray-400 text-xs uppercase tracking-wider">Tanggal Pengantaran</dt><dd class="font-medium text-charcoal mt-0.5">{{ \Carbon\Carbon::parse($order->event_date)->format('d M Y') }}</dd></div>
                    <div><dt class="text-gray-400 text-xs uppercase tracking-wider">Lokasi / Gedung</dt><dd class="font-medium text-charcoal mt-0.5">{{ $order->event_location }}</dd></div>
                    <div><dt class="text-gray-400 text-xs uppercase tracking-wider">Waktu Kirim (Tiba)</dt><dd class="font-medium text-charcoal mt-0.5">Pukul {{ $order->delivery_time }} WITA</dd></div>
                    <div class="sm:col-span-2"><dt class="text-gray-400 text-xs uppercase tracking-wider">Alamat Lengkap Tujuan</dt><dd class="font-medium text-charcoal mt-0.5 leading-relaxed">{{ $order->event_address }}</dd></div>
                    @if($order->notes)
                    <div class="sm:col-span-2"><dt class="text-gray-400 text-xs uppercase tracking-wider">Catatan Khusus Pembeli</dt><dd class="font-medium text-orange-600 mt-0.5 bg-orange-50/50 p-2.5 rounded-lg border border-dashed border-orange-100">{{ $order->notes }}</dd></div>
                    @endif
                </dl>
            </div>

            {{-- Riwayat Pencatatan Pembayaran --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-semibold text-charcoal mb-4 text-base border-b border-gray-50 pb-2">Riwayat Transaksi</h2>
                @forelse($order->payments as $payment)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="font-medium text-charcoal text-sm uppercase tracking-wider">
                            {{ $payment->type === 'dp' ? 'Uang Muka (DP 50%)' : 'Pelunasan Akhir' }}
                        </p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }} WITA</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-charcoal">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                        <span class="text-[10px] inline-block px-2 py-0.5 font-bold uppercase rounded-full mt-1
                            {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-amber-100 text-amber-700 border border-amber-200' }}">
                            {{ $payment->status === 'paid' ? 'Lunas' : 'Pending' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-6 text-gray-400">
                    <p class="text-2xl mb-1">💳</p>
                    <p class="text-xs">Belum ada rekaman jejak pembayaran pada pesanan ini.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Sidebar Ringkasan Harga & Tombol Pembayaran Gerbang Xendit --}}
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <h2 class="font-semibold text-charcoal mb-4 text-base border-b border-gray-50 pb-2">Ringkasan Biaya</h2>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-medium text-charcoal">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></div>
                    @if($order->addon_total > 0)
                    <div class="flex justify-between"><span class="text-gray-500">Lauk Tambahan (Addon)</span><span class="font-medium text-charcoal">Rp {{ number_format($order->addon_total, 0, ',', '.') }}</span></div>
                    @endif
                    <div class="border-t pt-2.5 mt-2">
                        <div class="flex justify-between font-bold text-base"><span>Total Tagihan</span><span class="text-orange-600">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
                    </div>
                    
                    <div class="bg-amber-50/70 rounded-lg p-3 mt-3 border border-amber-100/50 space-y-1.5">
                        @if($order->payment_scheme === 'full')
                        <div class="flex justify-between text-xs font-medium text-emerald-800"><span>Skema: Bayar Lunas (100%)</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
                        @else
                        <div class="flex justify-between text-xs font-medium text-amber-800"><span>Wajib Uang Muka (DP 50%)</span><span>Rp {{ number_format($order->dp_amount, 0, ',', '.') }}</span></div>
                        <div class="flex justify-between text-xs font-medium text-gray-600 border-t border-amber-200/40 pt-1.5"><span>Sisa Pelunasan Selesai</span><span>Rp {{ number_format($order->remaining_amount, 0, ',', '.') }}</span></div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- BLOK LOGIKA INTERAKTIF PEMBAYARAN GERBANG DIGITAL RAISSA CATERING --}}
            <div>
                @php
                $dpPayment = $order->dpPayment;
                $fullPayment = $order->fullPayment;
                $dpInvoiceUrl = $dpPayment ? ($dpPayment->xendit_response['invoice_url'] ?? null) : null;
                $fullInvoiceUrl = $fullPayment ? ($fullPayment->xendit_response['invoice_url'] ?? null) : null;
            @endphp

            {{-- TAHAP 0: Pesanan Menunggu Konfirmasi Admin --}}
                @if($order->status === 'pending' && !$order->isConfirmed())
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 border-2 border-amber-200 rounded-xl p-5 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-amber-400 via-orange-400 to-amber-400 animate-pulse"></div>
                    <div class="text-3xl mb-2">⏳</div>
                    <h3 class="font-bold text-amber-900 text-sm">Menunggu Konfirmasi Admin</h3>
                    <p class="text-xs text-amber-700/80 mt-1.5 leading-relaxed">Pesanan Anda sedang ditinjau oleh tim kami. Anda akan menerima notifikasi dan dapat melakukan pembayaran setelah pesanan dikonfirmasi.</p>
                    <div class="mt-3 flex items-center justify-center gap-1.5">
                        <span class="w-2 h-2 bg-amber-400 rounded-full animate-bounce" style="animation-delay: 0ms"></span>
                        <span class="w-2 h-2 bg-amber-400 rounded-full animate-bounce" style="animation-delay: 150ms"></span>
                        <span class="w-2 h-2 bg-amber-400 rounded-full animate-bounce" style="animation-delay: 300ms"></span>
                    </div>
                </div>

            {{-- TAHAP 1: Dikonfirmasi — Skema DP — Belum Bayar --}}
                @elseif(($order->status === 'confirmed' && $order->payment_status === 'unpaid' && $order->payment_scheme === 'dp'))
                <div class="bg-orange-50/50 border border-orange-100 rounded-xl p-4 text-center">
                    <div class="mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full text-[10px] font-bold border border-indigo-100 uppercase tracking-wider">✓ Pesanan Dikonfirmasi</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3 leading-relaxed">Pesanan dikonfirmasi! Silakan bayar uang muka (DP 50%) untuk memulai proses katering.</p>
                    
                    <form action="{{ route('customer.orders.pay-dp', $order->order_number) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-orange-500 text-white font-semibold py-3 rounded-lg hover:bg-orange-600 transition-colors shadow-sm text-sm">
                            💳 Bayar DP Rp {{ number_format($order->dp_amount, 0, ',', '.') }}
                        </button>
                    </form>
                </div>

            {{-- TAHAP 1-ALT: Dikonfirmasi — Skema FULL — Belum Bayar --}}
                @elseif(($order->status === 'confirmed' && $order->payment_status === 'unpaid' && $order->payment_scheme === 'full'))
                <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-4 text-center">
                    <div class="mb-2">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-bold border border-emerald-100 uppercase tracking-wider">✓ Pesanan Dikonfirmasi</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3 leading-relaxed">Pesanan dikonfirmasi! Admin meminta pembayaran penuh langsung untuk pesanan ini.</p>
                    
                    <form action="{{ route('customer.orders.pay-full', $order->order_number) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-emerald-600 text-white font-semibold py-3 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm text-sm">
                            💰 Bayar Lunas Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </button>
                    </form>
                </div>

            {{-- TAHAP 1.5: Jika Invoice DP sudah dibuat dan menunggu pembayaran --}}
                @elseif($order->payment_status === 'dp_pending')
                <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-600 mb-3 leading-relaxed">Tagihan DP Anda sudah dibuat dan menunggu pembayaran. Silakan selesaikan pembayaran melalui link berikut atau ulangi pembayaran jika invoice sudah kadaluarsa.</p>

                    @if($dpInvoiceUrl)
                    <a href="{{ $dpInvoiceUrl }}" target="_blank" class="inline-flex items-center justify-center w-full bg-orange-500 text-white font-semibold py-3 rounded-lg hover:bg-orange-600 transition-colors shadow-sm text-sm">
                        🔗 Buka Invoice DP
                    </a>
                    @else
                    <form action="{{ route('customer.orders.pay-dp', $order->order_number) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-orange-500 text-white font-semibold py-3 rounded-lg hover:bg-orange-600 transition-colors shadow-sm text-sm">
                            💳 Ulangi Pembayaran DP Rp {{ number_format($order->dp_amount, 0, ',', '.') }}
                        </button>
                    </form>
                    @endif
                </div>

                {{-- TAHAP 2: Jika DP Sudah Lunas, Munculkan Tombol Pelunasan Sisa Biaya Akhir --}}
                @elseif($order->payment_status === 'dp_paid' || $order->status === 'dp_paid')
                <div class="bg-green-50/50 border border-green-100 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-600 mb-3 leading-relaxed">Uang muka berhasil diverifikasi! Silakan lakukan pelunasan sisa tagihan katering Anda:</p>
                    
                    <form action="{{ route('customer.orders.pay-full', $order->order_number) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition-colors shadow-sm text-sm">
                            💰 Bayar Sisa Pelunasan Rp {{ number_format($order->remaining_amount, 0, ',', '.') }}
                        </button>
                    </form>
                    <p class="text-[11px] text-gray-400 mt-2">atau lakukan pelunasan tunai via Cash on Delivery (COD)</p>

                </div>

                {{-- TAHAP 2.5: Jika Invoice Pelunasan sudah dibuat dan menunggu pembayaran --}}
                @elseif($order->payment_status === 'full_pending')
                <div class="bg-amber-50/50 border border-amber-100 rounded-xl p-4 text-center">
                    <p class="text-xs text-gray-600 mb-3 leading-relaxed">Tagihan pelunasan sudah dibuat dan menunggu pembayaran. Silakan selesaikan pembayaran melalui link berikut.</p>

                    @if($fullInvoiceUrl)
                    <a href="{{ $fullInvoiceUrl }}" target="_blank" class="inline-flex items-center justify-center w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition-colors shadow-sm text-sm">
                        🔗 Buka Invoice Pelunasan
                    </a>
                    @else
                    <p class="text-xs text-gray-500">Tautan invoice belum tersedia. Silakan hubungi customer service jika Anda memerlukan bantuan.</p>
                    @endif
                </div>

                {{-- TAHAP 3: Jika Pesanan Sudah Lunas Total Keseluruhan --}}
                @elseif($order->payment_status === 'fully_paid' || $order->status === 'completed')
                <div class="bg-green-600 text-white rounded-xl p-4 text-center shadow-sm">
                    <p class="font-bold text-sm">✓ Tagihan Lunas Total</p>
                    <p class="text-xs text-green-100 mt-1 leading-relaxed">Terima kasih! Pesanan Anda telah dijadwalkan masuk antrean dapur produksi.</p>
                </div>
                @endif
            </div>

            {{-- Tautan Review & Status Ulasan --}}
            @if($order->status === 'completed')
                @if($order->review)
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                        <h2 class="font-semibold text-charcoal mb-4 text-base border-b border-gray-50 pb-2">Review Anda</h2>
                        <div class="flex items-center gap-2 mb-3 text-yellow-400 text-lg">
                            @for($i=0; $i < $order->review->rating; $i++) ⭐ @endfor
                        </div>
                        <p class="text-sm text-gray-600 mb-4">"{{ $order->review->comment }}"</p>
                        <p class="text-xs text-gray-500">Terima kasih telah memberi feedback. Review Anda membantu kami meningkatkan layanan.</p>
                    </div>
                @else
                    <div class="bg-orange-50/70 border border-orange-100 rounded-xl p-6 shadow-sm">
                        <h2 class="font-semibold text-charcoal mb-2">Pesanan selesai!</h2>
                        <p class="text-sm text-gray-600 mb-4">Berikan rating dan komentar agar kami terus meningkatkan layanan Raissa Catering.</p>
                        <a href="{{ route('customer.orders.review', $order->order_number) }}" class="inline-flex items-center justify-center w-full bg-orange-500 text-white px-6 py-3 rounded-2xl font-semibold hover:bg-orange-600 transition">Beri Review Sekarang</a>
                    </div>
                @endif
            @endif

            {{-- Tautan Kontak Bantuan Bawaan Toko --}}
            <a href="https://wa.me/628123456789?text=Halo%20Raissa%20Catering,%20saya%20ingin%20bertanya%20mengenai%20perkembangan%20antrean%20Pesanan%20%23{{ $order->order_number }}"
               target="_blank"
               class="block w-full text-center bg-gray-900 text-white py-3 rounded-lg font-semibold text-sm hover:bg-black transition-colors shadow-sm">
                💬 Hubungi Customer Service
            </a>
        </div>
    </div>
</div>
@endsection