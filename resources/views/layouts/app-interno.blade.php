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
</head>

<body class="font-sans antialiased text-white min-h-screen" x-data="{ dirty: false }">

    <!-- =========== VIDEO DE FONDO =========== -->
    <video autoplay muted loop playsinline
        class="fixed top-0 left-0 w-full h-full object-cover -z-20">
        <source src="{{ asset('image/fondo_animado.webm') }}" type="video/webm">
    </video>

    <!-- =========== LÁMINA SUAVE =========== -->
    <div class="fixed inset-0 bg-black/30 backdrop-blur-[1px] -z-10"></div>

    <!-- =========== NAVBAR INTERNO SIGA =========== -->
    <header
        class="backdrop-blur-lg bg-black/30 fixed w-full top-0 z-50 h-16 flex items-center justify-between px-6 border-b border-white/10">

        <!-- IZQUIERDA: Botón atrás -->
        <div class="flex-shrink-0 w-24">
            @if(url()->previous() !== url()->current() && url()->previous() !== route('login') && request()->path() !== '/')
                <a href="{{ url()->previous() }}"
                    @click.prevent="if(dirty) { if(confirm('¿Salir sin guardar?')) window.location.href = '{{ url()->previous() }}'; } else { window.location.href = '{{ url()->previous() }}'; }"
                    class="text-gray-200 hover:text-white flex items-center gap-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Atrás
                </a>
            @endif
        </div>

        <!-- CENTRO: SIGA UTN -->
        <div class="flex-grow text-center">
            <h1 class="text-2xl font-semibold drop-shadow-md">
                SIGA – UTN
            </h1>
        </div>

        <!-- DERECHA: Usuario + menú -->
        <div class="flex-shrink-0">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center gap-2 text-sm font-medium text-gray-200 hover:text-white transition">

                    <div>{{ Auth::user()->name }}</div>

                    @if(Auth::user()->foto)
                        <img src="{{ asset(Auth::user()->foto) }}" alt="Avatar"
                             class="w-8 h-8 rounded-full object-cover">
                    @else
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                </button>

                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-48 bg-white/90 backdrop-blur text-gray-800 rounded-md shadow-lg py-1 z-50"
                    style="display: none;">

                    <a href="{{ url('/perfil') }}"
                        class="block px-4 py-2 text-sm hover:bg-gray-100">
                        Perfil
                    </a>

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

    <!-- =========== CONTENIDO DEL PANEL =========== -->
    <main class="pt-24 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">

        <!-- Flash Messages -->
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

        {{ $slot }}
    </main>

</body>

</html>
