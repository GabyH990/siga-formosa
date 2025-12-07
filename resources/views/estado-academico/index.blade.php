<x-app-interno-layout>
    <x-slot name="header">
        Estado Académico General
    </x-slot>

    <div class="space-y-6">

        {{-- Filtros / búsqueda --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <form method="GET" action="{{ route('estado-academico.index') }}"
                    class="flex flex-col md:flex-row gap-4 items-center md:items-end">
                    <div class="w-full md:flex-1">
                        <label class="block text-sm font-medium text-black dark:text-gray-300">
                            Buscar Alumno
                        </label>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Legajo, Apellido, Nombre, DNI..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                                  focus:border-blue-500 focus:ring-blue-500
                                  dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                    </div>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold
                                   rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Buscar
                        </button>

                        {{-- Botón Exportar --}}
                        <a href="{{ route('estado-academico.exportar', request()->query()) }}" class="inline-flex items-center px-4 py-2 border border-green-500 text-sm font-semibold
                              rounded-md text-green-700 bg-teal-300 hover:bg-blue-50
                              dark:border-blue-400 dark:text-white dark:bg-gray-800 dark:hover:bg-teal-700">
                            Exportar a Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla principal --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-200 dark:divide-gray-700 text-xs md:text-sm">
                        <thead class="bg-blue-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium text-black dark:text-gray-200">
                                    Alumno
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-black dark:text-gray-200">
                                    Legajo
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-black dark:text-gray-200">
                                    Cohorte
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-black dark:text-gray-200">
                                    Cursando
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-black dark:text-gray-200">
                                    Regulares
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-black dark:text-gray-200">
                                    Aprobadas
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-black dark:text-gray-200">
                                    Observaciones
                                </th>
                                <th class="px-4 py-2 text-left font-medium text-black dark:text-gray-200">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-blue-200 dark:divide-gray-700">
                            @forelse($students as $student)
                                <tr>
                                    {{-- Alumno --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-black dark:text-white">
                                        {{ $student->apellido }}, {{ $student->nombre }}
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($student->career)
                                                {{ $student->career->codigo }} · {{ $student->career->nombre }}
                                            @else
                                                Sin carrera asignada
                                            @endif
                                        </div>
                                    </td>

                                    {{-- Legajo --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-black dark:text-gray-200">
                                        {{ $student->legajo }}
                                    </td>

                                    {{-- Cohorte --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-black dark:text-gray-200">
                                        {{ $student->cohorte ?? '-' }}
                                    </td>

                                    {{-- Cursando --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-black dark:text-gray-200">
                                        {{ $student->cant_cursando ?? 0 }}
                                    </td>

                                    {{-- Regulares --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-black dark:text-gray-200">
                                        {{ $student->cant_regulares ?? 0 }}
                                    </td>

                                    {{-- Aprobadas --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-black dark:text-gray-200">
                                        {{ $student->cant_aprobadas ?? 0 }}
                                    </td>

                                    {{-- Observaciones --}}
                                    <td class="px-4 py-2 whitespace-nowrap text-black dark:text-gray-200">
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
                                                      text-blue-700 bg-blue-50 hover:bg-blue-100
                                                      dark:text-blue-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                                                Ver detalle
                                            </a>

                                            <a href="{{ route('alumnos.estado.editar', $student->id) }}" class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-md
                                                      text-amber-700 bg-amber-50 hover:bg-amber-100
                                                      dark:text-amber-300 dark:bg-gray-700 dark:hover:bg-gray-600">
                                                Editar
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-4 text-center text-sm text-black dark:text-gray-400">
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
        <a href="{{ route('panel') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
            Volver al Panel
        </a>
    </div>
</x-app-interno-layout>
