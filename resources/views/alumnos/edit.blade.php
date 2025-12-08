{{-- resources/views/alumnos/edit.blade.php --}}
<x-app-interno-layout>
    <x-slot name="header">
        Editar Alumno: {{ $student->apellido }}, {{ $student->nombre }}
    </x-slot>
<div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
    <div class="space-y-6">
        {{-- Datos actuales del alumno --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">
                {{ $student->apellido }}, {{ $student->nombre }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Legajo:
                <span class="font-semibold">{{ $student->legajo }}</span>
                @if($student->career)
                    · Carrera:
                    <span class="font-semibold">
                        {{ $student->career->codigo ?? '' }} - {{ $student->career->nombre ?? '' }}
                    </span>
                @endif
                @if($student->cohorte)
                    · Cohorte:
                    <span class="font-semibold">{{ $student->cohorte }}</span>
                @endif
            </p>
        </div>

        {{-- Formulario de edición --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            {{-- 👇 CAMBIO MÍNIMO: agrego id al form --}}
            <form id="form-edit-alumno" method="POST" action="{{ route('alumnos.update', $student->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Legajo --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Legajo
                    </label>
                    <input type="text"
                           name="legajo"
                           value="{{ old('legajo', $student->legajo) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                  focus:border-blue-500 focus:ring-blue-500
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('legajo')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nombre y Apellido --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nombre
                        </label>
                        <input type="text"
                               name="nombre"
                               value="{{ old('nombre', $student->nombre) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('nombre')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Apellido
                        </label>
                        <input type="text"
                               name="apellido"
                               value="{{ old('apellido', $student->apellido) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('apellido')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- DNI y Fecha de nacimiento --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            DNI
                        </label>
                        <input type="text"
                               name="dni"
                               value="{{ old('dni', $student->dni) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('dni')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Fecha de nacimiento
                        </label>
                        <input type="date"
                               name="fecha_nacimiento"
                               value="{{ old('fecha_nacimiento', $student->fecha_nacimiento) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('fecha_nacimiento')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Correo y Teléfono --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Correo electrónico
                        </label>
                        <input type="email"
                               name="correo"
                               value="{{ old('correo', $student->correo) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('correo')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Teléfono
                        </label>
                        <input type="text"
                               name="telefono"
                               value="{{ old('telefono', $student->telefono) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('telefono')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Dirección --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Dirección
                    </label>
                    <input type="text"
                           name="direccion"
                           value="{{ old('direccion', $student->direccion) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                  focus:border-blue-500 focus:ring-blue-500
                                  dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('direccion')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cohorte y Carrera --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cohorte
                        </label>
                        <input type="number"
                               name="cohorte"
                               value="{{ old('cohorte', $student->cohorte) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        @error('cohorte')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Carrera
                        </label>
                        <select name="career_id"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($careers as $career)
                                <option value="{{ $career->id }}"
                                    {{ (int) old('career_id', $student->career_id) === $career->id ? 'selected' : '' }}>
                                    {{ $career->codigo }} - {{ $career->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('career_id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end gap-3 pt-4">
                    <a href="{{ route('alumnos.index') }}"
                       class="px-4 py-2 rounded-md text-sm font-semibold border
                              border-gray-300 text-gray-700 bg-white hover:bg-gray-50
                              dark:border-gray-600 dark:text-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700">
                        Cancelar
                    </a>

                    {{-- 👇 CAMBIO MÍNIMO: botón pasa a type="button" y le damos id --}}
                    <button type="button"
                            id="btn-guardar-cambios"
                            class="px-4 py-2 rounded-md text-sm font-semibold text-white
                                   bg-blue-600 hover:bg-blue-700">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SweetAlert2 (si no lo tenés en el layout) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-edit-alumno');
        const btnGuardar = document.getElementById('btn-guardar-cambios');

        if (!form || !btnGuardar) return;

        btnGuardar.addEventListener('click', () => {
            // 1) Validar HTML5 primero (required, formatos, etc.)
            if (typeof form.reportValidity === 'function') {
                if (!form.reportValidity()) {
                    return;
                }
            } else if (!form.checkValidity()) {
                return;
            }

            // 2) Armar un pequeño resumen para confirmar
            const apellido = form.apellido.value || '';
            const nombre   = form.nombre.value || '';
            const legajo   = form.legajo.value || '';
            const dni      = form.dni.value || '';

            Swal.fire({
                title: '¿Guardar cambios del alumno?',
                html: `
                    <div style="text-align:left">
                        <p><strong>Alumno:</strong> ${apellido}, ${nombre}</p>
                        <p><strong>Legajo:</strong> ${legajo || '—'}</p>
                        <p><strong>DNI:</strong> ${dni || '—'}</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, guardar',
                cancelButtonText: 'Revisar',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // acá va a tu AlumnoController@update
                }
            });
        });
    });
</script>
</x-app-interno-layout>
