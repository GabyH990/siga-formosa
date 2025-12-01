{{-- resources/views/alumnos/edit.blade.php --}}
<x-app-interno-layout>
    <x-slot name="header">
        Editar Alumno: {{ $student->apellido }}, {{ $student->nombre }}
    </x-slot>

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
            <form method="POST" action="{{ route('alumnos.update', $student->id) }}" class="space-y-4">
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

                    <button type="submit"
                            class="px-4 py-2 rounded-md text-sm font-semibold text-white
                                   bg-blue-600 hover:bg-blue-700">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-interno-layout>
