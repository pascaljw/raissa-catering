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
    <body class="font-sans text-gray-900 antialiased">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <a href="/" class="inline-block">
                        <x-application-logo class="auth-logo" />
                    </a>
                    <h1 class="auth-title">{{ config('app.name', 'Raissa Catering') }}</h1>
                    <p class="auth-subtitle">Layanan Catering Terpercaya</p>
                </div>

                {{ $slot }}
            </div>

            <!-- Footer Text -->
            <p style="color: rgba(255, 255, 255, 0.8); font-size: 0.875rem; margin-top: 2rem; text-align: center;">
                © 2026 {{ config('app.name', 'Raissa Catering') }}. All rights reserved.
            </p>
        </div>
    </body>
</html>
