<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SIGA-Formosa'))</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Estilos de Livewire --}}
    @livewireStyles
</head>

<body class="font-sans antialiased min-h-screen bg-[#fcfcfc] text-[#2C3E50] relative dark:bg-[#0f0f0f]" x-data="{ dirty: false }">

    <div class="absolute inset-0 z-0 pointer-events-none hidden dark:block">
        <div class="w-full h-full bg-[radial-gradient(circle_at_center,_rgba(59,157,255,0.25)_0%,_rgba(155,56,242,0.2)_60%,_transparent_100%)]"></div>
    </div>

    <header class="backdrop-blur-lg bg-gradient-to-r from-[#2e73b8] to-[#9257c5] fixed w-full top-0 z-50 h-16 flex items-center justify-between px-4 border-b border-white/20">

        <div class="w-28 hidden md:block"></div>

        <div class="flex-grow text-center">
            <h1 class="text-2xl font-semibold drop-shadow-md 
                       bg-gradient-to-r from-[#9b38f2] to-[#3b9dff] 
                       bg-clip-text text-transparent"
                style="-webkit-text-stroke: 0.2px white;">
                SIGA – UTN
            </h1>
        </div>

        <div class="w-28 flex justify-end flex-shrink-0 items-center gap-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center gap-2 text-sm font-medium text-[#ffffff] hover:text-purple-200 transition px-2 py-1 rounded focus:outline-none">
                    <span class="hidden md:inline">{{ Auth::user()->name }}</span>

                    @if(Auth::user()->foto)
                        <img src="{{ asset(Auth::user()->foto) }}" alt="Avatar"
                             class="w-8 h-8 rounded-full object-cover border border-white/20">
                    @else
                        <div class="w-8 h-8 rounded-full bg-white/30 flex items-center justify-center text-[#2C3E50] font-semibold border border-white/20">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </button>

                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 text-[#2C3E50] dark:text-gray-200 rounded-md shadow-lg py-1 z-50 ring-1 ring-black ring-opacity-5"
                     style="display: none;">
                    
                    <a href="{{ url('/perfil') }}"
                        class="block px-4 py-2 text-sm hover:bg-[#A7D3F4]/40 dark:hover:bg-gray-700">
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm hover:bg-[#A7D3F4]/40 dark:hover:bg-gray-700">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="relative z-10 pt-20 pb-10 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        
        @isset($header)
            <div class="mb-6 text-sm font-semibold text-gray-500 dark:text-gray-300 text-center">
                {{ $header }}
            </div>
        @endisset

        @if(session('success'))
            <div class="mb-4 bg-[#27AE60]/20 border border-[#27AE60] text-[#27AE60] dark:text-[#2ecc71] px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 bg-[#E74C3C]/20 border border-[#E74C3C] text-[#E74C3C] dark:text-[#e74c3c] px-4 py-3 rounded relative">
                {{ session('error') }}
            </div>
        @endif

        {{-- SLOT --}}
        {{ $slot }}
    </main>

    {{-- Scripts de Livewire --}}
    @livewireScripts
    @stack('scripts')
</body>

</html>