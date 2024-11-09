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

<body class="antialiased">
<!-- Navbar -->
<nav class="bg-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="text-2xl font-bold text-blue-600">{{ config('app.name') }}</div>
            <div class="space-x-4">
                <a href="{{ route('welcome') }}" class="text-gray-600 hover:text-blue-600">Inicio</a>
                <a href="{{ route('book') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Reservar</a>
            </div>
        </div>
    </div>
</nav>

{{ $slot }}

<!-- Footer -->
<footer class="bg-gray-800 text-white">
    <div class="max-w-6xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">DeskBook</h3>
                <p class="text-gray-400">Tu plataforma de reserva de espacios de trabajo</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Empresa</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white">Sobre nosotros</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Contacto</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Soporte</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white">Centro de ayuda</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Términos de uso</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white">Privacidad</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Síguenos</h4>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white">Twitter</a>
                    <a href="#" class="text-gray-400 hover:text-white">LinkedIn</a>
                    <a href="#" class="text-gray-400 hover:text-white">Instagram</a>
                </div>
            </div>
        </div>
    </div>
</footer>

@filamentScripts
@vite('resources/js/app.js')
</body>
</html>
