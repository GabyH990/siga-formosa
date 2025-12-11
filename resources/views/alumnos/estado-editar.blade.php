<x-app-interno-layout>
    @section('title', 'Cargar o Editar Estado Académico – SIGA')

    <x-slot name="header">
        Cargar / editar estado de {{ $student->apellido }}, {{ $student->nombre }}
    </x-slot>

    <div class="space-y-6" x-data="{ estado: 'Regular', tipoAprobacion: '' }">

        {{-- ==========================
        DATOS DEL ALUMNO
        ========================== --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
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
        </div>

        {{-- ==========================
        FORMULARIO DE CARGA
        ========================== --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Cargar / editar estado de una materia
                </h3>

                {{-- Formulario con ID para el script --}}
                <form id="form-estado-academico" method="POST"
                    action="{{ route('alumnos.estado.guardar', $student->id) }}" class="space-y-4">
                    @csrf

                    {{-- Materia --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                            Materia
                        </label>
                        <select name="subject_id" id="subject_id" required class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       bg-white text-gray-700
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            <option value="">Seleccione materia</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->nombre }}</option>
                            @endforeach
                        </select>
                        @error('subject_id') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Comisión --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                            Comisión (opcional)
                        </label>
                        <select name="commission_nombre" class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       bg-white text-gray-700
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            <option value="">Sin comisión específica</option>
                            @foreach($commissionNames as $nombre)
                                <option value="{{ $nombre }}">{{ $nombre }}</option>
                            @endforeach
                        </select>
                        @error('commission_nombre') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                            Estado
                        </label>
                        <select name="estado" id="estado" x-model="estado" required class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       bg-white text-gray-700
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            <option value="Cursando">Cursando</option>
                            <option value="Regular">Regular</option>
                            <option value="Aprobada">Aprobada</option>
                        </select>
                        @error('estado') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Año de regularización (solo Regular) --}}
                    <div x-show="estado === 'Regular'" x-cloak>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                            Año de regularización
                        </label>
                        <input type="number" name="anio_regularizacion" class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                      bg-white text-gray-700
                                      focus:border-violet-500 focus:ring-violet-500
                                      dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                        @error('anio_regularizacion') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Datos de aprobación (solo Aprobada) --}}
                    <div x-show="estado === 'Aprobada'" x-cloak
                        class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                            Datos de aprobación
                        </h4>

                        {{-- Tipo de aprobación --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                Tipo de aprobación
                            </label>
                            <select name="tipo_aprobacion" id="tipo_aprobacion" x-model="tipoAprobacion" class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                           bg-white text-gray-700
                                           focus:border-violet-500 focus:ring-violet-500
                                           dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                                <option value="">Seleccione...</option>
                                <option value="directa">Aprobación directa</option>
                                <option value="final">Examen final</option>
                            </select>
                            @error('tipo_aprobacion') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Libro / Tomo / Folio / Nota --}}
                        <div x-show="tipoAprobacion !== ''" x-cloak class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Libro</label>
                                <input type="text" name="libro"
                                    class="mt-1 block w-full rounded-md border-violet-300 shadow-sm bg-white text-gray-700 focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Tomo</label>
                                <input type="text" name="tomo"
                                    class="mt-1 block w-full rounded-md border-violet-300 shadow-sm bg-white text-gray-700 focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Folio</label>
                                <input type="text" name="acta"
                                    class="mt-1 block w-full rounded-md border-violet-300 shadow-sm bg-white text-gray-700 focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-900 dark:text-white">Nota
                                    final</label>
                                <input type="number" step="0.01" name="nota_final"
                                    class="mt-1 block w-full rounded-md border-violet-300 shadow-sm bg-white text-gray-700 focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            </div>
                        </div>
                    </div>

                    {{-- Observaciones --}}
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                            Observaciones (opcional)
                        </label>
                        <textarea name="observaciones" rows="3" class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                         bg-white text-gray-700
                                         focus:border-violet-500 focus:ring-violet-500
                                         dark:bg-indigo-300 dark:border-violet-600 dark:text-black"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('alumnos.estado', $student->id) }}"
                            class="px-4 py-2 rounded-md text-sm font-semibold border border-purple-300 text-white bg-purple-500 hover:bg-purple-700 transition">
                            Cancelar
                        </a>

                        {{-- Botón con ID para script --}}
                        <button type="submit" id="btn-guardar-estado"
                            class="px-4 py-2 rounded-md text-sm font-semibold text-white bg-indigo-500 hover:bg-indigo-700 transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ==========================
        RESUMEN DE ESTADOS
        ========================== --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    Estados cargados para este alumno
                </h3>

                @if($estados->isEmpty())
                    <p class="text-sm text-gray-500 dark:text-gray-400 italic">
                        Aún no se cargaron estados académicos para este alumno.
                    </p>
                @else
                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-violet-100 dark:bg-purple-900">
                                <tr>
                                    <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">Materia</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">Comisión</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">Estado</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">Tipo
                                        aprobación</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">Nota</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-purple-800/50 divide-y divide-purple-100 dark:divide-purple-700">
                                @foreach($estados as $estado)
                                    <tr>
                                        <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                            {{ $estado->subject->nombre ?? 'Materia' }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                            {{ $estado->commission->nombre ?? '-' }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                            {{ $estado->estado }}
                                        </td>
                                        <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
                                            @if($estado->tipo_aprobacion === 'directa')
                                                Aprobación directa
                                            @elseif($estado->tipo_aprobacion === 'final')
                                                Examen final
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-gray-800 dark:text-gray-200">
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

    {{-- SweetAlert2 + Confirmación --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('form-estado-academico');
                const btnGuardar = document.getElementById('btn-guardar-estado');

                if (!form || !btnGuardar) return;

                btnGuardar.addEventListener('click', (e) => {
                    e.preventDefault();

                    // 1) Validación HTML5
                    if (typeof form.reportValidity === 'function') {
                        if (!form.reportValidity()) return;
                    } else if (!form.checkValidity()) {
                        return;
                    }

                    // 2) Preparar datos para el resumen
                    const subjectSelect = form.querySelector('[name="subject_id"]');
                    const materiaTexto = (subjectSelect && subjectSelect.value)
                        ? subjectSelect.options[subjectSelect.selectedIndex].text
                        : '';

                    const estado = form.querySelector('[name="estado"]').value;
                    const tipoAprobSelect = form.querySelector('[name="tipo_aprobacion"]');
                    const tipoAprob = (tipoAprobSelect && tipoAprobSelect.value)
                        ? tipoAprobSelect.options[tipoAprobSelect.selectedIndex].text
                        : '';

                    // 3) SweetAlert
                    Swal.fire({
                        title: '¿Guardar estado académico?',
                        html: `
                                <div style="text-align:left">
                                    <p><strong>Materia:</strong> ${materiaTexto || '—'}</p>
                                    <p><strong>Estado:</strong> ${estado || '—'}</p>
                                    ${estado === 'Aprobada'
                                ? `<p><strong>Tipo:</strong> ${tipoAprob || '—'}</p>`
                                : ''
                            }
                                </div>
                            `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#6366f1', // Indigo-500
                        cancelButtonColor: '#a855f7',  // Purple-500
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'Cancelar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush

</x-app-interno-layout>
