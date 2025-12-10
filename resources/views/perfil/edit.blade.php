<x-app-interno-layout>
    @section('title', 'Editar Perfil – SIGA')

    <x-slot name="header">
        Editar Perfil
    </x-slot>

    <div class="max-w-3xl mx-auto">
        {{-- Borde Gradiente --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            
            {{-- Tarjeta Principal --}}
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-8">
                
                <form id="form-perfil" method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data"
                    x-data="{ dirty: false }" x-on:change="dirty = true">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Foto de Perfil</label>
                            <input type="file" name="foto"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-300
                                       file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100
                                       dark:file:bg-indigo-300 dark:file:text-indigo-800">
                            @error('foto') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('name') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('email') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        {{-- Sección Cambio de Contraseña --}}
                        <div class="border-t border-violet-100 dark:border-violet-800 pt-6 mt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Cambiar Contraseña</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Contraseña Actual
                                    </label>
                                    <input type="password" name="current_password"
                                        class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                               focus:border-violet-500 focus:ring-violet-500 
                                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                                    @error('current_password') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Nueva Contraseña
                                    </label>
                                    <input type="password" name="new_password"
                                        class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                               focus:border-violet-500 focus:ring-violet-500 
                                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                                    @error('new_password') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Confirmar Nueva Contraseña
                                    </label>
                                    <input type="password" name="new_password_confirmation"
                                        class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                               focus:border-violet-500 focus:ring-violet-500 
                                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('perfil.show') }}"
                            class="bg-purple-400 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded transition">
                            Cancelar
                        </a>
                        
                        {{-- Botón con ID para el script --}}
                        <button type="submit" id="btn-guardar-perfil"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 para confirmar guardado --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('form-perfil');
                const btnGuardar = document.getElementById('btn-guardar-perfil');

                if (!form || !btnGuardar) return;

                btnGuardar.addEventListener('click', (e) => {
                    e.preventDefault();

                    // 1) Validación HTML5 (campos required, formato de email, etc.)
                    // Esto evita que salga el SweetAlert si el formulario está incompleto
                    if (typeof form.reportValidity === 'function') {
                        if (!form.reportValidity()) {
                            return;
                        }
                    } else if (!form.checkValidity()) {
                        return; // Fallback para navegadores viejos
                    }

                    // 2) Confirmación con SweetAlert
                    Swal.fire({
                        title: "¿Guardar cambios del perfil?",
                        text: "Se actualizarán tus datos y, si corresponde, tu contraseña.",
                        icon: "question",
                        showCancelButton: true,
                        confirmButtonColor: '#6366f1', // Indigo-500
                        cancelButtonColor: '#a855f7',  // Purple-500
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
    @endpush
</x-app-interno-layout>