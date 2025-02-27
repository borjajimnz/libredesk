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
<nav class="bg-white border">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <a href="{{ route('welcome') }}" class="text-2xl font-bold text-primary-600">
                @setting('name', config('app.name'))
            </a>
            @auth
                <div class="space-x-4">
                    @if(auth()->user()->admin)
                        <a href="{{ route('filament.admin.pages.dashboard') }}" class="text-gray-600 hover:text-primary-600">
                            @translate('admin')
                        </a>
                    @endif
                        <a href="{{ route('profile') }}" class="text-gray-600 hover:text-primary-600">
                            @translate('profile')
                        </a>

                    <a href="{{ route('logout') }}" class="text-gray-600 hover:text-primary-600">
                       @translate('logout')
                    </a>
                </div>
            @else
                <div class="space-x-4">
                    <a href="{{ route('filament.app.auth.login') }}" class="text-gray-600 hover:text-primary-600">
                        @translate('login')
                    </a>
                </div>
            @endguest
        </div>
    </div>
</nav>

{{ $slot }}

<livewire:notifications />
<!-- Footer -->
<footer class="bg-transparent border-t text-primary-2000">
    <div class="max-w-6xl mx-auto px-4 py-8 flex flex-col items-center gap-2">
        <p class="text-primary-400">{{ config('app.name') }} {{config('libredesk.version')}}</p>
    </div>
</footer>

@filamentScripts
@vite('resources/js/app.js')
</body>
</html>
