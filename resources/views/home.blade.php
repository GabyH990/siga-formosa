<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>SIGA - Sistema Académico</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="antialiased text-white flex flex-col min-h-screen">

    <!-- ================= VIDEO DE FONDO ================= -->
    <video autoplay muted loop playsinline
        class="fixed top-0 left-0 w-full h-full object-cover -z-20">
        <source src="{{ asset('img/fondo_animado.webm') }}" type="video/mp4">
    </video>

    <!-- ================= LÁMINA SUAVE (opción C) ================= -->
    <div class="fixed inset-0 bg-black/30 backdrop-blur-[1px] -z-10"></div>


    <!-- ================= NAVBAR ================= -->
    <header class="w-full backdrop-blur-lg bg-black/20 fixed top-0 left-0 z-50 border-b border-white/10">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <!-- LOGO -->
            <div class="flex items-center space-x-2">
                <img src="{{ asset('img/siga_logo.png') }}" alt="Logo SIGA" class="h-10">
                <span class="font-semibold text-xl">SIGA – UTN</span>
            </div>

            <!-- LINKS DESKTOP -->
            <ul class="hidden md:flex items-center space-x-6 font-medium">
                <li><a href="#home" class="hover:text-blue-300">Inicio</a></li>
                <li><a href="#contacto" class="hover:text-blue-300">Contacto</a></li>
                

                @if (Route::has('login'))
                    @auth
                        <li><a href="{{ url('/dashboard') }}" class="text-blue-300 font-semibold">Panel</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-blue-300">Iniciar sesión</a></li>
                        
                    @endauth
                @endif
            </ul>

            <!-- MENU MOBIL -->
            <button id="mobileMenuBtn" class="md:hidden text-white text-2xl">☰</button>
        </nav>

        <div id="mobileMenu" class="hidden md:hidden bg-black/40 backdrop-blur-xl px-6 py-4 space-y-3 text-white">
            <a href="#home" class="block">Inicio</a>
            <a href="#contacto" class="block">Contacto</a>
          

            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="block text-blue-300 font-semibold">Panel</a>
                @else
                    <a href="{{ route('login') }}" class="block">Iniciar sesión</a>
                   
                @endauth
            @endif
        </div>
    </header>


    <!-- ================= BANNER ================= -->
    <section id="home" class="pt-32 pb-24 text-center">
        <div class="max-w-7xl mx-auto px-6">
            <h1 class="text-5xl md:text-6xl font-bold drop-shadow-lg">
                Bienvenido al SIGA
            </h1>
           <div class="w-full flex justify-center items-center mt-10">
    <img src="{{ asset('img/siga_logo.png') }}" 
         alt="Logo SIGA"
         class="h-40 drop-shadow-[0_0_15px_rgba(255,255,255,0.6)]">
</div>

            <p class="mt-4 text-lg md:text-xl drop-shadow-md opacity-90">
                Sistema Integral de Gestión Académica – UTN Formosa
            </p>
        </div>
    </section>


    <!-- ================= SECCIÓN INGRESO ================= -->
  <section id="ingreso" class="py-0.5">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold drop-shadow-lg mb-6">Ingreso al Sistema</h2>

        <p class="max-w-2xl mx-auto text-lg opacity-90 drop-shadow mb-10">
            Accede a tu cuenta para ingresar al sistema académico.
        </p>

        <div class="flex justify-center gap-6">

            @if (Route::has('login'))
                @auth
                    <!-- Si el usuario YA está logueado -->
                    <a href="{{ url('/dashboard') }}"
                       class="px-8 py-3 bg-green-600/80 hover:bg-green-700 rounded-lg font-semibold shadow-lg backdrop-blur-sm">
                        Ir al Panel
                    </a>
                @else
                    <!-- Si NO está logueado -->
                    <a href="{{ route('login') }}"
                       class="px-8 py-3 bg-blue-600/80 hover:bg-blue-700 rounded-lg font-semibold shadow-lg backdrop-blur-sm">
                        Iniciar Sesión
                    </a>
                @endauth
            @endif

        </div>
    </div>
</section>



   <!-- ================= CONTACTO ================= -->
<section id="contacto" class="py-24">
    <div class="max-w-5xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-bold drop-shadow-lg mb-6">Contacto</h2>

        <p class="max-w-2xl mx-auto text-lg opacity-90 drop-shadow mb-10">
            Estamos para asistirte ante cualquier duda o consulta.
        </p>

        <div class="grid md:grid-cols-3 gap-6">

            <!-- Sofía Vera -->
            <div class="p-6 bg-white/10 backdrop-blur-md rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold mb-2 drop-shadow">Sofía Vera</h3>
                <p class="opacity-90"><strong>Email:</strong> veraelizabeth785@gmail.com</p>
                <p class="opacity-90"><strong>Tel:</strong> 3718 656146</p>
            </div>

            <!-- Gabriela Heretichi -->
            <div class="p-6 bg-white/10 backdrop-blur-md rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold mb-2 drop-shadow">Gabriela Heretichi</h3>
                <p class="opacity-90"><strong>Email:</strong> gaviheretichi@gmail.com</p>
                <p class="opacity-90"><strong>Tel:</strong> 3704 711518</p>
            </div>

            <!-- Gerardo Medina -->
            <div class="p-6 bg-white/10 backdrop-blur-md rounded-xl shadow-lg">
                <h3 class="text-xl font-semibold mb-2 drop-shadow">Gerardo Medina</h3>
                <p class="opacity-90"><strong>Email:</strong> gerardomedinavv@gmail.com</p>
                <p class="opacity-90"><strong>Tel:</strong> 3704 857048</p>
            </div>

        </div>
    </div>
</section>



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
