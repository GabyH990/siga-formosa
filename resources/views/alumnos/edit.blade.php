<x-app-interno-layout>
    <x-slot name="header">
        Editar Profesor: {{ $professor->apellido }}, {{ $professor->nombre }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form method="POST" action="{{ route('profesores.update', $professor->id) }}" x-on:change="dirty = true">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Legajo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Legajo</label>
                    <input type="text" name="legajo" value="{{ old('legajo', $professor->legajo) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('legajo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $professor->nombre) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Apellido -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido', $professor->apellido) }}" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Correo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo</label>
                    <input type="email" name="correo" value="{{ old('correo', $professor->correo) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('correo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $professor->telefono) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Título -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título</label>
                    <input type="text" name="titulo" value="{{ old('titulo', $professor->titulo) }}"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                    Cátedras que dicta
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">
                    Marcá todas las cátedras que dicta este profesor.
                    Podés <strong>agregar nuevas</strong> o <strong>quitar</strong> las que ya no dicta.
                </p>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto border rounded p-4 dark:border-gray-600">
                    @foreach($subjects as $subject)
                        <label class="flex items-start gap-2">
                            <input type="checkbox" name="subjects[]"
                                   value="{{ $subject->id }}"
                                   {{ in_array($subject->id, old('subjects', $selectedSubjects)) ? 'checked' : '' }}
                                   class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                <span class="font-semibold">
                                    {{ $subject->career->codigo ?? '' }}
                                    @if($subject->career) · @endif
                                    {{ $subject->nombre }}
                                </span>
                            </span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('profesores.listado') }}"
                   class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</x-app-interno-layout>
