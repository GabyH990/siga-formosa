<x-app-interno-layout>
    @section('title', 'Estado Académico – SIGA')
    
    <x-slot name="header">
        Estado Académico de {{ $student->apellido }}, {{ $student->nombre }}
    </x-slot>

    <div class="space-y-6">
        
        {{-- ==========================================
             ENCABEZADO DEL ALUMNO
             ========================================== --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">
                            {{ $student->apellido }}, {{ $student->nombre }}
                        </h2>

                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            Legajo: <span class="font-semibold">{{ $student->legajo }}</span>

                            @if($student->career)
                                <span class="mx-2">|</span> Carrera:
                                <span class="font-semibold">
                                    {{ $student->career->codigo }} - {{ $student->career->nombre }}
                                </span>
                            @endif

                            @if($student->cohorte)
                                <span class="mx-2">|</span> Cohorte:
                                <span class="font-semibold">{{ $student->cohorte }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- BOTÓN PARA CARGAR / EDITAR ESTADO --}}
                    <div class="flex justify-end">
                        <a href="{{ route('alumnos.estado.editar', $student->id) }}"
                           id="btn-estado-editar"
                           class="inline-flex items-center px-4 py-2 rounded-md text-sm font-semibold
                                  bg-indigo-500 hover:bg-indigo-600 text-white shadow transition">
                            Cargar o editar estado
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==========================================
             SECCIÓN: CURSANDO
             ========================================== --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b-2 border-indigo-200 dark:border-indigo-800 pb-1">
                        Cursando
                    </h3>
                </div>

                @if($cursando->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                        El alumno no tiene materias en cursada actualmente.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($cursando as $state)
                            <div class="border border-violet-100 dark:border-violet-800 rounded-lg p-3 hover:bg-violet-50 dark:hover:bg-violet-900/50 transition">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $state->subject->nombre ?? 'Materia sin nombre' }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            Comisión:
                                            @if($state->commission)
                                                <span class="font-medium">{{ $state->commission->nombre }}</span>
                                                @if($state->commission->anio)
                                                    · Año: {{ $state->commission->anio }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </p>
                                    </div>

                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200">
                                        Cursando
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ==========================================
             SECCIÓN: REGULARES
             ========================================== --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b-2 border-cyan-200 dark:border-cyan-800 pb-1">
                        Regulares
                    </h3>
                </div>

                @if($regulares->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                        El alumno no tiene materias regularizadas pendientes de final.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($regulares as $state)
                            @php
                                $finalPendiente = is_null($state->tipo_aprobacion);
                            @endphp

                            <div class="border border-violet-100 dark:border-violet-800 rounded-lg p-3 hover:bg-violet-50 dark:hover:bg-violet-900/50 transition">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $state->subject->nombre ?? 'Materia sin nombre' }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400">
                                            Regularizado en:
                                            <span class="font-semibold">
                                                {{ $state->anio_regularizacion ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200">
                                            Regular
                                        </span>

                                        @if($finalPendiente)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-200">
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

        {{-- ==========================================
             SECCIÓN: APROBADAS
             ========================================== --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white border-b-2 border-green-200 dark:border-green-800 pb-1">
                        Aprobadas
                    </h3>
                </div>

                @if($aprobadas->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                        El alumno aún no tiene materias aprobadas.
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach($aprobadas as $state)
                            <div class="border border-violet-100 dark:border-violet-800 rounded-lg p-3 hover:bg-violet-50 dark:hover:bg-violet-900/50 transition">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $state->subject->nombre ?? 'Materia sin nombre' }}
                                        </p>

                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                            @if($state->tipo_aprobacion === 'directa')
                                                Aprobación directa
                                            @elseif($state->tipo_aprobacion === 'final')
                                                Examen final
                                            @else
                                                Aprobada
                                            @endif

                                            @if($state->nota_final)
                                                · Nota: <span class="font-bold text-gray-800 dark:text-gray-200">{{ $state->nota_final }}</span>
                                            @endif
                                        </p>

                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-0.5">
                                            @if($state->libro) Libro: {{ $state->libro }} @endif
                                            @if($state->acta) | Folio: {{ $state->acta }} @endif
                                            @if($state->tomo) | Tomo: {{ $state->tomo }} @endif
                                        </p>
                                    </div>

                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200">
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

    {{-- Botón de cierre/cancelar --}}
    <div class="flex justify-center mt-8">
        <a href="{{ route('alumnos.index') }}"
           class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded shadow transition">
            ← Volver a Módulo Alumnos
        </a>
    </div>

    {{-- Script de confirmación con SweetAlert2 --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const btnEditarEstado = document.getElementById('btn-estado-editar');
                
                if (!btnEditarEstado) return;

                btnEditarEstado.addEventListener('click', (e) => {
                    e.preventDefault();

                    const url = btnEditarEstado.getAttribute('href') || '#';
                    const alumno = @json($student->apellido . ', ' . $student->nombre);

                    Swal.fire({
                        title: '¿Cargar o editar estado académico?',
                        html: `
                            <div style="text-align:left">
                                <p>Vas a ir a la pantalla para modificar el estado académico de:</p>
                                <p class="mt-2 text-center text-lg font-semibold text-purple-600 dark:text-purple-300">
                                    ${alumno}
                                </p>
                            </div>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonColor: '#6366f1', // Indigo-500
                        cancelButtonColor: '#9ca3af',  // Gray-400
                        confirmButtonText: 'Sí, continuar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed && url !== '#') {
                            window.location.href = url;
                        }
                    });
                });
            });
        </script>
    @endpush

</x-app-interno-layout>