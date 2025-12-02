{{-- resources/views/panel.blade.php --}}
<x-app-interno-layout>

    {{-- VIDEO DE FONDO --}}
    <video autoplay loop muted playsinline class="fixed inset-0 w-full h-full object-cover -z-10">
        <source src="{{ asset('image/fondo_animado.webm') }}" type="video/webm">
    </video>

    {{-- CONTENIDO DEL PANEL --}}
    <div class="space-y-6 relative z-10">

        <x-slot name="header">
            Panel Principal
        </x-slot>

        @if($esSuperAdmin ?? false)
            {{-- ======================================
                 VISTA SUPER ADMIN
                ====================================== --}}

            @if(!$activeCareer)
                {{-- Super Admin SIN carrera seleccionada --}}

                <div class="space-y-4">

                    {{-- Gestión de usuarios --}}
                    <a href="{{ route('superadmin.bedeles.index') }}"
                       class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                        <h2 class="text-xl font-semibold text-white">Gestión de usuarios</h2>
                        <p class="mt-2 text-sm text-gray-100">
                            Crear y administrar usuarios Bedel y sus carreras asignadas.
                        </p>
                    </a>

                    {{-- TUP --}}
                    <a href="{{ route('panel', ['career' => 'TUP']) }}"
                       class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                        <h2 class="text-xl font-semibold text-white">TUP</h2>
                        <p class="mt-2 text-sm text-gray-100">
                            Ingresar al sistema como administrativo/bedel de la carrera TUP.
                        </p>
                    </a>

                    {{-- LPB --}}
                    <a href="{{ route('panel', ['career' => 'LPB']) }}"
                       class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                        <h2 class="text-xl font-semibold text-white">LPB</h2>
                        <p class="mt-2 text-sm text-gray-100">
                            Ingresar al sistema como administrativo/bedel de la carrera LPB.
                        </p>
                    </a>

                </div>

            @else
                {{-- Super Admin CON carrera seleccionada (vista tipo Bedel) --}}

                {{-- Aviso de modo Super Admin --}}
                <div class="bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-4 text-white">
                    <p class="text-sm font-semibold">
                        Modo Super Admin
                    </p>
                    <p class="text-xs">
                        Viendo el sistema como <strong>Bedel {{ $activeCareer->codigo }}</strong>
                        ({{ $activeCareer->nombre }}).
                    </p>
                </div>

                {{-- Tarjetas de módulos --}}
                <div class="space-y-4">

                    {{-- Asistencia --}}
                    <a href="{{ route('asistencias.index') }}"
                       class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                        <h2 class="text-xl font-semibold text-white">Asistencia</h2>
                        <p class="mt-2 text-sm text-gray-100">
                            Gestionar cursadas, registros y reportes de {{ $activeCareer->codigo }}.
                        </p>
                    </a>

                    {{-- Alumnos --}}
                    <a href="{{ route('alumnos.index') }}"
                       class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                        <h2 class="text-xl font-semibold text-white">Alumnos</h2>
                        <p class="mt-2 text-sm text-gray-100">
                            Administrar legajos y datos personales de los alumnos.
                        </p>
                    </a>

                    {{-- Profesores --}}
                    <a href="{{ route('profesores.index') }}"
                       class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                        <h2 class="text-xl font-semibold text-white">Profesores</h2>
                        <p class="mt-2 text-sm text-gray-100">
                            Gestión de docentes y asignaciones.
                        </p>
                    </a>

                    {{-- Estados Académicos --}}
                    <a href="{{ route('estado-academico.index') }}"
                       class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                        <h2 class="text-xl font-semibold text-white">Estados Académicos</h2>
                        <p class="mt-2 text-sm text-gray-100">
                            Vista general de situaciones académicas de los alumnos.
                        </p>
                    </a>

                </div>
            @endif

        @else
            {{-- ======================================
                 VISTA BEDEL (usuario común)
                ====================================== --}}

            <div class="space-y-4">

                {{-- Asistencia --}}
                <a href="{{ route('asistencias.index') }}"
                   class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                    <h2 class="text-xl font-semibold text-white">Asistencia</h2>
                    <p class="mt-2 text-sm text-gray-100">
                        Gestionar cursadas, registros y reportes.
                    </p>
                </a>

                {{-- Alumnos --}}
                <a href="{{ route('alumnos.index') }}"
                   class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                    <h2 class="text-xl font-semibold text-white">Alumnos</h2>
                    <p class="mt-2 text-sm text-gray-100">
                        Administrar legajos y datos personales.
                    </p>
                </a>

                {{-- Profesores --}}
                <a href="{{ route('profesores.index') }}"
                   class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                    <h2 class="text-xl font-semibold text-white">Profesores</h2>
                    <p class="mt-2 text-sm text-gray-100">
                        Gestión de docentes y asignaciones.
                    </p>
                </a>

                {{-- Estados Académicos --}}
                <a href="{{ route('estado-academico.index') }}"
                   class="block bg-white/20 backdrop-blur-md border border-white/30 shadow rounded-lg p-6 hover:bg-white/30 transition">
                    <h2 class="text-xl font-semibold text-white">Estados Académicos</h2>
                    <p class="mt-2 text-sm text-gray-100">
                        Vista general de situaciones académicas.
                    </p>
                </a>

            </div>
        @endif

    </div>

</x-app-interno-layout>
