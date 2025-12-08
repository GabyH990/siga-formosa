<x-app-interno-layout>
    <x-slot name="header">
        Editar Perfil
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form id="form-perfil"
              method="POST"
              action="{{ route('perfil.update') }}"
              enctype="multipart/form-data"
              x-on:change="dirty = true">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <!-- Foto -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto de Perfil</label>
                    <input type="file" name="foto"
                        class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                               file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                               file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100">
                    @error('foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Cambiar Contraseña</h3>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Contraseña Actual
                            </label>
                            <input type="password" name="current_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('current_password')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nueva Contraseña
                            </label>
                            <input type="password" name="new_password"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @error('new_password')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Confirmar Nueva Contraseña
                            </label>
                            <input type="password" name="new_password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('perfil.show') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>

                {{-- CAMBIO: botón pasa a type="button" y tiene id --}}
                <button type="button"
                        id="btn-guardar-perfil"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    {{-- SweetAlert2 para confirmar guardado --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-perfil');
            const btnGuardar = document.getElementById('btn-guardar-perfil');

            if (!form || !btnGuardar) return;

            btnGuardar.addEventListener('click', (e) => {
                e.preventDefault();

                // 1) Validación HTML5 (campos required, formato de email, etc.)
                if (typeof form.reportValidity === 'function') {
                    if (!form.reportValidity()) {
                        return;
                    }
                } else if (!form.checkValidity()) {
                    return;
                }

                // 2) Confirmación con SweetAlert
                Swal.fire({
                    title: "¿Guardar cambios del perfil?",
                    text: "Se actualizarán tus datos y, si corresponde, tu contraseña.",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Sí, guardar",
                    cancelButtonText: "Cancelar"
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-app-interno-layout>
