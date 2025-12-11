<x-app-interno-layout>
    @section('title', 'Módulo Profesores – SIGA')
    <x-slot name="header">
        Módulo Profesores
    </x-slot>

    <div class="space-y-6">

        {{-- Barra superior --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <div class="w-full md:w-2/3">
                    <form method="GET" action="{{ route('profesores.listado') }}" class="flex flex-col md:flex-row gap-2 md:items-center">
                        <div class="flex-1 flex gap-2">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por Legajo, Nombre, Correo..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-400 dark:bg-indigo-300 dark:border-violet-500 dark:text-black">
                            <button type="submit" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                                Buscar
                            </button>
                        </div>
                        <div class="mt-2 md:mt-0">
                            <button type="button" onclick="const form = this.closest('form'); const hidden = form.querySelector('input[name=ver_inactivos]'); hidden.value = hidden.value === '1' ? '0' : '1'; form.submit();"
                                class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 bg-fuchsia-400 text-sm font-medium text-gray-900 hover:bg-violet-700 dark:bg-fuchsia-400 dark:text-gray-900 dark:border-gray-500">
                                {{ $verInactivos ? 'Ocultar inactivos' : 'Ver también profesores inactivos' }}
                            </button>
                            <input type="hidden" name="ver_inactivos" value="{{ $verInactivos ? '1' : '0' }}">
                        </div>
                    </form>
                </div>
                <div>
                    <a href="{{ route('profesores.create') }}" class="bg-indigo-400 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                        + Nuevo Profesor
                    </a>
                </div>
            </div>

            {{-- Tabla de profesores --}}
            <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
                <div class="bg-white dark:bg-violet-950 shadow rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-blue-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-violet-950">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Legajo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Apellido y Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Correo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Teléfono</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Asignaciones</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-violet-900 divide-y divide-blue-300 dark:divide-purple-300">
                                @forelse($professors as $professor)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white">{{ $professor->legajo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">{{ $professor->apellido }}, {{ $professor->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white">{{ $professor->correo }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white">{{ $professor->telefono }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white">{{ $professor->titulo }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-white">
                                            @if($professor->subjects->isEmpty())
                                                <span class="italic text-gray-800800">Sin asignaciones</span>
                                            @else
                                                <ul class="list-disc list-inside space-y-1">
                                                    @foreach($professor->subjects as $subject)
                                                        <li>{{ $subject->nombre }} @if($subject->career) ({{ $subject->career->codigo }}) @endif</li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if($professor->activo)
                                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-indigo-300 text-indigo-800">Activo</span>
                                            @else
                                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-pink-300 text-pink-800">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                            @if($professor->activo)
                                                {{-- Editar --}}
                                                <a href="{{ route('profesores.edit', $professor->id) }}" class="text-purple-400 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300">Editar</a>

                                                {{-- Desactivar (Con SweetAlert) --}}
                                                <form action="{{ route('profesores.destroy', $professor->id) }}" 
                                                      method="POST" 
                                                      class="inline-block form-desactivar" 
                                                      data-name="{{ $professor->apellido }}, {{ $professor->nombre }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-pink-600 hover:text-pink-800 dark:text-pink-600 dark:hover:text-pink-800">
                                                        Desactivar
                                                    </button>
                                                </form>
                                            @else
                                                {{-- Activar (Con SweetAlert) --}}
                                                <form action="{{ route('profesores.activar', $professor->id) }}" 
                                                      method="POST" 
                                                      class="inline-block form-activar"
                                                      data-name="{{ $professor->apellido }}, {{ $professor->nombre }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-indigo-400 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                                                        Activar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                            No se encontraron profesores para los filtros aplicados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4">
                        {{ $professors->links() }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="flex justify-center mt-6">
            <a href="{{ route('panel') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded">
                Volver al Panel
            </a>
        </div>

        {{-- Script SweetAlert2 --}}
        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    // Desactivar
                    document.querySelectorAll('.form-desactivar').forEach(form => {
                        form.addEventListener('submit', function (e) {
                            e.preventDefault();
                            const name = this.dataset.name;
                            Swal.fire({
                                title: '¿Seguro que querés desactivar a este profesor?',
                                text: name,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33', // Color rojo para acción destructiva
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Sí, desactivar',
                                cancelButtonText: 'Cancelar',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.submit();
                                }
                            });
                        });
                    });

                    // Activar
                    document.querySelectorAll('.form-activar').forEach(form => {
                        form.addEventListener('submit', function (e) {
                            e.preventDefault();
                            const name = this.dataset.name;
                            Swal.fire({
                                title: '¿Activar nuevamente a este profesor?',
                                text: name,
                                icon: 'question',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Sí, activar',
                                cancelButtonText: 'Cancelar',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    this.submit();
                                }
                            });
                        });
                    });
                });
            </script>
        @endpush
</x-app-interno-layout>