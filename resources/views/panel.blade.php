{{-- resources/views/panel.blade.php --}}
<x-app-interno-layout>
    <x-slot name="header">
        Panel Principal
    </x-slot>

    <div class="space-y-6">

        @if($esSuperAdmin ?? false)
            {{-- ===========================
                 VISTA SUPER ADMIN
               =========================== --}}

            @if(!$activeCareer)
                {{-- Caso 1: Super Admin SIN carrera seleccionada
                     → SOLO tres tarjetones: Gestión de usuarios, TUP, LPB --}}

                <div class="space-y-4">
                    {{-- Gestión de usuarios --}}
                    <a href="{{ route('superadmin.bedeles.index') }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Gestión de usuarios
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Crear y administrar usuarios Bedel y sus carreras asignadas.
                        </p>
                    </a>

                    {{-- TUP --}}
                    <a href="{{ route('panel', ['career' => 'TUP']) }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            TUP
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Ingresar al sistema como administrativo/bedel de la carrera
                            Tecnicatura Universitaria en Programación (TUP).
                        </p>
                    </a>

                    {{-- LPB --}}
                    <a href="{{ route('panel', ['career' => 'LPB']) }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            LPB
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Ingresar al sistema como administrativo/bedel de la carrera
                            Licenciatura en Producción de Bioimágenes (LPB).
                        </p>
                    </a>
                </div>

            @else
                {{-- Caso 2: Super Admin CON carrera seleccionada
                     → Vista igual a un Bedel de esa carrera
                     (OJO: se eliminó el botón "Volver a selección de carrera"
                      para que solo se use el botón Atrás del layout) --}}

                {{-- Aviso de modo super admin, opcional --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                        Modo Super Admin
                    </p>
                    <p class="text-xs text-gray-600 dark:text-gray-300">
                        Viendo el sistema como <span class="font-semibold">Bedel {{ $activeCareer->codigo }}</span>
                        ({{ $activeCareer->nombre }}).
                    </p>
                </div>

                {{-- Tarjetas de módulos (como Bedel) --}}
                <div class="space-y-4">
                    {{-- Asistencia --}}
                    <a href="{{ route('asistencias.index') }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Asistencia
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Gestionar cursadas, registros y reportes de {{ $activeCareer->codigo }}.
                        </p>
                    </a>

                    {{-- Alumnos --}}
                    <a href="{{ route('alumnos.index') }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Alumnos
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Administrar legajos y datos personales de los alumnos de {{ $activeCareer->codigo }}.
                        </p>
                    </a>

                    {{-- Profesores --}}
                    <a href="{{ route('profesores.index') }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Profesores
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Gestión de docentes y asignaciones de cátedras/comisiones.
                        </p>
                    </a>

                    {{-- Estados Académicos --}}
                    <a href="{{ route('estado-academico.index') }}"
                       class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                            Estados Académicos
                        </h2>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                            Vista general de situaciones académicas de los alumnos.
                        </p>
                    </a>
                </div>
            @endif

        @else
            {{-- ===========================
                 VISTA BEDEL (usuario normal)
                 Siempre ve los módulos
               =========================== --}}
            <div class="space-y-4">
                {{-- Asistencia --}}
                <a href="{{ route('asistencias.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Asistencia
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Gestionar cursadas, registros y reportes.
                    </p>
                </a>

                {{-- Alumnos --}}
                <a href="{{ route('alumnos.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Alumnos
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Administrar legajos y datos personales.
                    </p>
                </a>

                {{-- Profesores --}}
                <a href="{{ route('profesores.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Profesores
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Gestión de docentes y asignaciones.
                    </p>
                </a>

                {{-- Estados Académicos --}}
                <a href="{{ route('estado-academico.index') }}"
                   class="block bg-white dark:bg-gray-800 shadow rounded-lg p-6 hover:shadow-md transition">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Estados Académicos
                    </h2>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        Vista general de situaciones académicas.
                    </p>
                </a>
            </div>
        @endif

    </div>
</x-app-interno-layout>
