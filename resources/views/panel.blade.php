{{-- resources/views/panel.blade.php --}}
<x-app-interno-layout>

    @php
        $careerCode = isset($activeCareer) ? $activeCareer->codigo : null;
    @endphp

    {{-- HEADER CON LOGOS (Estructura del Archivo 2 + Estilos Dark Mode) --}}
    <x-slot name="header">
        <div class="flex items-center gap-3 text-gray-800 dark:text-white">
            @if ($careerCode === 'TUP')
                {{-- Logo TUP + título --}}
                <img src="{{ asset('image/tup.png') }}" alt="Logo TUP" class="w-8 h-8 object-contain">
                <span class="text-lg font-semibold">Panel Carrera TUP</span>
            @elseif ($careerCode === 'LPB')
                {{-- Logo / ícono LIC:  Bioimagen --}}
                <svg viewBox="0 0 1024 1024" class="w-8 h-8 text-violet-500 dark:text-violet-300" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                    <path d="M500.8 766.4c8 4.8 11.2 14.4 6.4 22.4s-14.4 11.2-22.4 6.4l-176-96c-8-4.8-11.2-14.4-6.4-22.4 4.8-8 14.4-11.2 22.4-6.4l176 96z" />
                    <path d="M652.8 460.8v-32c57.6 0 86.4 89.6 64 188.8-22.4 96-134.4 179.2-224 179.2v-32c75.2 0 174.4-73.6 192-155.2 19.2-81.6-3.2-148.8-32-148.8zM316.8 908.8h352c9.6 0 16 6.4 16 16s-6.4 16-16 16h-352c-9.6 0-16-6.4-16-16s6.4-16 16-16z" />
                    <path d="M652.8 94.4c46.4 27.2 62.4 84.8 35.2 131.2L528 502.4l-166.4-96 160-276.8c25.6-46.4 84.8-60.8 131.2-35.2z" />
                    <path d="M336 428.8c-9.6-6.4-14.4-17.6-11.2-24s16-9.6 27.2-3.2l184 107.2c9.6 6.4 14.4 17.6 11.2 24-4.8 8-16 9.6-27.2 3.2L336 428.8z" />
                    <path d="M358.4 443.2l139.2 80-24 41.6c-4.8 8-14.4 9.6-22.4 6.4l-110.4-64c-8-4.8-9.6-14.4-6.4-22.4l24-41.6z" />
                    <path d="M652.8 444.8m-48 0a48 48 0 1 0 96 0 48 48 0 1 0-96 0Z" />
                    <path d="M476.8 828.8v96h32v-96z" />
                    <path d="M492.8 780.8m-64 0a64 64 0 1 0 128 0 64 64 0 1 0-128 0Z" />
                </svg>
                <span class="text-lg font-semibold">Lic. Bioimagen</span>
            @else
                <span class="text-lg font-semibold">Panel Principal</span>
            @endif
        </div>
    </x-slot>

    {{-- CONTENIDO DEL PANEL --}}
    <div class="space-y-6 relative z-10">

        @if ($esSuperAdmin ?? false)

            {{-- ======================================
               VISTA SUPER ADMIN
               ====================================== --}}

            @if (!$activeCareer)
                {{-- Super Admin SIN carrera seleccionada --}}
                <div class="space-y-4">
                    <h1 class="text-xl font-semibold text-black dark:text-white text-center">Panel Admin</h1>

                    {{-- Gestión de usuarios --}}
                    <a href="{{ route('superadmin.bedeles.index') }}"
                        class="block bg-white backdrop-blur-md border border-blue-400 shadow rounded-lg p-6 hover:bg-blue-100 dark:bg-blue-700 dark:hover:bg-blue-500 transition">

                        <div class="flex items-center gap-3">
                            {{-- Icono Usuarios --}}
                            <svg class="w-8 h-8 text-blue-500 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M17 20v-2a4 4 0 00-3-3.87M9 9a4 4 0 118 0 4 4 0 01-8 0zm6 3a4 4 0 00-3 3.87M7 20v-2a4 4 0 013-3.87" />
                            </svg>
                            <h2 class="text-xl font-semibold text-black dark:text-white">Gestión de usuarios</h2>
                        </div>

                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Crear y administrar usuarios Bedel y sus carreras asignadas.
                        </p>
                    </a>

                    {{-- Auditoría --}}
                    <a href="{{ route('superadmin.audit.index') }}"
                        class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 dark:bg-indigo-700 dark:hover:bg-indigo-500 transition">

                        <div class="flex items-center gap-3">
                            {{-- Icono Auditoría --}}
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-500 dark:text-indigo-200"
                                viewBox="0 0 1024 1024" fill="currentColor">
                                <path d="M296 250c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h384c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H296zm184 144H296c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8zm-48 458H208V148h560v320c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8V108c0-17.7-14.3-32-32-32H168c-17.7 0-32 14.3-32 32v784c0 17.7 14.3 32 32 32h264c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm440-88H728v-36.6c46.3-13.8 80-56.6 80-107.4 0-61.9-50.1-112-112-112s-112 50.1-112 112c0 50.7 33.7 93.6 80 107.4V764H520c-8.8 0-16 7.2-16 16v152c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V780c0-8.8-7.2-16-16-16zM646 620c0-27.6 22.4-50 50-50s50 22.4 50 50-22.4 50-50 50-50-22.4-50-50zm180 266H566v-60h260v60z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-black dark:text-white">Auditoría</h2>
                        </div>

                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Ver acciones registradas en el sistema (altas, ediciones, desactivaciones, etc.).
                        </p>
                    </a>

                    {{-- TUP --}}
                    <a href="{{ route('panel', ['career' => 'TUP']) }}"
                        class="block bg-white backdrop-blur-md border border-violet-400 shadow rounded-lg p-6 hover:bg-violet-100 dark:bg-violet-700 dark:hover:bg-violet-500 transition">

                        <div class="flex items-center gap-3">
                            {{-- Icono Carrera (imagen local) --}}
                            <img src="{{ asset('image/tup.png') }}" alt="Icono TUP" class="w-8 h-8 object-contain">
                            <h2 class="text-xl font-semibold text-black dark:text-white">TUP</h2>
                        </div>

                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Ingresar al sistema como administrativo/bedel de la carrera TUP.
                        </p>
                    </a>

                    {{-- LPB --}}
                    <a href="{{ route('panel', ['career' => 'LPB']) }}"
                        class="block bg-white backdrop-blur-md border border-purple-400 shadow rounded-lg p-6 hover:bg-purple-100 dark:bg-purple-700 dark:hover:bg-purple-500 transition">

                        <div class="flex items-center gap-3">
                            {{-- Icono Carrera --}}
                            <svg viewBox="0 0 1024 1024" class="w-8 h-8 text-purple-600 dark:text-purple-300" version="1.1" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                                <path d="M500.8 766.4c8 4.8 11.2 14.4 6.4 22.4s-14.4 11.2-22.4 6.4l-176-96c-8-4.8-11.2-14.4-6.4-22.4 4.8-8 14.4-11.2 22.4-6.4l176 96z" />
                                <path d="M652.8 460.8v-32c57.6 0 86.4 89.6 64 188.8-22.4 96-134.4 179.2-224 179.2v-32c75.2 0 174.4-73.6 192-155.2 19.2-81.6-3.2-148.8-32-148.8zM316.8 908.8h352c9.6 0 16 6.4 16 16s-6.4 16-16 16h-352c-9.6 0-16-6.4-16-16s6.4-16 16-16z" />
                                <path d="M652.8 94.4c46.4 27.2 62.4 84.8 35.2 131.2L528 502.4l-166.4-96 160-276.8c25.6-46.4 84.8-60.8 131.2-35.2z" />
                                <path d="M336 428.8c-9.6-6.4-14.4-17.6-11.2-24s16-9.6 27.2-3.2l184 107.2c9.6 6.4 14.4 17.6 11.2 24-4.8 8-16 9.6-27.2 3.2L336 428.8z" />
                                <path d="M358.4 443.2l139.2 80-24 41.6c-4.8 8-14.4 9.6-22.4 6.4l-110.4-64c-8-4.8-9.6-14.4-6.4-22.4l24-41.6z" />
                                <path d="M652.8 444.8m-48 0a48 48 0 1 0 96 0 48 48 0 1 0-96 0Z" />
                                <path d="M476.8 828.8v96h32v-96z" />
                                <path d="M492.8 780.8m-64 0a64 64 0 1 0 128 0 64 64 0 1 0-128 0Z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-black dark:text-white">LPB</h2>
                        </div>

                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Ingresar al sistema como administrativo/bedel de la carrera LPB.
                        </p>
                    </a>

                </div>
            @else
                {{-- ======================================
                   SUPER ADMIN con carrera seleccionada
                   ====================================== --}}

                {{-- Título de panel según carrera --}}
                <div class="flex items-center justify-center gap-3 mb-4">
                    @if ($activeCareer->codigo === 'TUP')
                        <img src="{{ asset('image/tup.png') }}" alt="Logo TUP" class="w-10 h-10 object-contain">
                        <h1 class="text-2xl font-semibold text-black dark:text-white">
                            Panel Carrera TUP
                        </h1>
                    @elseif ($activeCareer->codigo === 'LPB')
                        <svg viewBox="0 0 1024 1024" class="w-10 h-10 text-violet-500 dark:text-violet-300"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                            <path d="M500.8 766.4c8 4.8 11.2 14.4 6.4 22.4s-14.4 11.2-22.4 6.4l-176-96c-8-4.8-11.2-14.4-6.4-22.4 4.8-8 14.4-11.2 22.4-6.4l176 96z" />
                            <path d="M652.8 460.8v-32c57.6 0 86.4 89.6 64 188.8-22.4 96-134.4 179.2-224 179.2v-32c75.2 0 174.4-73.6 192-155.2 19.2-81.6-3.2-148.8-32-148.8zM316.8 908.8h352c9.6 0 16 6.4 16 16s-6.4 16-16 16h-352c-9.6 0-16-6.4-16-16s6.4-16 16-16z" />
                            <path d="M652.8 94.4c46.4 27.2 62.4 84.8 35.2 131.2L528 502.4l-166.4-96 160-276.8c25.6-46.4 84.8-60.8 131.2-35.2z" />
                            <path d="M336 428.8c-9.6-6.4-14.4-17.6-11.2-24s16-9.6 27.2-3.2l184 107.2c9.6 6.4 14.4 17.6 11.2 24-4.8 8-16 9.6-27.2 3.2L336 428.8z" />
                            <path d="M358.4 443.2l139.2 80-24 41.6c-4.8 8-14.4 9.6-22.4 6.4l-110.4-64c-8-4.8-9.6-14.4-6.4-22.4l24-41.6z" />
                            <path d="M652.8 444.8m-48 0a48 48 0 1 0 96 0 48 48 0 1 0-96 0Z" />
                            <path d="M476.8 828.8v96h32v-96z" />
                            <path d="M492.8 780.8m-64 0a64 64 0 1 0 128 0 64 64 0 1 0-128 0Z" />
                        </svg>
                        <h1 class="text-2xl font-semibold text-black dark:text-white">
                             Carrera LAB
                        </h1>
                    @endif
                </div>

                {{-- Aviso (Estilo Archivo 1) --}}
                <div class="bg-sky-100 backdrop-blur-md border border-sky-400 shadow rounded-lg p-4 text-black dark:bg-sky-700 dark:text-white flex justify-between items-center">
                    <div>
                        <p class="text-sm font-semibold">
                            Modo Super Admin
                        </p>
                        <p class="text-xs">
                            Viendo el sistema como <strong>Bedel {{ $activeCareer->codigo }}</strong>
                            ({{ $activeCareer->nombre }}).
                        </p>
                    </div>
                    <a href="{{ route('panel', ['reset_career' => 1]) }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm text-black dark:text-white bg-purple-500 hover:bg-purple-700">
                        ← Volver al Panel
                    </a>
                </div>

                {{-- TARJETAS (Estructura de iconos Archivo 2 + Colores Archivo 1) --}}
                <div class="space-y-4">

                    {{-- Asistencia --}}
                    <a href="{{ route('asistencias.index') }}"
                        class="block bg-white backdrop-blur-md border border-blue-400 shadow rounded-lg p-6 hover:bg-blue-100 dark:bg-blue-700 dark:hover:bg-blue-500 transition">

                        <div class="flex items-center gap-3">
                            <svg viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" class="si-glyph si-glyph-person-checked w-8 h-8 text-blue-500 dark:text-blue-200" fill="currentColor">
                                <g transform="translate(0.000000, 1.000000)">
                                    <path d="M10.926,3.121 C10.926,4.801 9.617,8.012 8.004,8.012 C6.391,8.012 5.081,4.801 5.081,3.121 C5.081,1.441 6.391,0.081 8.004,0.081 C9.617,0.081 10.926,1.441 10.926,3.121 L10.926,3.121 Z"></path>
                                    <path d="M9.033,11.796 L11.426,9.375 L12.705,10.71 L14.348,9.048 C13.717,8.398 12.943,8.05 11.679,8.05 C10.825,9.206 7.99,9.503 7.99,9.503 C7.99,9.503 5.091,9.218 4.237,8.076 C0.144,8.076 0.02,13.986 0.02,13.986 L11.12,13.986 L9.033,11.796 L9.033,11.796 Z"></path>
                                    <path d="M15.094,9.801 L12.713,12.182 L11.297,10.765 L10.372,11.689 L12.714,14.03 L16.02,10.726 L15.094,9.801 Z"></path>
                                </g>
                            </svg>
                            <h2 class="text-xl font-semibold text-black dark:text-white">Asistencia</h2>
                        </div>

                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Gestionar cursadas, registros y reportes de {{ $activeCareer->codigo }}.
                        </p>
                    </a>

                    {{-- Alumnos --}}
                    <a href="{{ route('alumnos.index') }}"
                        class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 dark:bg-indigo-700 dark:hover:bg-indigo-500 transition">

                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-indigo-500 dark:text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4" stroke-width="1.8" />
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M6 21v-2a6 6 0 0112 0v2" />
                            </svg>
                            <h2 class="text-xl font-semibold text-black dark:text-white">Alumnos</h2>
                        </div>

                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Administrar legajos y datos personales de los alumnos.
                        </p>
                    </a>

                    {{-- Profesores --}}
                    <a href="{{ route('profesores.index') }}"
                        class="block bg-white backdrop-blur-md border border-violet-400 shadow rounded-lg p-6 hover:bg-violet-100 dark:bg-violet-700 dark:hover:bg-violet-500 transition">

                        <div class="flex items-center gap-3">
                            <svg viewBox="0 -0.5 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg" class="si-glyph si-glyph-person-man w-8 h-8 text-violet-500 dark:text-violet-200" fill="currentColor">
                                <g transform="translate(1.000000, 1.000000)">
                                    <path d="M8.918,12.338 L7.975,13.958 L7.033,12.338 L7.521,9.042 L8.429,9.042 L8.918,12.338 Z"></path>
                                    <path d="M5.534,14 C3.833,11.746 4.378,8.224 4.21,8 C0.123,7.999 0,14 0,14 L5.534,14 Z"></path>
                                    <path d="M10.5819092,14 L16,14 C15.999,14 16,7.96850586 12.319458,7.96850586 C12.156458,8.18850586 12.2849092,11.728 10.5819092,14 Z"></path>
                                    <path d="M8.00799561,7.94122333 C6.38999561,7.94122333 5,4.732 5,3.064 C5,1.394 6.38899561,0.0581054693 8.00799561,0.0581054693 C9.62799561,0.0581054693 11.0015869,1.39500007 11.0015869,3.06400007 C11.0015869,4.73200007 9.62799561,7.94122333 8.00799561,7.94122333 Z"></path>
                                </g>
                            </svg>
                            <h2 class="text-xl font-semibold text-black dark:text-white">Profesores</h2>
                        </div>

                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Gestión de docentes y asignaciones.
                        </p>
                    </a>

                    {{-- Estados Académicos --}}
                    <a href="{{ route('estado-academico.index') }}"
                        class="block bg-white backdrop-blur-md border border-purple-400 shadow rounded-lg p-6 hover:bg-purple-100 dark:bg-purple-700 dark:hover:bg-purple-500 transition">

                        <div class="flex items-center gap-3">
                            <svg class="w-8 h-8 text-purple-500 dark:text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <h2 class="text-xl font-semibold text-black dark:text-white">Estados Académicos</h2>
                        </div>

                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Vista general de situaciones académicas de los alumnos.
                        </p>
                    </a>

                    {{-- Auditoría (Botón extra si lo quieres también aquí) --}}
                    <a href="{{ route('superadmin.audit.index') }}"
                        class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 dark:bg-indigo-700 dark:hover:bg-indigo-500 transition">

                        <div class="flex items-center gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-500 dark:text-indigo-200" viewBox="0 0 1024 1024" fill="currentColor">
                                <path d="M296 250c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h384c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8H296zm184 144H296c-4.4 0-8 3.6-8 8v48c0 4.4 3.6 8 8 8h184c4.4 0 8-3.6 8-8v-48c0-4.4-3.6-8-8-8zm-48 458H208V148h560v320c0 4.4 3.6 8 8 8h56c4.4 0 8-3.6 8-8V108c0-17.7-14.3-32-32-32H168c-17.7 0-32 14.3-32 32v784c0 17.7 14.3 32 32 32h264c4.4 0 8-3.6 8-8v-56c0-4.4-3.6-8-8-8zm440-88H728v-36.6c46.3-13.8 80-56.6 80-107.4 0-61.9-50.1-112-112-112s-112 50.1-112 112c0 50.7 33.7 93.6 80 107.4V764H520c-8.8 0-16 7.2-16 16v152c0 8.8 7.2 16 16 16h352c8.8 0 16-7.2 16-16V780c0-8.8-7.2-16-16-16zM646 620c0-27.6 22.4-50 50-50s50 22.4 50 50-22.4 50-50 50-50-22.4-50-50zm180 266H566v-60h260v60z" />
                            </svg>
                            <h2 class="text-xl font-semibold text-black dark:text-white">Auditoría</h2>
                        </div>
                        <p class="mt-2 text-sm text-black dark:text-gray-100">
                            Ver el historial de acciones realizadas en el sistema.
                        </p>
                    </a>

                </div>
            @endif
        @else
            {{-- ======================================
               VISTA BEDEL (usuario común)
               ====================================== --}}

            @if (isset($activeCareer) && $activeCareer)
                <div class="flex items-center justify-center gap-3 mb-4">
                    @if ($activeCareer->codigo === 'TUP')
                        <img src="{{ asset('image/tup.png') }}" alt="Logo TUP" class="w-10 h-10 object-contain">
                        <h1 class="text-2xl font-semibold text-black dark:text-white">
                            Panel Carrera TUP
                        </h1>
                    @elseif ($activeCareer->codigo === 'LPB')
                        <svg viewBox="0 0 1024 1024" class="w-10 h-10 text-violet-500 dark:text-violet-300"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                            <path d="M500.8 766.4c8 4.8 11.2 14.4 6.4 22.4s-14.4 11.2-22.4 6.4l-176-96c-8-4.8-11.2-14.4-6.4-22.4 4.8-8 14.4-11.2 22.4-6.4l176 96z" />
                            <path d="M652.8 460.8v-32c57.6 0 86.4 89.6 64 188.8-22.4 96-134.4 179.2-224 179.2v-32c75.2 0 174.4-73.6 192-155.2 19.2-81.6-3.2-148.8-32-148.8zM316.8 908.8h352c9.6 0 16 6.4 16 16s-6.4 16-16 16h-352c-9.6 0-16-6.4-16-16s6.4-16 16-16z" />
                            <path d="M652.8 94.4c46.4 27.2 62.4 84.8 35.2 131.2L528 502.4l-166.4-96 160-276.8c25.6-46.4 84.8-60.8 131.2-35.2z" />
                            <path d="M336 428.8c-9.6-6.4-14.4-17.6-11.2-24s16-9.6 27.2-3.2l184 107.2c9.6 6.4 14.4 17.6 11.2 24-4.8 8-16 9.6-27.2 3.2L336 428.8z" />
                            <path d="M358.4 443.2l139.2 80-24 41.6c-4.8 8-14.4 9.6-22.4 6.4l-110.4-64c-8-4.8-9.6-14.4-6.4-22.4l24-41.6z" />
                            <path d="M652.8 444.8m-48 0a48 48 0 1 0 96 0 48 48 0 1 0-96 0Z" />
                            <path d="M476.8 828.8v96h32v-96z" />
                            <path d="M492.8 780.8m-64 0a64 64 0 1 0 128 0 64 64 0 1 0-128 0Z" />
                        </svg>
                        <h1 class="text-2xl font-semibold text-black dark:text-white">
                             Carrera LAB
                        </h1>
                    @endif
                </div>
            @endif

            <div class="space-y-4">

                {{-- Asistencia --}}
                <a href="{{ route('asistencias.index') }}"
                    class="block bg-white backdrop-blur-md border border-blue-400 shadow rounded-lg p-6 hover:bg-blue-100 dark:bg-blue-700 dark:hover:bg-blue-500 transition">

                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-blue-500 dark:text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 12h14M12 5v14" />
                        </svg>
                        <h2 class="text-xl font-semibold text-black dark:text-white">Asistencia</h2>
                    </div>

                    <p class="mt-2 text-sm text-black dark:text-gray-100">
                        Gestionar cursadas, registros y reportes.
                    </p>
                </a>

                {{-- Alumnos --}}
                <a href="{{ route('alumnos.index') }}"
                    class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 dark:bg-indigo-700 dark:hover:bg-indigo-500 transition">

                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-indigo-500 dark:text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="7" r="4" stroke-width="1.8" />
                            <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M6 21v-2a6 6 0 0112 0v2" />
                        </svg>
                        <h2 class="text-xl font-semibold text-black dark:text-white">Alumnos</h2>
                    </div>

                    <p class="mt-2 text-sm text-black dark:text-gray-100">
                        Administrar legajos y datos personales.
                    </p>
                </a>

                {{-- Profesores --}}
                <a href="{{ route('profesores.index') }}"
                    class="block bg-white backdrop-blur-md border border-violet-400 shadow rounded-lg p-6 hover:bg-violet-100 dark:bg-violet-700 dark:hover:bg-violet-500 transition">

                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-violet-500 dark:text-violet-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M12 14s-4 1-4 4v2h8v-2c0-3-4-4-4-4zm0-2a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                        <h2 class="text-xl font-semibold text-black dark:text-white">Profesores</h2>
                    </div>

                    <p class="mt-2 text-sm text-black dark:text-gray-100">
                        Gestión de docentes y asignaciones.
                    </p>
                </a>

                {{-- Estados Académicos --}}
                <a href="{{ route('estado-academico.index') }}"
                    class="block bg-white backdrop-blur-md border border-purple-400 shadow rounded-lg p-6 hover:bg-purple-100 dark:bg-purple-700 dark:hover:bg-purple-500 transition">

                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-purple-500 dark:text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <h2 class="text-xl font-semibold text-black dark:text-white">Estados Académicos</h2>
                    </div>

                    <p class="mt-2 text-sm text-black dark:text-gray-100">
                        Vista general de situaciones académicas.
                    </p>
                </a>

            </div>

        @endif

    </div>

</x-app-interno-layout>