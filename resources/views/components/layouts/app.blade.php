<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8"/>

    <meta name="application-name" content="{{ config('app.name') }}"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>

    <title>{{ config('app.name') }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="antialiased flex flex-col justify-between h-screen">
<!-- Navbar -->
<nav class="bg-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <a href="{{ route('welcome') }}" class="text-2xl font-bold text-blue-600">{{ config('app.name') }}</a>
            @auth
                <div class="space-x-4">
                    <a href="{{ route('logout') }}" class="text-gray-600 hover:text-blue-600">{{ __('logout') }}</a>
                </div>
            @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600">{{ __('login') }}</a>
                </div>
            @endguest
        </div>
    </div>
</nav>

{{ $slot }}

<!-- Footer -->
<footer class="bg-gray-800 text-white">
    <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col items-center">
        <h3 class="text-xl font-bold mb-4">LibreDesk</h3>
        <p class="text-gray-400">Tu plataforma de reserva de espacios de trabajo</p>
    </div>
</footer>

@filamentScripts
@vite('resources/js/app.js')
</body>
</html>
