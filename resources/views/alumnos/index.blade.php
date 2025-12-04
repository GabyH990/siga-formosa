<x-app-interno-layout>
    <x-slot name="header">
        Listado de Alumnos
    </x-slot>

<div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
    <div class="space-y-6">
        <!-- Actions & Filters -->
        
        <div
            class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="w-full md:w-1/2">
                <form method="GET" action="{{ route('alumnos.index') }}" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Buscar por Legajo, Apellido, DNI..."
                        class="w-full rounded-md border-gray-300 shadow-sm 
                        bg-white text-gray-800 placeholder-gray-500
                        focus:border-blue-500 focus:ring-blue-500
                        dark:bg-gray-700 dark:border-gray-600 
                        dark:text-white dark:placeholder-gray-400">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Buscar
                    </button>
                </form>
            </div>
            <div>
                <a href="{{ route('alumnos.create') }}"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    + Nuevo Alumno
                </a>
            </div>
        </div>

        <!-- Table -->
        
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-violet-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-black dark:text-gray-300 uppercase tracking-wider">
                                Legajo</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-black dark:text-gray-300 uppercase tracking-wider">
                                Apellido y Nombre</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-black dark:text-gray-300 uppercase tracking-wider">
                                DNI</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-black dark:text-gray-300 uppercase tracking-wider">
                                Carrera</th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-black dark:text-gray-300 uppercase tracking-wider">
                                Cohorte</th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-black dark:text-gray-300 uppercase tracking-wider">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($students as $student)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-gray-400">
                                    {{ $student->legajo }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-black dark:text-white">
                                    {{ $student->apellido }}, {{ $student->nombre }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-gray-400">
                                    {{ $student->dni }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-gray-400">
                                    {{ $student->career->codigo ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-black dark:text-gray-400">
                                    {{ $student->cohorte }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">

                                    {{-- Estado académico --}}
                                    <a href="{{ route('alumnos.estado', $student->id) }}"
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Estado Académico
                                    </a>

                                    {{-- Editar --}}
                                    <a href="{{ route('alumnos.edit', $student->id) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        Editar
                                    </a>

                                    {{-- Eliminar --}}
                                    <form action="{{ route('alumnos.destroy', $student->id) }}"
                                          method="POST"
                                          class="inline-block"
                                          onsubmit="return confirm('¿Seguro que deseas eliminar este alumno? Se borrarán también sus estados académicos, asistencias y relaciones con comisiones.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 ml-2">
                                            Eliminar
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                    No se encontraron alumnos.</td>
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

    <!-- Botón de cierre/cancelar -->
<div class="flex justify-center mt-6">
    <a href="{{ route('panel') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
        Volver al Panel
    </a>
</div>

</x-app-interno-layout>
