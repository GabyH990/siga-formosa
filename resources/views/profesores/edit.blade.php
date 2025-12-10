<x-app-interno-layout>
    @section('title', 'Editar Profesores – SIGA')

    <x-slot name="header">
        Editar Profesor: {{ $professor->apellido }}, {{ $professor->nombre }}
    </x-slot>

    <div class="flex justify-center">
        {{-- Tarjeta contenedora --}}
        <div class="w-full max-w-xl bg-violet-50 dark:bg-violet-950 shadow-md rounded-xl p-8 border border-violet-100 dark:border-violet-900">

            {{-- Formulario con ID --}}
            <form id="edit-professor-form" method="POST" action="{{ route('profesores.update', $professor->id) }}" x-data="{ dirty: false }" x-on:change="dirty = true">
                @csrf
                @method('PUT')

                {{-- Legajo --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">
                        Legajo
                    </label>
                    <input type="text" name="legajo" value="{{ old('legajo', $professor->legajo) }}" required
                        class="mt-1 block w-full rounded-full border border-violet-200 px-4 py-2 shadow-sm 
                               focus:outline-none focus:ring-2 focus:ring-violet-400 
                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    @error('legajo') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nombre --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">
                        Nombre
                    </label>
                    <input type="text" name="nombre" value="{{ old('nombre', $professor->nombre) }}" required
                        class="mt-1 block w-full rounded-full border border-violet-200 px-4 py-2 shadow-sm 
                               focus:outline-none focus:ring-2 focus:ring-violet-400 
                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    @error('nombre') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Apellido --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">
                        Apellido
                    </label>
                    <input type="text" name="apellido" value="{{ old('apellido', $professor->apellido) }}" required
                        class="mt-1 block w-full rounded-full border border-violet-200 px-4 py-2 shadow-sm 
                               focus:outline-none focus:ring-2 focus:ring-violet-400 
                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    @error('apellido') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Correo --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">
                        Correo
                    </label>
                    <input type="email" name="correo" value="{{ old('correo', $professor->correo) }}"
                        class="mt-1 block w-full rounded-full border border-violet-200 px-4 py-2 shadow-sm 
                               focus:outline-none focus:ring-2 focus:ring-violet-400 
                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    @error('correo') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Teléfono --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">
                        Teléfono
                    </label>
                    <input type="text" name="telefono" value="{{ old('telefono', $professor->telefono) }}"
                        class="mt-1 block w-full rounded-full border border-violet-200 px-4 py-2 shadow-sm 
                               focus:outline-none focus:ring-2 focus:ring-violet-400 
                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                </div>

                {{-- Título --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white">
                        Título
                    </label>
                    <input type="text" name="titulo" value="{{ old('titulo', $professor->titulo) }}"
                        class="mt-1 block w-full rounded-full border border-violet-200 px-4 py-2 shadow-sm 
                               focus:outline-none focus:ring-2 focus:ring-violet-400 
                               dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                </div>

                {{-- Cátedras (varias) --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Cátedras
                    </label>

                    <div class="max-h-64 overflow-y-auto border rounded-lg p-3 bg-white dark:bg-purple-900 dark:border-gray-600">
                        @foreach($subjects as $subject)
                            <label class="flex items-center py-1">
                                <input type="checkbox" name="subjects[]" value="{{ $subject->id }}"
                                    @checked(in_array($subject->id, old('subjects', $selectedSubjects)))
                                    class="rounded border-gray-300 text-sky-600 shadow-sm 
                                           focus:border-sky-300 focus:ring focus:ring-sky-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-200">
                                    {{ $subject->nombre }}
                                    @if($subject->career)
                                        ({{ $subject->career->codigo }})
                                    @endif
                                </span>
                            </label>
                        @endforeach
                    </div>
                    @error('subjects') <p class="text-xs text-pink-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="mt-6 flex justify-end gap-4">
                    <a href="{{ route('profesores.listado') }}"
                        class="bg-purple-500 hover:bg-purple-600 text-white font-bold py-2 px-4 rounded-full transition">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-6 rounded-full transition">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script de confirmación con SweetAlert2 --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('edit-professor-form');

                if (form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault(); // frenamos el envío normal

                        Swal.fire({
                            title: '¿Confirmar cambios?',
                            text: 'Se guardarán los datos del profesor {{ $professor->apellido }}, {{ $professor->nombre }}.',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#6366f1', // Indigo-500
                            cancelButtonColor: '#a855f7',  // Purple-500
                            confirmButtonText: 'Sí, guardar',
                            cancelButtonText: 'Cancelar',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit(); // enviamos el form
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
</x-app-interno-layout>