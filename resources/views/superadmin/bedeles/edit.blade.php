<x-app-interno-layout>
    <x-slot name="header">
        Editar Bedel: {{ $bedel->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <form id="form-editar-bedel"
              method="POST"
              action="{{ route('superadmin.bedeles.update', $bedel->id) }}"
              x-on:change="dirty = true">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $bedel->name) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $bedel->email) }}" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Carrera -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Carrera Asignada</label>
                    <select name="career_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccione Carrera</option>
                        @foreach($careers as $career)
                            <option value="{{ $career->id }}"
                                {{ old('career_id', $bedel->career_id) == $career->id ? 'selected' : '' }}>
                                {{ $career->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('career_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Password (Optional) -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nueva Contraseña (Opcional)
                    </label>
                    <input type="password" name="password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Confirmar Nueva Contraseña
                    </label>
                    <input type="password" name="password_confirmation"
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
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    {{-- SweetAlert2 (por si no está en el layout) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-editar-bedel');
            const btnGuardar = document.getElementById('btn-guardar-bedel');

            if (!form || !btnGuardar) return;

            btnGuardar.addEventListener('click', function (e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Guardar cambios del bedel?',
                    html: `
                        <p style="text-align:left">
                            Se actualizarán los datos del bedel <strong>{{ $bedel->name }}</strong>.
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
