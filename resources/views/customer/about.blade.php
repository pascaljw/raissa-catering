@extends('layouts.app')
@section('title', $page->title ?? 'Tentang Kami')

@section('content')
<section class="bg-cream py-20">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="text-sm uppercase tracking-[0.3em] text-primary font-semibold">Tentang Raissa Catering</p>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-charcoal mt-4">{{ $page->title ?? 'Solusi Catering Profesional untuk Setiap Acara' }}</h1>
            <p class="mt-4 text-gray-600 max-w-3xl mx-auto leading-relaxed text-base md:text-lg">
                {{ $page->subtitle ?? 'Raissa Catering hadir sebagai partner catering terpercaya di Samarinda. Kami menyediakan berbagai paket nasi kotak dan catering prasmanan untuk pernikahan, ulang tahun, meeting kantor, syukuran, dan acara keluarga.' }}
            </p>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="space-y-6">
                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8">
                    <h2 class="font-display text-2xl font-bold text-charcoal mb-3">Visi Kami</h2>
                    <p class="text-gray-600 leading-relaxed">Menjadi pilihan catering terbaik di Samarinda dengan layanan cepat, rasa lezat, dan penataan sajian yang rapi. Semua paket dirancang untuk memudahkan Anda menyelenggarakan acara tanpa repot.</p>
                </div>

                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8">
                    <h2 class="font-display text-2xl font-bold text-charcoal mb-3">Misi Kami</h2>
                    <ul class="space-y-3 text-gray-600 list-disc list-inside leading-relaxed">
                        <li>Menyajikan makanan dengan kualitas bahan segar dan cita rasa konsisten.</li>
                        <li>Memberikan pelayanan cepat dan keramahan tim catering.</li>
                        <li>Membantu acara Anda berjalan lancar dari order sampai pengiriman.</li>
                    </ul>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white border border-gray-100 rounded-3xl shadow-sm p-8">
                    <h2 class="font-display text-2xl font-bold text-charcoal mb-3">Apa yang Kami Tawarkan</h2>
                    <div class="space-y-4 text-gray-600 leading-relaxed text-sm">
                        @foreach(explode("\n\n", $page->body ?? 'Raissa Catering menyediakan paket catering dengan pilihan menu yang fleksibel, dari nasi kotak standar sampai paket premium untuk tamu istimewa.\n\nKami juga melayani permintaan tambahan seperti minuman, dessert, dan kebutuhan khusus menu halal.\n\nSetiap pesanan didampingi dokumentasi pesanan, sehingga Anda bisa memantau status secara online.') as $paragraph)
                            <p>{{ trim($paragraph) }}</p>
                        @endforeach
                    </div>
                </div>

                <div class="bg-primary text-white rounded-3xl shadow-sm p-8">
                    <h2 class="font-display text-2xl font-bold mb-3">Hubungi Kami</h2>
                    <p class="text-white/80 leading-relaxed mb-6">Ingin tahu paket terbaik untuk acara Anda? Tim kami siap membantu konsultasi menu dan estimasi harga.</p>
                    <a href="https://wa.me/628123456789" target="_blank" class="inline-flex items-center justify-center gap-2 bg-white text-primary font-semibold px-6 py-3 rounded-xl shadow-lg hover:bg-white/90 transition">Chat via WhatsApp</a>
                </div>
            </div>
        </div>

        <div class="mt-16 bg-white rounded-3xl border border-gray-100 shadow-sm p-8">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="text-center">
                    <p class="font-display text-4xl text-primary font-bold">500+</p>
                    <p class="mt-2 text-gray-600">Acara Dilayani</p>
                </div>
                <div class="text-center">
                    <p class="font-display text-4xl text-primary font-bold">98%</p>
                    <p class="mt-2 text-gray-600">Pelanggan Puas</p>
                </div>
                <div class="text-center">
                    <p class="font-display text-4xl text-primary font-bold">5★</p>
                    <p class="mt-2 text-gray-600">Rating Rata-rata</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
