<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Raissa Catering') }} - Login</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gradient-to-br from-primary/10 via-amber-50 to-white text-slate-900">
        <div class="relative min-h-screen flex items-center justify-center px-4 py-10">
            <div class="absolute inset-x-0 top-0 h-48 bg-primary/10 blur-3xl"></div>
            <div class="relative w-full max-w-6xl">
                <div class="grid gap-8 xl:grid-cols-[1.05fr_0.95fr] items-center">
                    <div class="hidden lg:flex flex-col justify-between rounded-[2rem] bg-white shadow-[0_24px_80px_rgba(15,23,42,0.08)] border border-slate-200/80 p-10">
                        <div>
                            <span class="text-xs uppercase tracking-[0.35em] text-primary/75">Raissa Catering</span>
                            <h2 class="mt-6 text-4xl font-semibold tracking-tight text-primary">Login untuk pengalaman pemesanan terbaik</h2>
                            <p class="mt-4 max-w-xl text-base leading-7 text-slate-600">Kelola pesanan, cek status catering, dan nikmati pelayanan yang lebih cepat dengan akun Anda.</p>
                        </div>

                        <div class="mt-10 grid gap-4">
                            <div class="rounded-[1.75rem] border border-primary/20 bg-primary/5 p-5">
                                <p class="text-sm font-semibold text-primary">Praktis</p>
                                <p class="mt-2 text-sm text-slate-600">Masuk dengan cepat untuk melihat paket, invoice, dan progress pemesanan.</p>
                            </div>
                            <div class="rounded-[1.75rem] border border-primary/20 bg-primary/5 p-5">
                                <p class="text-sm font-semibold text-primary">Aman</p>
                                <p class="mt-2 text-sm text-slate-600">Data Anda tersimpan dengan aman dan selalu tersedia saat Anda membutuhkannya.</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[2rem] bg-white shadow-[0_30px_90px_rgba(15,23,42,0.09)] border border-slate-200/70 overflow-hidden">
                        <div class="p-8 sm:p-10">
                            <div class="text-center mb-8">
                                <a href="/" class="mx-auto inline-flex items-center justify-center h-24 w-24 rounded-full bg-primary/10 text-primary mb-6 shadow-sm overflow-hidden border border-primary/10">
                                    <img src="{{ asset('images/raissa-catering.png') }}" alt="Raissa Catering logo" class="h-full w-full object-contain" />
                                </a>
                                <h1 class="text-3xl font-semibold tracking-tight text-slate-900">{{ config('', 'Raissa Catering') }}</h1>
                                <p class="mt-2 text-sm text-slate-500">Silakan masuk untuk melanjutkan pemesanan catering Anda.</p>
                            </div>

                            <div class="rounded-[1.75rem] border border-slate-200/70 bg-slate-50 p-6 shadow-sm">
                                <div class="text-center mb-6">
                                    <p class="text-sm uppercase tracking-[0.35em] text-primary/75">Selamat datang kembali</p>
                                    <p class="mt-3 text-base text-slate-600">Isi detail Anda dan kelola acara dengan mudah.</p>
                                </div>

                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>

                <p class="mt-10 text-center text-sm text-slate-500">
                    © 2026 {{ config('app.name', 'Raissa Catering') }}. All rights reserved.
                </p>
            </div>
        </div>
    </body>
</html>
