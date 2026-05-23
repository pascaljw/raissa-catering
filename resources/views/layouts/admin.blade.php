<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | @yield('title', 'Raissa Catering')</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#C8763A', light: '#E8956A', dark: '#9B5A2A' },
                        cream: { DEFAULT: '#FDF6EC', dark: '#F5E6D0' },
                        charcoal: { DEFAULT: '#2D2D2D' },
                    },
                    fontFamily: {
                        display: ['Playfair Display', 'serif'],
                        body: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>body{font-family:'Plus Jakarta Sans',sans-serif;}.card{@apply bg-white rounded-xl shadow-sm border border-gray-100;}</style>
</head>
<body class="bg-gray-50 min-h-screen flex">

{{-- Sidebar --}}
<aside class="w-64 bg-charcoal min-h-screen fixed left-0 top-0 z-40">
    <div class="p-6 border-b border-white/10">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                <span class="text-white font-bold text-sm">R</span>
            </div>
            <div>
                <p class="text-white font-bold text-sm">Raissa Catering</p>
                <p class="text-white/50 text-xs">Admin Panel</p>
            </div>
        </a>
    </div>

    <nav class="p-4 space-y-1">
        @php
        $menu = [
            ['route' => 'admin.dashboard',    'label' => 'Dashboard',       'icon' => '📊'],
            ['route' => 'admin.orders.index', 'label' => 'Pesanan',          'icon' => '📋'],
            ['route' => 'admin.menus.index',  'label' => 'Paket & Menu',     'icon' => '🍱'],
            ['route' => 'admin.pages.edit',   'label' => 'Tentang Kami',     'icon' => '📝'],
            ['route' => 'admin.reports.index','label' => 'Laporan',          'icon' => '📈'],
        ];
        @endphp
        @foreach($menu as $item)
        <a href="{{ route($item['route']) }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
               {{ request()->routeIs($item['route']) ? 'bg-primary text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <span>{{ $item['icon'] }}</span>
            <span>{{ $item['label'] }}</span>
        </a>
        @endforeach
    </nav>

    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-white/10">
        <p class="text-white/60 text-xs mb-2">{{ auth()->user()->name }}</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="text-white/50 hover:text-red-400 text-xs transition-colors">Keluar →</button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="ml-64 flex-1">
    {{-- Top bar --}}
    <header class="bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center sticky top-0 z-30">
        <h2 class="font-semibold text-charcoal">@yield('title', 'Dashboard')</h2>
        <div class="flex items-center gap-3">
            <span class="text-sm text-gray-500">{{ now()->format('d M Y') }}</span>
            <a href="{{ route('home') }}" target="_blank" class="text-xs text-primary hover:underline">Lihat Website →</a>
        </div>
    </header>

    @if(session('success'))
    <div class="mx-6 mt-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
        ✅ {{ session('success') }}
    </div>
    @endif

    @yield('content')
</div>

</body>
</html>
