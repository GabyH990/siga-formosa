<x-app-interno-layout>
    @section('title', 'Módulo Alumnos – SIGA')
    <x-slot name="header">
        Módulo Alumnos
    </x-slot>

    <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
        <div class="space-y-6">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <div class="w-full md:w-1/2">
                    <form method="GET" action="{{ route('alumnos.index') }}" class="flex gap-2">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por Legajo, Apellido, DNI..."
                            class="w-full rounded-md border-violet-300 shadow-sm
                                   bg-white text-gray-900 placeholder-gray-500
                                   focus:border-violet-500 focus:ring-violet-900
                                   dark:bg-indigo-300 dark:border-violet-600
                                   dark:text-black dark:placeholder-gray-800">
                        <button type="submit"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                            Buscar
                        </button>
                    </form>
                </div>
                <div>
                    <a href="{{ route('alumnos.create') }}"
                        class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                        + Nuevo Alumno
                    </a>
                </div>
            </div>

            <div class="bg-white dark:bg-violet-950 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-purple-200 dark:divide-purple-500">
                        <thead class="bg-violet-50 dark:bg-violet-950">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-black dark:text-white uppercase tracking-wider">
                                    Legajo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-black dark:text-white uppercase tracking-wider">
                                    Apellido y Nombre
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-black dark:text-white uppercase tracking-wider">
                                    DNI
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-black dark:text-white uppercase tracking-wider">
                                    Fecha de nacimiento
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-black dark:text-white uppercase tracking-wider">
                                    Correo
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-black dark:text-white uppercase tracking-wider">
                                    Teléfono
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-black dark:text-white uppercase tracking-wider">
                                    Dirección
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-black dark:text-white uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-purple-900 divide-y divide-purple-200 dark:divide-purple-500/50">
                            @forelse($students as $student)
                                <tr class="hover:bg-purple-50 dark:hover:bg-violet-800/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">
                                        {{ $student->legajo }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black dark:text-white">
                                        {{ $student->apellido }}, {{ $student->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">
                                        {{ $student->dni }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">
                                        {{ optional($student->fecha_nacimiento) ? \Carbon\Carbon::parse($student->fecha_nacimiento)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">
                                        {{ $student->correo }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">
                                        {{ $student->telefono }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-white">
                                        {{ $student->direccion }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">

                                        {{-- Estado académico --}}
                                        <a href="{{ route('alumnos.estado', $student->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-200 dark:hover:text-indigo-500 font-semibold transition">
                                            Estado Académico
                                        </a>

                                        {{-- Editar --}}
                                        <a href="{{ route('alumnos.edit', $student->id) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-300 dark:hover:text-blue-500 font-semibold transition">
                                            Editar
                                        </a>

                                        {{-- Eliminar (Con SweetAlert) --}}
                                        <form action="{{ route('alumnos.destroy', $student->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            
                                            {{-- Botón con clase y data attribute para el script --}}
                                            <button type="submit" 
                                                class="text-pink-600 hover:text-pink-800 dark:text-pink-400 dark:hover:text-pink-300 ml-2 font-semibold btn-eliminar-alumno transition"
                                                data-nombre="{{ $student->apellido }}, {{ $student->nombre }}">
                                                Eliminar
                                            </button>
                                        </form>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"
                                        class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center italic">
                                        No se encontraron alumnos para los filtros aplicados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    <div class="flex justify-center mt-6">
        <a href="{{ route('panel') }}"
            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded shadow transition">
            Volver al Panel
        </a>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const botonesEliminar = document.querySelectorAll('.btn-eliminar-alumno');

                botonesEliminar.forEach((boton) => {
                    boton.addEventListener('click', function (e) {
                        e.preventDefault();

                        const form = this.closest('form');
                        const nombre = this.getAttribute('data-nombre') || 'este alumno';

                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: `Se eliminará a ${nombre} y también sus estados académicos, asistencias y relaciones con comisiones.`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#db2777', // Color rosa/rojo
                            cancelButtonColor: '#9ca3af',  // Gris
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    @endpush

</x-app-interno-layout>