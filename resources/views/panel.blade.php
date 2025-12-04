{{-- resources/views/panel.blade.php --}}
<x-app-interno-layout>

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
                       class="block bg-white backdrop-blur-md border border-blue-600 shadow rounded-lg p-6 hover:bg-blue-100 transition">
                        <h2 class="text-xl font-semibold text-black">Gestión de usuarios</h2>
                        <p class="mt-2 text-sm text-black">
                            Crear y administrar usuarios Bedel y sus carreras asignadas.
                        </p>
                    </a>

                    {{-- TUP --}}
                    <a href="{{ route('panel', ['career' => 'TUP']) }}"
                       class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 transition">
                        <h2 class="text-xl font-semibold text-black">TUP</h2>
                        <p class="mt-2 text-sm text-black">
                            Ingresar al sistema como administrativo/bedel de la carrera TUP.
                        </p>
                    </a>

                    {{-- LPB --}}
                    <a href="{{ route('panel', ['career' => 'LPB']) }}"
                       class="block bg-white backdrop-blur-md border border-violet-400 shadow rounded-lg p-6 hover:bg-violet-100 transition">
                        <h2 class="text-xl font-semibold text-black">LPB</h2>
                        <p class="mt-2 text-sm text-black">
                            Ingresar al sistema como administrativo/bedel de la carrera LPB.
                        </p>
                    </a>

                </div>
        

            @else
                {{-- Super Admin CON carrera seleccionada (vista tipo Bedel) --}}

                {{-- Aviso de modo Super Admin --}}
                <div class="bg-sky-100 backdrop-blur-md border border-sky-400 shadow rounded-lg p-4 text-black">
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
                       class="block bg-white backdrop-blur-md border border-blue-400 shadow rounded-lg p-6 hover:bg-blue-100 transition">
                        <h2 class="text-xl font-semibold text-black">Asistencia</h2>
                        <p class="mt-2 text-sm text-black">
                            Gestionar cursadas, registros y reportes de {{ $activeCareer->codigo }}.
                        </p>
                    </a>

                    {{-- Alumnos --}}
                    <a href="{{ route('alumnos.index') }}"
                       class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 transition">
                        <h2 class="text-xl font-semibold text-black">Alumnos</h2>
                        <p class="mt-2 text-sm text-black">
                            Administrar legajos y datos personales de los alumnos.
                        </p>
                    </a>

                    {{-- Profesores --}}
                    <a href="{{ route('profesores.index') }}"
                       class="block bg-white backdrop-blur-md border border-violet-400 shadow rounded-lg p-6 hover:bg-violet-100 transition">
                        <h2 class="text-xl font-semibold text-black">Profesores</h2>
                        <p class="mt-2 text-sm text-black">
                            Gestión de docentes y asignaciones.
                        </p>
                    </a>

                    {{-- Estados Académicos --}}
                    <a href="{{ route('estado-academico.index') }}"
                       class="block bg-white backdrop-blur-md border border-purple-400 shadow rounded-lg p-6 hover:bg-purple-100 transition">
                        <h2 class="text-xl font-semibold text-black">Estados Académicos</h2>
                        <p class="mt-2 text-sm text-blackblack">
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
                   class="block bg-white backdrop-blur-md border border-blue-400 shadow rounded-lg p-6 hover:bg-blue-100 transition">
                    <h2 class="text-xl font-semibold text-black">Asistencia</h2>
                    <p class="mt-2 text-sm text-black">
                        Gestionar cursadas, registros y reportes.
                    </p>
                </a>

                {{-- Alumnos --}}
                <a href="{{ route('alumnos.index') }}"
                   class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 transition">
                    <h2 class="text-xl font-semibold text-black">Alumnos</h2>
                    <p class="mt-2 text-sm text-black">
                        Administrar legajos y datos personales.
                    </p>
                </a>

                {{-- Profesores --}}
                <a href="{{ route('profesores.index') }}"
                   class="block bg-white backdrop-blur-md border border-violet-400 shadow rounded-lg p-6 hover:bg-violet-100 transition">
                    <h2 class="text-xl font-semibold black">Profesores</h2>
                    <p class="mt-2 text-sm text-black">
                        Gestión de docentes y asignaciones.
                    </p>
                </a>

                {{-- Estados Académicos --}}
                <a href="{{ route('estado-academico.index') }}"
                   class="block bg-white backdrop-blur-md border border-purple-400 shadow rounded-lg p-6 hover:bg-purple-100 transition">
                    <h2 class="text-xl font-semibold text-black">Estados Académicos</h2>
                    <p class="mt-2 text-sm text-black">
                        Vista general de situaciones académicas.
                    </p>
                </a>

            </div>
        @endif

    </div>

</x-app-interno-layout>
