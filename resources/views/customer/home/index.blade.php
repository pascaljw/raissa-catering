@extends('layouts.app')
@section('title', 'Beranda')

@section('content')

{{-- CSS Tambahan untuk Smooth Scroll & Gaya Transisi --}}
<style>
    html {
        scroll-behavior: smooth;
    }
    /* Memastikan elemen yang belum ter-load tidak merusak tata letak sebelum AOS aktif */
    [data-aos] {
        pointer-events: none;
    }
    .aos-animate {
        pointer-events: auto;
    }
</style>

{{-- Hero Section --}}
<section class="relative bg-gradient-to-br from-cream to-cream-dark overflow-hidden py-20">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-2 gap-12 items-center">
        {{-- Animasi: Masuk bergeser dari kiri --}}
        <div data-aos="fade-right" data-aos-duration="1000">
            <span class="text-primary font-semibold text-sm uppercase tracking-widest">Catering Samarinda Terpercaya</span>
            <h1 class="font-display text-5xl font-bold text-charcoal mt-3 mb-5 leading-tight">
                Sajian Lezat<br>untuk Setiap <span class="text-primary">Momen Spesial</span>
            </h1>
            <p class="text-gray-600 text-lg leading-relaxed mb-8">
                Raissa Catering hadir untuk memenuhi kebutuhan catering Anda. Dari nasi kotak pernikahan hingga catering meeting kantor — semua kami layani dengan sepenuh hati.
            </p>
            <div class="flex gap-4 flex-wrap">
                <a href="{{ route('packages.index') }}" class="btn-primary">Lihat Paket Kami</a>
                <a href="https://wa.me/628xxx" class="btn-outline">Hubungi via WhatsApp</a>
            </div>

            {{-- Stats --}}
            <div class="mt-10 flex gap-8">
                <div>
                    <p class="font-display text-3xl font-bold text-primary">500+</p>
                    <p class="text-sm text-gray-500">Acara Dilayani</p>
                </div>
                <div>
                    <p id="review-count-value" class="font-display text-3xl font-bold text-primary">{{ $reviewCount }}</p>
                    <p class="text-sm text-gray-500">Review Terverifikasi</p>
                </div>
                <div>
                    <p id="average-rating-value" class="font-display text-3xl font-bold text-primary">{{ number_format($averageRating, 1) }}★</p>
                    <p class="text-sm text-gray-500">Rating Rata-rata</p>
                </div>
            </div>
        </div>
        
        {{-- Animasi: Masuk bergeser dari kanan --}}
        <div class="relative" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
            <div class="w-full h-80 bg-primary/10 rounded-3xl flex items-center justify-center shadow-md overflow-hidden">
                <img src="{{ asset('images/raissa-catering.png') }}" alt="Raissa Catering" class="max-h-[70%] max-w-[80%] object-contain" />
            </div>
        </div>
    </div>
</section>

{{-- Cara Pesan --}}
<section id="tentang" class="py-16 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <div data-aos="fade-up" data-aos-duration="800">
            <h2 class="font-display text-3xl font-bold text-charcoal mb-3">Cara Pesan Mudah</h2>
            <p class="text-gray-500 mb-12">4 langkah sederhana untuk menikmati catering kami</p>
        </div>
        
        <div class="grid md:grid-cols-4 gap-6">
            @php $steps = [
                ['icon'=>'🔍','num'=>'01','title'=>'Pilih Paket','desc'=>'Browse paket catering sesuai acara dan anggaran Anda'],
                ['icon'=>'📝','num'=>'02','title'=>'Isi Form Pesan','desc'=>'Isi detail acara, tanggal, lokasi, dan jumlah porsi'],
                ['icon'=>'💳','num'=>'03','title'=>'Bayar DP 50%','desc'=>'Amankan pesanan dengan bayar uang muka 50% via Xendit'],
                ['icon'=>'✅','num'=>'04','title'=>'Terima & Lunasi','desc'=>'Makanan diantar, lunasi sisa 50% tunai atau online'],
            ]; @endphp
            
            @foreach($steps as $index => $step)
            {{-- Animasi stagger: Muncul berurutan satu per satu berdasarkan indeks --}}
            <div class="relative p-6 bg-cream rounded-2xl shadow-sm border border-gray-100" 
                 data-aos="zoom-in" 
                 data-aos-duration="600" 
                 data-aos-delay="{{ $index * 150 }}">
                <div class="text-4xl mb-3">{{ $step['icon'] }}</div>
                <div class="absolute top-4 right-4 text-5xl font-bold text-primary/10">{{ $step['num'] }}</div>
                <h3 class="font-semibold text-charcoal mb-2">{{ $step['title'] }}</h3>
                <p class="text-sm text-gray-500">{{ $step['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Paket Catering --}}
<section class="py-16 bg-cream overflow-hidden" id="paket">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
            <h2 class="font-display text-3xl font-bold text-charcoal mb-3">Paket Catering Kami</h2>
            <p class="text-gray-500">Pilih paket yang sesuai dengan kebutuhan acara Anda</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-6">
            @foreach($packages as $index => $package)
            {{-- Animasi: Muncul dari bawah secara bergantian --}}
            <div class="card bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden"
                 data-aos="fade-up"
                 data-aos-duration="700"
                 data-aos-delay="{{ $index * 100 }}">
                <div class="h-48 bg-primary/10 relative overflow-hidden">
                    @if($package->image)
                        <img src="{{ $package->image_url }}" alt="{{ $package->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full text-6xl">🍱</div>
                    @endif
                    <span class="absolute top-3 left-3 bg-primary text-white text-xs px-3 py-1 rounded-full font-medium">
                        {{ $package->event_type_label }}
                    </span>
                </div>
                <div class="p-6">
                    <h3 class="font-display text-xl font-bold text-charcoal mb-1">{{ $package->name }}</h3>
                    <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ $package->description }}</p>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <span class="text-2xl font-bold text-primary">Rp {{ number_format($package->price_per_box, 0, ',', '.') }}</span>
                            <span class="text-gray-400 text-sm">/kotak</span>
                        </div>
                        <span class="text-xs text-gray-400">Min. {{ $package->min_order }} kotak</span>
                    </div>
                    <a href="{{ route('packages.show', $package->slug) }}" class="btn-primary w-full text-center block shadow-sm">Lihat Detail</a>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8" data-aos="fade-up" data-aos-duration="600">
            <a href="{{ route('packages.index') }}" class="btn-outline">Lihat Semua Paket →</a>
        </div>
    </div>
</section>

{{-- Testimoni --}}
<section id="kontak" class="py-16 bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12" data-aos="fade-up" data-aos-duration="800">
            <h2 class="font-display text-3xl font-bold text-charcoal mb-3">Kata Pelanggan Kami</h2>
            <p class="text-gray-500">Kepuasan pelanggan adalah prioritas utama kami</p>
        </div>
        
        <div id="review-cards" class="grid md:grid-cols-3 gap-6">
            @forelse($reviews as $index => $review)
            <div class="bg-cream p-6 rounded-2xl shadow-sm border border-gray-100"
                 data-aos="zoom-in-up"
                 data-aos-duration="700"
                 data-aos-delay="{{ $index * 100 }}">
                <div class="flex text-yellow-400 mb-3">
                    @for($i=0;$i<$review->rating;$i++) ⭐ @endfor
                </div>
                <p class="text-gray-600 text-sm mb-4 italic">"{{ $review->comment }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-sm">
                        {{ substr($review->user->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="font-semibold text-charcoal text-sm">{{ $review->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $review->package->name }}</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="md:col-span-3 rounded-3xl border border-dashed border-gray-200 bg-cream/80 p-10 text-center text-gray-500">
                <p class="text-2xl">⭐</p>
                <p class="mt-4 text-lg font-semibold text-charcoal">Belum ada ulasan pelanggan.</p>
                <p class="mt-2 text-sm">Tetapi review terbaru akan muncul di sini secara otomatis saat pelanggan memberikan rating.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 bg-primary overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 text-center text-white" data-aos="scale-up" data-aos-duration="800">
        <h2 class="font-display text-4xl font-bold mb-4">Siap Pesan Catering?</h2>
        <p class="text-white/80 text-lg mb-8">Pesan sekarang dan dapatkan catering berkualitas untuk acara Anda. Pembayaran mudah, pengiriman tepat waktu.</p>
        <div class="flex gap-4 justify-center flex-wrap">
            <a href="{{ route('packages.index') }}" class="bg-white text-primary font-bold px-8 py-4 rounded-lg hover:bg-cream transition-all duration-200 shadow-md transform hover:-translate-y-0.5">Pesan Sekarang</a>
            <a href="https://wa.me/6285179860754" class="border-2 border-white text-white font-bold px-8 py-4 rounded-lg hover:bg-white/10 transition-all duration-200 transform hover:-translate-y-0.5">Tanya via WhatsApp</a>
        </div>
    </div>
</section>

{{-- Script Integrasi Engine Animasi AOS --}}
<link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Inisialisasi konfigurasi animasi
        AOS.init({
            once: true,      // Animasi hanya berjalan satu kali saat di-scroll (tidak berulang-ulang)
            mirror: false,   // Menonaktifkan animasi ulang saat elemen di-scroll ke atas kembali
            offset: 120,     // Jarak picu awal animasi dari tepi bawah layar browser (dalam piksel)
        });

        const summaryUrl = "{{ route('reviews.summary') }}";
        const averageRatingEl = document.getElementById('average-rating-value');
        const reviewCountEl = document.getElementById('review-count-value');
        const reviewCardsEl = document.getElementById('review-cards');

        const renderStars = rating => '⭐'.repeat(Math.round(rating));
        const escapeHtml = value => String(value || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;').replace(/`/g, '&#96;');

        const refreshReviewSummary = async () => {
            try {
                const response = await fetch(summaryUrl, { headers: { 'Accept': 'application/json' } });
                if (!response.ok) return;

                const data = await response.json();
                if (averageRatingEl) {
                    averageRatingEl.textContent = `${parseFloat(data.average_rating).toFixed(1)}★`;
                }
                if (reviewCountEl) {
                    reviewCountEl.textContent = data.review_count;
                }

                if (reviewCardsEl && Array.isArray(data.reviews)) {
                    reviewCardsEl.innerHTML = data.reviews.map((review, index) => {
                        const comment = review.comment ? escapeHtml(review.comment) : '—';
                        const userName = escapeHtml(review.user_name);
                        const packageName = escapeHtml(review.package);

                        return `
                            <div class="bg-cream p-6 rounded-2xl shadow-sm border border-gray-100" data-aos="zoom-in-up" data-aos-duration="700" data-aos-delay="${index * 100}">
                                <div class="flex text-yellow-400 mb-3">${renderStars(review.rating)}</div>
                                <p class="text-gray-600 text-sm mb-4 italic">"${comment}"</p>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-primary rounded-full flex items-center justify-center text-white font-bold shadow-sm">${userName.charAt(0) || 'U'}</div>
                                    <div>
                                        <p class="font-semibold text-charcoal text-sm">${userName}</p>
                                        <p class="text-xs text-gray-400">${packageName}</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('');
                    if (window.AOS) {
                        AOS.refresh();
                    }
                }
            } catch (error) {
                console.warn('Gagal memuat ringkasan review:', error);
            }
        };

        refreshReviewSummary();
        setInterval(refreshReviewSummary, 15000);
    });
</script>

@endsection