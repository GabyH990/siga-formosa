<x-app-interno-layout>
    @section('title', 'Estado Académico General – SIGA')
    <x-slot name="header">
        Estado Académico General
    </x-slot>

    <div class="space-y-6">

        {{-- Filtros / búsqueda --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <form method="GET" action="{{ route('estado-academico.index') }}"
                    class="flex flex-col md:flex-row gap-4 items-center md:items-end">
                    <div class="w-full md:flex-1">
                        <label class="block text-sm font-medium text-gray-900 dark:text-white">
                            Buscar Alumno
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Legajo, Apellido, Nombre, DNI..." class="mt-1 block w-full rounded-md border-violet-300 shadow-sm
                                  focus:border-violet-500 focus:ring-violet-500
                                  dark:bg-indigo-300 dark:border-violet-600 text-gray-900 dark:text-black">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold
                                   rounded-md text-white bg-indigo-500 hover:bg-indigo-700">
                            Buscar
                        </button>

                        {{-- Botón Exportar --}}
                        <a href="{{ route('estado-academico.exportar', request()->query()) }}" class="inline-flex items-center px-4 py-2 border border-purple-300 text-sm font-semibold
                              rounded-md text-white bg-purple-500 hover:bg-purple-600">
                            Exportar a Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla principal --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-purple-200 text-xs md:text-sm">
                        <thead class="bg-violet-200 dark:bg-purple-900">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">
                                    Alumno
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">
                                    Legajo
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">
                                    Cohorte
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">
                                    Cursando
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">
                                    Regulares
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">
                                    Aprobadas
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">
                                    Observaciones
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-gray-900 dark:text-white">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-purple-800 divide-y divide-purple-200">
                            @forelse($students as $student)
                                <tr>
                                    {{-- Alumno --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ $student->apellido }}, {{ $student->nombre }}
                                        <div class="text-xs text-gray-900 dark:text-white">
                                            @if($student->career)
                                                {{ $student->career->codigo }} · {{ $student->career->nombre }}
                                            @else
                                                Sin carrera asignada
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Legajo --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ $student->legajo }}
                                    </td>

                                    {{-- Cohorte --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ $student->cohorte ?? '-' }}
                                    </td>

                                    {{-- Cursando --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ $student->cant_cursando ?? 0 }}
                                    </td>

                                    {{-- Regulares --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ $student->cant_regulares ?? 0 }}
                                    </td>

                                    {{-- Aprobadas --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-white">
                                        {{ $student->cant_aprobadas ?? 0 }}
                                    </td>

                                    {{-- Observaciones --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-gray-900 dark:text-white">
                                        @php $obs = $student->cant_observaciones ?? 0; @endphp
                                        @if($obs > 0)
                                            {{ $obs }} materia(s)
                                        @else
                                            -
                                        @endif
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex flex-wrap gap-2">
                                            <a href="{{ route('alumnos.estado', $student->id) }}" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-md
                                                      text-black bg-indigo-300 hover:bg-indigo-500">
                                                Ver detalle
                                            </a>

                                            <a href="{{ route('alumnos.estado.editar', $student->id) }}" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-md
                                                      text-black bg-purple-300 hover:bg-apurple-500">
                                                Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-sm text-black dark:white">
                                        No se encontraron alumnos para los filtros aplicados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Botón de cierre/cancelar -->
    <div class="flex justify-center mt-6">
        <a href="{{ route('panel') }}" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded">
            Volver al Panel
        </a>
    </div>
</x-app-interno-layout>
