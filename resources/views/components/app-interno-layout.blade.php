<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SIGA-Formosa') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Estilos de Livewire --}}
    @livewireStyles
</head>

<body class="font-sans antialiased text-white min-h-screen" x-data="{ dirty: false }">

    <!-- =========== VIDEO DE FONDO =========== -->
    <video autoplay muted loop playsinline
        class="fixed top-0 left-0 w-full h-full object-cover -z-20">
        <source src="{{ asset('image/fondo_animado.webm') }}" type="video/webm">
        <!-- fallback -->
    </video>

    <!-- =========== LÁMINA SUAVE =========== -->
    <div class="fixed inset-0 bg-black/30 backdrop-blur-[1px] -z-10"></div>

    <!-- =========== NAVBAR INTERNO SIGA (mantiene funcionalidad previa) =========== -->
    <header
        class="backdrop-blur-lg bg-black/30 fixed w-full top-0 z-50 h-16 flex items-center justify-between px-4 border-b border-white/10">

        <!-- IZQUIERDA: Botón Atrás (manteniendo tu lógica de dirty) -->
        <div class="flex-shrink-0 w-28">
            <button type="button"
                @click="
                    if (dirty) {
                        if (confirm('¿Salir sin guardar?')) {
                            window.history.back();
                        }
                    } else {
                        window.history.back();
                    }
                "
                class="text-gray-200 hover:text-white flex items-center gap-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                <span class="hidden sm:inline">Atrás</span>
            </button>
        </div>

        <!-- CENTRO: SIGA – UTN -->
        <div class="flex-grow text-center">
            <h1 class="text-2xl font-semibold drop-shadow-md">
                SIGA – UTN
            </h1>
        </div>

        <!-- DERECHA: Menú de Usuario (mantiene foto, nombre y dropdown) -->
        <div class="flex-shrink-0 flex items-center gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center gap-2 text-sm font-medium text-gray-200 hover:text-white transition px-2 py-1 rounded">
                    <span class="hidden md:inline">{{ Auth::user()->name }}</span>

                    @if(Auth::user()->foto)
                        <img src="{{ asset(Auth::user()->foto) }}" alt="Avatar"
                             class="w-8 h-8 rounded-full object-cover border border-white/10">
                    @else
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white/95 text-gray-800 rounded-md shadow-lg py-1 z-50"
                    style="display: none;">
                    <a href="{{ url('/perfil') }}"
                        class="block px-4 py-2 text-sm hover:bg-gray-100">Perfil</a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-100">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- =========== CONTENIDO PRINCIPAL =========== -->
    <main class="pt-20 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Mensajes flash (manteniendo lo que ya tenías) -->
        @if(session('success'))
            <div class="mb-4 bg-green-100/80 border border-green-400 text-green-900 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-red-100/80 border border-red-400 text-red-900 px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        {{-- SLOT: aquí se renderiza el contenido de cada vista (panel, forms, etc.) --}}
        {{ $slot }}
    </main>

    {{-- Scripts de Livewire --}}
    @livewireScripts
</body>

</html>
