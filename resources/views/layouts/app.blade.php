<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Raissa Catering') | Raissa Catering Samarinda</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- TailwindCSS CDN (ganti dengan Vite di production) --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#C8763A', light: '#E8956A', dark: '#9B5A2A' },
                        cream:    { DEFAULT: '#FDF6EC', dark: '#F5E6D0' },
                        charcoal: { DEFAULT: '#2D2D2D', light: '#4A4A4A' },
                    },
                    fontFamily: {
                        display: ['Playfair Display', 'serif'],
                        body:    ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        /* Mengunci smooth scroll global pada level CSS */
        html { 
            scroll-behavior: smooth !important; 
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-display { font-family: 'Playfair Display', serif; }
        .btn-primary { @apply bg-primary text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-dark transition-all duration-200 shadow-md hover:shadow-lg; }
        .btn-outline  { @apply border-2 border-primary text-primary px-6 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition-all duration-200; }
        .card { @apply bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden; }
    </style>

    @stack('styles')
</head>
<body class="bg-cream min-h-screen">

{{-- Navbar --}}
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-12 h-12 bg-white/10 rounded-full flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('images/raissa-catering.png') }}" alt="Raissa Catering" class="h-full w-full object-contain" />
                </div>
                <span class="font-display text-xl font-bold text-charcoal">Raissa <span class="text-primary">Catering</span></span>
            </a>

            {{-- Navigasi Menu Aktif Dinamis --}}
            <div class="hidden md:flex items-center gap-6">
                {{-- Menu Beranda --}}
                <a href="{{ route('home') }}" class="font-medium transition-colors {{ request()->routeIs('home') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary' }}">
                    Beranda
                </a>
                
                {{-- PERBAIKAN: Menu Paket Catering dibuat Smooth Scroll jika di Beranda --}}
                <a href="{{ request()->is('/') ? '#paket' : route('packages.index') }}" class="js-smooth-scroll font-medium transition-colors {{ request()->routeIs('packages.*') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary' }}">
                    Paket Catering
                </a>
                
                {{-- Menu Tentang Kami --}}
                <a href="{{ route('about') }}" class="font-medium transition-colors {{ request()->routeIs('about') ? 'text-primary font-semibold' : 'text-gray-600 hover:text-primary' }}">
                    Tentang Kami
                </a>
                
                {{-- Menu Kontak --}}
                <a href="{{ request()->is('/') ? '#kontak' : url('/#kontak') }}" class="js-smooth-scroll text-gray-600 hover:text-primary font-medium transition-colors">
                    Kontak
                </a>
            </div>

            <div class="flex items-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn-outline text-sm py-2 px-4">Masuk</a>
                    <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-4">Daftar</a>
                @else
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn-primary text-sm py-2 px-4">Admin Panel</a>
                    @else
                        {{-- Menu Pesanan Saya (Deteksi Aktif Dinamis) --}}
                        <a href="{{ route('customer.orders.index') }}" class="font-medium transition-colors {{ request()->routeIs('customer.orders.*') ? 'text-primary font-semibold mr-2' : 'text-gray-600 hover:text-primary mr-2' }}">
                            Pesanan Saya
                        </a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-red-500 text-sm font-medium transition-colors">Keluar</button>
                    </form>
                @endguest
            </div>
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    </div>
@endif

@if($errors->any())
    <div class="max-w-7xl mx-auto px-4 mt-4">
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- Footer --}}
<footer class="bg-charcoal text-gray-300 mt-16 py-12" id="kontak">
    <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8">
        <div>
            <h3 class="font-display text-white text-xl font-bold mb-3">Raissa Catering</h3>
            <p class="text-sm leading-relaxed">Catering terpercaya di Samarinda, Kalimantan Timur. Melayani berbagai acara dengan cita rasa terbaik.</p>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Hubungi Kami</h4>
            <ul class="text-sm space-y-2">
                <li>📍 Samarinda, Kalimantan Timur</li>
                <li>📞 <a href="tel:+628xxx" class="hover:text-primary">+62 8xx-xxxx-xxxx</a></li>
                <li>📧 <a href="mailto:info@raissacatering.com" class="hover:text-primary">info@raissacatering.com</a></li>
                <li>💬 <a href="https://wa.me/628xxx" class="hover:text-primary">WhatsApp Kami</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-white font-semibold mb-3">Layanan</h4>
            <ul class="text-sm space-y-2">
                <li><a href="{{ route('packages.index') }}" class="hover:text-primary">Paket Catering</a></li>
                <li><a href="#" class="hover:text-primary">Catering Pernikahan</a></li>
                <li><a href="#" class="hover:text-primary">Catering Meeting</a></li>
                <li><a href="#" class="hover:text-primary">Nasi Box Harian</a></li>
            </ul>
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 mt-8 pt-8 border-t border-gray-700 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} Raissa Catering. All rights reserved.
    </div>
</footer>

{{-- Script Pendukung Gerakan Smooth Scroll Sempurna via JavaScript --}}
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll(".js-smooth-scroll");

        links.forEach(link => {
            link.addEventListener("click", function (e) {
                const href = this.getAttribute("href");

                // Jika link mengandung karakter '#' dan user sedang di halaman beranda
                if (href.includes("#") && window.location.pathname === "/") {
                    e.preventDefault();
                    
                    const targetId = href.substring(href.indexOf("#"));
                    const targetElement = document.querySelector(targetId);

                    if (targetElement) {
                        // Menghitung kompensasi offset tinggi navbar h-16 (64px) agar posisi scroll tidak memotong judul konten
                        const navbarOffset = 64;
                        const elementPosition = targetElement.getBoundingClientRect().top;
                        const offsetPosition = elementPosition + window.pageYOffset - navbarOffset;

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: "smooth"
                        });
                    }
                }
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>