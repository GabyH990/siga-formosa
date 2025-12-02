<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'SIGA - UTN' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased text-white flex flex-col min-h-screen">

    <!-- ============ VIDEO DE FONDO ============ -->
    <video autoplay muted loop playsinline
        class="fixed top-0 left-0 w-full h-full object-cover -z-20">
        <source src="{{ asset('img/fondo_animado.webm') }}" type="video/mp4">
    </video>

    <!-- ============ LÁMINA SUAVE ============ -->
    <div class="fixed inset-0 bg-black/30 backdrop-blur-[1px] -z-10"></div>

    <!-- ============ NAVBAR ============ -->
    <header class="w-full backdrop-blur-lg bg-black/20 fixed top-0 left-0 z-50 border-b border-white/10">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <!-- LOGO -->
            <div class="flex items-center space-x-2">
                <img src="{{ asset('img/siga_logo.png') }}" alt="Logo SIGA" class="h-10">
                <span class="font-semibold text-xl">SIGA – UTN</span>
            </div>

            <!-- LINKS -->
            <ul class="hidden md:flex items-center space-x-6 font-medium">
                <li><a href="/" class="hover:text-blue-300">Inicio</a></li>
                <li><a href="/#contacto" class="hover:text-blue-300">Contacto</a></li>

                @auth
                    <li><a href="{{ route('dashboard') }}" class="text-blue-300 font-semibold">Panel</a></li>
                @else
                    <li><a href="{{ route('login') }}" class="hover:text-blue-300">Iniciar sesión</a></li>
                @endauth
            </ul>

            <!-- MOBILE MENU -->
            <button id="mobileMenuBtn" class="md:hidden text-white text-2xl">☰</button>
        </nav>

        <div id="mobileMenu"
             class="hidden md:hidden bg-black/40 backdrop-blur-xl px-6 py-4 space-y-3 text-white">
            <a href="/" class="block">Inicio</a>
            <a href="/#contacto" class="block">Contacto</a>

            @auth
                <a href="{{ route('dashboard') }}" class="block text-blue-300 font-semibold">Panel</a>
            @else
                <a href="{{ route('login') }}" class="block">Iniciar sesión</a>
            @endauth
        </div>
    </header>

    <!-- ============ CONTENIDO LIVEWIRE ============ -->
    <main class="pt-32 px-6 max-w-md mx-auto">
        {{ $slot }}
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="mt-auto py-6 text-center bg-black/40 backdrop-blur-lg border-t border-white/10">
        <p class="opacity-90">
            SIGA UTN – Formosa © {{ date('Y') }} | Todos los derechos reservados
        </p>
    </footer>

    @livewireScripts

    <!-- Script menú -->
    <script>
        document.getElementById("mobileMenuBtn").onclick = () =>
            document.getElementById("mobileMenu").classList.toggle("hidden");
    </script>

</body>
</html>
