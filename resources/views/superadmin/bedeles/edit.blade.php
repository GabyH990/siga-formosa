<x-app-interno-layout>
    @section('title', 'Editar Bedeles – SIGA')

    <x-slot name="header">
        Editar Bedel: {{ $bedel->name }}
    </x-slot>

    <div class="max-w-4xl mx-auto bg-white dark:bg-violet-950 shadow rounded-lg p-6">
        
        {{-- Formulario con ID para el script --}}
        <form id="form-editar-bedel" method="POST" action="{{ route('superadmin.bedeles.update', $bedel->id) }}" x-data="{ dirty: false }" x-on:change="dirty = true">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white">Nombre</label>
                    <input type="text" name="name" value="{{ old('name', $bedel->name) }}" required
                        class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $bedel->email) }}" required
                        class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-800 dark:text-white">Carrera Asignada</label>
                    <select name="career_id" required
                        class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                        <option value="">Seleccione Carrera</option>
                        @foreach($careers as $career)
                            <option value="{{ $career->id }}" {{ old('career_id', $bedel->career_id) == $career->id ? 'selected' : '' }}>{{ $career->nombre }}</option>
                        @endforeach
                    </select>
                    @error('career_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nueva Contraseña
                        (Opcional)</label>
                    <input type="password" name="password"
                        class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Nueva
                        Contraseña</label>
                    <input type="password" name="password_confirmation"
                        class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-4">
                <a href="{{ route('superadmin.bedeles.index') }}"
                    class="bg-purple-400 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>
                
                {{-- Botón con ID para el script --}}
                <button type="submit" id="btn-guardar-bedel" 
                    class="bg-indigo-400 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded">
                    Guardar Cambios
                </button>
            </div>
        </form>
    </div>

    {{-- SweetAlert2 --}}
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
                    html: `Se actualizarán los datos del bedel <strong>{{ $bedel->name }}</strong>.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#818cf8', // Indigo-400 para combinar con el botón
                    cancelButtonColor: '#a78bfa',  // Purple-400 para combinar con cancelar
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