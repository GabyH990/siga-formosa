<x-app-interno-layout>
    <x-slot name="header">
        Cargar / editar estado de {{ $student->apellido }}, {{ $student->nombre }}
    </x-slot>

    <div class="space-y-6" x-data="{ estado: 'Regular', tipoAprobacion: '' }">
        {{-- Datos del alumno --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-black dark:text-white mb-1">
                    {{ $student->apellido }}, {{ $student->nombre }}
                </h2>
                <p class="text-sm text-black dark:text-white">
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
        </div>

        {{-- Formulario de carga / edición --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-black dark:text-white mb-4">
                    Cargar / editar estado de una materia
                </h3>

                {{-- 👇 CAMBIO: agrego id al form --}}
                <form id="form-estado-academico"
                      method="POST"
                      action="{{ route('alumnos.estado.guardar', $student->id) }}"
                      class="space-y-4">
                    @csrf

                    {{-- Materia --}}
                    <div>
                        <label class="block text-sm font-medium text-black dark:text-white">
                            Materia
                        </label>
                        <select name="subject_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="">Seleccione materia</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->nombre }}</option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Comisión --}}
                    <div>
                        <label class="block text-sm font-medium text-black dark:text-white">
                            Comisión (opcional)
                        </label>
                        <select name="commission_nombre"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Sin comisión específica</option>
                            @foreach($commissionNames as $nombre)
                                <option value="{{ $nombre }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                        @error('commission_nombre')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label class="block text-sm font-medium text-black dark:text-white">
                            Estado
                        </label>
                        <select name="estado"
                                x-model="estado"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            <option value="Cursando">Cursando</option>
                            <option value="Regular">Regular</option>
                            <option value="Aprobada">Aprobada</option>
                        </select>
                        @error('estado')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Año de regularización (solo Regular) --}}
                    <div x-show="estado === 'Regular'" x-cloak>
                        <label class="block text-sm font-medium text-black dark:text-white">
                            Año de regularización
                        </label>
                        <input type="number" name="anio_regularizacion"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('anio_regularizacion')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Datos de aprobación (solo Aprobada) --}}
                    <div x-show="estado === 'Aprobada'" x-cloak class="border-t border-white dark:border-white pt-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">
                            Datos de aprobación
                        </h4>

                        {{-- Tipo de aprobación --}}
                        <div class="mb-3">
                            <label class="block text-sm font-medium text-black dark:text-white">
                                Tipo de aprobación
                            </label>
                            <select name="tipo_aprobacion"
                                    x-model="tipoAprobacion"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                           focus:border-blue-500 focus:ring-blue-500
                                           dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Seleccione...</option>
                                <option value="directa">Aprobación directa</option>
                                <option value="final">Examen final</option>
                            </select>
                            @error('tipo_aprobacion')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Libro / Tomo / Folio / Nota --}}
                        <div x-show="tipoAprobacion !== ''" x-cloak class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-sm font-medium text-black dark:text-white">
                                    Libro
                                </label>
                                <input type="text" name="libro"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                              focus:border-blue-500 focus:ring-blue-500
                                              dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-black dark:text-white">
                                    Tomo
                                </label>
                                <input type="text" name="tomo"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                              focus:border-blue-500 focus:ring-blue-500
                                              dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-black dark:text-white">
                                    Folio
                                </label>
                                {{-- sigue usando name="acta" para coincidir con la BD --}}
                                <input type="text" name="acta"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                              focus:border-blue-500 focus:ring-blue-500
                                              dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-black dark:text-white">
                                    Nota final
                                </label>
                                <input type="number" step="0.01" name="nota_final"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                              focus:border-blue-500 focus:ring-blue-500
                                              dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            </div>
                        </div>
                    </div>

                    {{-- Observaciones --}}
                    <div>
                        <label class="block text-sm font-medium text-black dark:text-white">
                            Observaciones (opcional)
                        </label>
                        <textarea name="observaciones" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700
                                         focus:border-blue-500 focus:ring-blue-500
                                         dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                        @error('observaciones')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('alumnos.estado', $student->id) }}"
                           class="px-4 py-2 rounded-md text-sm font-semibold border
                                  border-gray-300 text-gray-700 bg-white hover:bg-gray-50
                                  dark:border-gray-600 dark:text-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700">
                            Cancelar
                        </a>

                        {{-- 👇 CAMBIO: type="button" + id --}}
                        <button type="button"
                                id="btn-guardar-estado"
                                class="px-4 py-2 rounded-md text-sm font-semibold text-white
                                       bg-blue-600 hover:bg-blue-700">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Resumen de estados ya cargados --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-black dark:text-white mb-4">
                    Estados cargados para este alumno
                </h3>

                @if($estados->isEmpty())
                    <p class="text-sm text-black dark:text-white">
                        Aún no se cargaron estados académicos para este alumno.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-xs md:text-sm">
                            <thead class="bg-white/20 dark:bg:white/20">
                                <tr>
                                    <th class="px-4 py-2 text-left text-black dark:text-white">Materia</th>
                                    <th class="px-4 py-2 text-left text-black dark:text-white">Comisión</th>
                                    <th class="px-4 py-2 text-left text-black dark:text-white">Estado</th>
                                    <th class="px-4 py-2 text-left text-black dark:text-white">Tipo aprobación</th>
                                    <th class="px-4 py-2 text-left text-black dark:text-white">Nota</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-white/20 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($estados as $estado)
                                    <tr>
                                        <td class="px-4 py-2">
                                            {{ $estado->subject->nombre ?? 'Materia' }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $estado->commission->nombre ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $estado->estado }}
                                        </td>
                                        <td class="px-4 py-2">
                                            @if($estado->tipo_aprobacion === 'directa')
                                                Aprobación directa
                                            @elseif($estado->tipo_aprobacion === 'final')
                                                Examen final
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-2">
                                            {{ $estado->nota_final ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- SweetAlert2 + confirmación de guardado --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-estado-academico');
            const btnGuardar = document.getElementById('btn-guardar-estado');

            if (!form || !btnGuardar) return;

            btnGuardar.addEventListener('click', (e) => {
                e.preventDefault();

                // 1) Validación HTML5 (required, formatos, etc.)
                if (typeof form.reportValidity === 'function') {
                    if (!form.reportValidity()) {
                        return;
                    }
                } else if (!form.checkValidity()) {
                    return;
                }

                // 2) Preparo datos básicos para el resumen
                const subjectSelect = form.subject_id;
                const materiaTexto = (subjectSelect && subjectSelect.value)
                    ? subjectSelect.options[subjectSelect.selectedIndex].text
                    : '';

                const estado = form.estado ? form.estado.value : '';
                const tipoAprobSelect = form.tipo_aprobacion;
                const tipoAprob = tipoAprobSelect ? tipoAprobSelect.value : '';

                Swal.fire({
                    title: '¿Guardar estado académico?',
                    html: `
                        <div style="text-align:left">
                            <p><strong>Materia:</strong> ${materiaTexto || '—'}</p>
                            <p><strong>Estado:</strong> ${estado || '—'}</p>
                            ${estado === 'Aprobada'
                                ? `<p><strong>Tipo de aprobación:</strong> ${tipoAprob || '—'}</p>`
                                : ''
                            }
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // acá entra al controlador y corre las validaciones de Laravel
                    }
                });
            });
        });
    </script>
</x-app-interno-layout>
