<x-app-interno-layout>
    <x-slot name="header">
        Agregar Profesores
    </x-slot>

    <div class="flex justify-center">
        <div class="w-full max-w-xl bg-violet-50 dark:bg-gray-800 shadow-md rounded-xl p-8">
            
            <form
                id="create-professor-form"
                method="POST"
                action="{{ route('profesores.store') }}"
                x-on:change="dirty = true"
            >
                @csrf

                {{-- Legajo --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Legajo
                    </label>
                    <input type="text" name="legajo" value="{{ old('legajo') }}" required
                        class="mt-1 block w-full rounded-full border border-violet-200 px-4 py-2
                               shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('legajo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nombre --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Nombre
                    </label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required
                        class="mt-1 block w-full rounded-full border border-sky-200 px-4 py-2
                               shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('nombre') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Apellido --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Apellido
                    </label>
                    <input type="text" name="apellido" value="{{ old('apellido') }}" required
                        class="mt-1 block w-full rounded-full border border-sky-200 px-4 py-2
                               shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('apellido') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Correo --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Correo
                    </label>
                    <input type="email" name="correo" value="{{ old('correo') }}"
                        class="mt-1 block w-full rounded-full border border-sky-200 px-4 py-2
                               shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('correo') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Teléfono --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Teléfono
                    </label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                        class="mt-1 block w-full rounded-full border border-sky-200 px-4 py-2
                               shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- Título (opcional) --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Título
                    </label>
                    <input type="text" name="titulo" value="{{ old('titulo') }}"
                        class="mt-1 block w-full rounded-full border border-sky-200 px-4 py-2
                               shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-400
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>

                {{-- Cátedras (varias) --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cátedras
                    </label>

                    <div class="max-h-64 overflow-y-auto border rounded-lg p-3
                                bg-white/60 dark:bg-gray-800 dark:border-gray-600">
                        @foreach($subjects as $subject)
                            <label class="flex items-center py-1">
                                <input type="checkbox" name="subjects[]"
                                       value="{{ $subject->id }}"
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
                    @error('subjects') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Botón Guardar --}}
                <div class="mt-6 flex justify-center">
                    <button type="submit"
                        class="px-10 py-2 rounded-full text-sm font-semibold text-white
                               bg-sky-500 hover:bg-sky-600">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        {{-- SweetAlert2 (si ya lo cargás en el layout, podés borrar esta línea) --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('create-professor-form');
                if (!form) return;

                form.addEventListener('submit', function (e) {
                    e.preventDefault(); // frenamos el envío normal

                    const formData = new FormData(form);

                    const legajo   = formData.get('legajo')   || '';
                    const nombre   = formData.get('nombre')   || '';
                    const apellido = formData.get('apellido') || '';
                    const correo   = formData.get('correo')   || '';
                    const telefono = formData.get('telefono') || '';
                    const titulo   = formData.get('titulo')   || '';

                    const resumen = `
Legajo:   ${legajo}
Nombre:   ${nombre}
Apellido: ${apellido}
Correo:   ${correo || '-'}
Teléfono: ${telefono || '-'}
Título:   ${titulo || '-'}
`;

                    Swal.fire({
                        title: '¿Los datos son correctos?',
                        icon: 'question',
                        html: '<pre style="text-align:left; white-space:pre-wrap;">' + resumen + '</pre>',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'Revisar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit(); // ahora sí, enviamos el formulario
                        }
                    });
                });
            });
        </script>
    @endpush
</x-app-interno-layout>
