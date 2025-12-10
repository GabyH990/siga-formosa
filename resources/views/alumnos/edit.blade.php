<x-app-interno-layout>
    @section('title', 'Editar Alumno – SIGA')

    <x-slot name="header">
        Editar Alumno: {{ $student->apellido }}, {{ $student->nombre }}
    </x-slot>

    <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
        <div class="space-y-6 bg-white dark:bg-violet-950 p-6 rounded-lg">
            
            {{-- Datos actuales del alumno (Header) --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">
                    {{ $student->apellido }}, {{ $student->nombre }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Legajo: <span class="font-semibold">{{ $student->legajo }}</span>
                    @if($student->career)
                        · Carrera: <span class="font-semibold">
                            {{ $student->career->codigo ?? '' }} - {{ $student->career->nombre ?? '' }}
                        </span>
                    @endif
                    @if($student->cohorte)
                        · Cohorte: <span class="font-semibold">{{ $student->cohorte }}</span>
                    @endif
                </p>
            </div>

            {{-- Formulario de edición --}}
            <div>
                <form id="form-edit-alumno" method="POST" action="{{ route('alumnos.update', $student->id) }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Legajo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                            Legajo
                        </label>
                        <input type="text" name="legajo" value="{{ old('legajo', $student->legajo) }}"
                            class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                   focus:border-violet-500 focus:ring-violet-500
                                   dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                        @error('legajo')
                            <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Nombre y Apellido --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                Nombre
                            </label>
                            <input type="text" name="nombre" value="{{ old('nombre', $student->nombre) }}"
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('nombre')
                                <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                Apellido
                            </label>
                            <input type="text" name="apellido" value="{{ old('apellido', $student->apellido) }}"
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('apellido')
                                <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- DNI y Fecha de nacimiento --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                DNI
                            </label>
                            <input type="text" name="dni" value="{{ old('dni', $student->dni) }}"
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('dni')
                                <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                Fecha de nacimiento
                            </label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $student->fecha_nacimiento) }}"
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('fecha_nacimiento')
                                <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Correo y Teléfono --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                Correo electrónico
                            </label>
                            <input type="email" name="correo" value="{{ old('correo', $student->correo) }}"
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('correo')
                                <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                Teléfono
                            </label>
                            <input type="text" name="telefono" value="{{ old('telefono', $student->telefono) }}"
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('telefono')
                                <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Dirección --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                            Dirección
                        </label>
                        <input type="text" name="direccion" value="{{ old('direccion', $student->direccion) }}"
                            class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                   focus:border-violet-500 focus:ring-violet-500
                                   dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                        @error('direccion')
                            <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cohorte y Carrera --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                Cohorte
                            </label>
                            <input type="number" name="cohorte" value="{{ old('cohorte', $student->cohorte) }}"
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('cohorte')
                                <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">
                                Carrera
                            </label>
                            <select name="career_id"
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ (int) old('career_id', $student->career_id) === $career->id ? 'selected' : '' }}>
                                        {{ $career->codigo }} - {{ $career->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('career_id')
                                <p class="text-xs text-pink-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end gap-3 pt-6">
                        <a href="{{ route('alumnos.index') }}"
                            class="px-4 py-2 rounded-md text-sm font-semibold border border-purple-300 text-white bg-purple-500 hover:bg-purple-700 transition">
                            Cancelar
                        </a>

                        {{-- Botón con ID para el script --}}
                        <button type="button" id="btn-guardar-cambios"
                            class="px-4 py-2 rounded-md text-sm font-semibold text-white bg-indigo-500 hover:bg-indigo-700 transition">
                            Guardar cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script SweetAlert2 --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('form-edit-alumno');
                const btnGuardar = document.getElementById('btn-guardar-cambios');

                if (!form || !btnGuardar) return;

                btnGuardar.addEventListener('click', (e) => {
                    e.preventDefault();

                    // 1) Validación HTML5
                    if (typeof form.reportValidity === 'function') {
                        if (!form.reportValidity()) return;
                    } else if (!form.checkValidity()) {
                        return;
                    }

                    // 2) Armar resumen
                    const apellido = form.querySelector('[name="apellido"]').value || '';
                    const nombre = form.querySelector('[name="nombre"]').value || '';
                    const legajo = form.querySelector('[name="legajo"]').value || '';
                    const dni = form.querySelector('[name="dni"]').value || '';

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
                        confirmButtonColor: '#6366f1', // Indigo
                        cancelButtonColor: '#a855f7',  // Purple
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'Revisar',
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