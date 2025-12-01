<x-app-interno-layout>
    <x-slot name="header">
        Nuevo Alumno
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form method="POST" action="{{ route('alumnos.store') }}" x-on:change="dirty = true">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Legajo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Legajo</label>
                    <input type="text" name="legajo" value="{{ old('legajo') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('legajo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- DNI -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">DNI</label>
                    <input type="text" name="dni" value="{{ old('dni') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Apellido -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha Nacimiento -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Cohorte -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cohorte (Año)</label>
                    <input type="number" name="cohorte" value="{{ old('cohorte', date('Y')) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('cohorte') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Carrera -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carrera</label>
                    <select name="career_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccione Carrera</option>
                        @foreach($careers as $career)
                            <option value="{{ $career->id }}" {{ old('career_id') == $career->id ? 'selected' : '' }}>
                                {{ $career->nombre }}</option>
                        @endforeach
                    </select>
                    @error('career_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Correo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo</label>
                    <input type="email" name="correo" value="{{ old('correo') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Teléfono -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('alumnos.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</x-app-interno-layout>
