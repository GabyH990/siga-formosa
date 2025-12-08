<x-app-interno-layout>
    <x-slot name="header">
        Nuevo Bedel
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <form id="form-nuevo-bedel"
                      method="POST"
                      action="{{ route('superadmin.bedeles.store') }}"
                      x-on:change="dirty = true">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                          focus:border-blue-500 focus:ring-blue-500
                                          dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Correo Electrónico
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                          focus:border-blue-500 focus:ring-blue-500
                                          dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('email')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Carrera -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Carrera Asignada
                            </label>
                            <select name="career_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                           focus:border-blue-500 focus:ring-blue-500
                                           dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Seleccione Carrera</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}"
                                        {{ old('career_id') == $career->id ? 'selected' : '' }}>
                                        {{ $career->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('career_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contraseña
                            </label>
                            <input type="password" name="password" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                          focus:border-blue-500 focus:ring-blue-500
                                          dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('password')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Confirmar Contraseña
                            </label>
                            <input type="password" name="password_confirmation" required
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                          focus:border-blue-500 focus:ring-blue-500
                                          dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('superadmin.bedeles.index') }}"
                           class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>
                        <button id="btn-guardar-bedel"
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Confirmación de guardado --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-nuevo-bedel');
            const btnGuardar = document.getElementById('btn-guardar-bedel');

            if (!form || !btnGuardar) return;

            btnGuardar.addEventListener('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Crear nuevo bedel?',
                    html: `
                        <p style="text-align:left">
                            Se dará de alta un nuevo usuario bedel con los datos cargados.
                        </p>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar'
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-interno-layout>
