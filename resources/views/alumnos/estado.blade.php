{{-- resources/views/alumnos/estado.blade.php --}}
<x-app-interno-layout>
    <x-slot name="header">
        Estado Académico de {{ $student->apellido }}, {{ $student->nombre }}
    </x-slot>

    
    <div class="space-y-6">
        {{-- Encabezado del alumno --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h2 class="text-xl font-semibold text-black dark:text-white mb-1">
                        {{ $student->apellido }}, {{ $student->nombre }}
                    </h2>

                    <p class="text-sm text-black dark:text-gray-300">
                        Legajo:
                        <span class="font-semibold">{{ $student->legajo }}</span>

                        @if($student->career)
                            · Carrera:
                            <span class="font-semibold">
                                {{ $student->career->codigo }} - {{ $student->career->nombre }}
                            </span>
                        @endif

                        @if($student->cohorte)
                            · Cohorte:
                            <span class="font-semibold">{{ $student->cohorte }}</span>
                        @endif
                    </p>
                </div>

                {{-- BOTÓN PARA CARGAR / EDITAR ESTADO --}}
                <div class="flex justify-end">
                    <a href="{{ route('alumnos.estado.editar', $student->id) }}"
                       class="inline-flex items-center px-4 py-2 rounded-md text-sm font-semibold
                              bg-blue-600 hover:bg-blue-700 text-white">
                        Cargar o editar estado
                    </a>
                </div>
            </div>
        </div>
        </div>

        {{-- Sección: Cursando --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-black dark:text-white">
                    Cursando
                </h3>
            </div>

            @if($cursando->isEmpty())
                <p class="text-sm text-black dark:text-gray-400">
                    El alumno no tiene materias en cursada actualmente.
                </p>
            @else
                <div class="space-y-3">
                    @foreach($cursando as $state)
                        <div class="border border-blue-300 dark:border-gray-700 rounded-lg p-3">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-black dark:text-white">
                                        {{ $state->subject->nombre ?? 'Materia sin nombre' }}
                                    </p>
                                    <p class="text-xs text-black dark:text-white">
                                        Comisión:
                                        @if($state->commission)
                                            {{ $state->commission->nombre }}
                                            @if($state->commission->anio)
                                                · Año: {{ $state->commission->anio }}
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>

                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                           bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100">
                                    Cursando
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        </div>

        {{-- Sección: Regulares (con "final pendiente" derivado) --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-black dark:text-white">
                    Regulares
                </h3>
            </div>

            @if($regulares->isEmpty())
                <p class="text-sm text-black dark:text-white">
                    El alumno no tiene materias regularizadas.
                </p>
            @else
                <div class="space-y-3">
                    @foreach($regulares as $state)
                        @php
                            // "Final pendiente" = Regular sin tipo_aprobacion (se deriva, no se carga)
                            $finalPendiente = is_null($state->tipo_aprobacion);
                        @endphp

                        <div class="border border-blue-300 dark:border-gray-700 rounded-lg p-3">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-black dark:text-white">
                                        {{ $state->subject->nombre ?? 'Materia sin nombre' }}
                                    </p>
                                    <p class="text-xs text-black dark:text-white">
                                        Regular en
                                        <span class="font-semibold">
                                            {{ $state->anio_regularizacion ?? 'año no especificado' }}
                                        </span>
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                               bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-100">
                                        Regular
                                    </span>

                                    @if($finalPendiente)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                                   bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-100">
                                            Final pendiente
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        </div>

        {{-- Sección: Aprobadas --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-black dark:text-white">
                    Aprobadas
                </h3>
            </div>

            @if($aprobadas->isEmpty())
                <p class="text-sm text-black dark:text-white">
                    El alumno aún no tiene materias aprobadas.
                </p>
            @else
                <div class="space-y-3">
                    @foreach($aprobadas as $state)
                        <div class="border border-blue-300 dark:border-gray-700 rounded-lg p-3">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                <div>
                                    <p class="text-sm font-semibold text-black dark:text-white">
                                        {{ $state->subject->nombre ?? 'Materia sin nombre' }}
                                    </p>

                                    <p class="text-xs text-black dark:text-white mt-1">
                                        @if($state->tipo_aprobacion === 'directa')
                                            Aprobación directa
                                        @elseif($state->tipo_aprobacion === 'final')
                                            Examen final
                                        @else
                                            Tipo de aprobación no especificado
                                        @endif

                                        @if($state->nota_final)
                                            · Nota:
                                            <span class="font-semibold">{{ $state->nota_final }}</span>
                                        @endif
                                    </p>

                                    <p class="text-xs text-black dark:text-white mt-1">
                                        @if($state->libro)
                                            Libro: <span class="font-semibold">{{ $state->libro }}</span> ·
                                        @endif
                                        @if($state->acta)
                                            Folio: <span class="font-semibold">{{ $state->acta }}</span> ·
                                        @endif
                                        @if($state->tomo)
                                            Tomo: <span class="font-semibold">{{ $state->tomo }}</span>
                                        @endif
                                    </p>
                                </div>

                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                           bg-blue-900 text-white">
                                    Aprobada
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        </div>
    </div>

    <!-- Botón de cierre/cancelar -->
<div class="flex justify-center mt-6">
    <a href="{{ route('alumnos.index') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
        Cerrar
    </a>
</div>

</x-app-interno-layout>
