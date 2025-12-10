<x-app-interno-layout>
    @section('title', 'Nuevo Bedel – SIGA')

    <x-slot name="header">
        Nuevo Bedel
    </x-slot>

    <div class="max-w-4xl mx-auto">
        {{-- Borde Gradiente --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            
            {{-- Contenedor Principal --}}
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                
                {{-- Formulario con ID --}}
                <form id="form-nuevo-bedel" method="POST" action="{{ route('superadmin.bedeles.store') }}" x-data="{ dirty: false }" x-on:change="dirty = true">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('name') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Correo Electrónico</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('email') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Carrera Asignada</label>
                            <select name="career_id" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                                <option value="">Seleccione Carrera</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ old('career_id') == $career->id ? 'selected' : '' }}>
                                        {{ $career->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('career_id') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Contraseña</label>
                            <input type="password" name="password" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('password') <span class="text-pink-600 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('superadmin.bedeles.index') }}"
                            class="bg-purple-400 hover:bg-purple-800 text-white font-bold py-2 px-4 rounded transition">
                            Cancelar
                        </a>
                        
                        {{-- Botón con ID --}}
                        <button type="submit" id="btn-guardar-bedel"
                            class="bg-indigo-400 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Confirmación de guardado con SweetAlert --}}
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
                    html: `Se dará de alta un nuevo usuario bedel con los datos cargados.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#818cf8', // Indigo-400
                    cancelButtonColor: '#a78bfa',  // Purple-400
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