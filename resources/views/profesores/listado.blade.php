<x-app-interno-layout>
    <x-slot name="header">
        Listado de Profesores
    </x-slot>

    <div class="space-y-6">

        {{-- Mensajes --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Barra superior: búsqueda + "Nuevo Profesor" --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div
                class="flex flex-col md:flex-row justify-between items-center gap-4 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="w-full md:w-2/3">
                    <form method="GET" action="{{ route('profesores.listado') }}"
                        class="flex flex-col md:flex-row gap-2 md:items-center">

                        {{-- Buscador --}}
                        <div class="flex-1 flex gap-2">
                            <input type="text" name="search" value="{{ $search }}"
                                placeholder="Buscar por Legajo, Nombre, Correo..." class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Buscar
                            </button>
                        </div>

                        {{-- Botón ver/ocultar inactivos (auto submit) --}}
                        <div class="mt-2 md:mt-0">
                            <button type="button" onclick="
                                    const form  = this.closest('form');
                                    const hidden = form.querySelector('input[name=ver_inactivos]');
                                    hidden.value = hidden.value === '1' ? '0' : '1';
                                    form.submit();
                                " class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300
                                       bg-white text-sm font-medium text-gray-700 hover:bg-gray-50
                                       dark:bg-gray-700 dark:text-gray-200 dark:border-gray-500">
                                {{ $verInactivos ? 'Ocultar inactivos' : 'Ver también profesores inactivos' }}
                            </button>

                            {{-- valor actual para que el botón pueda alternar --}}
                            <input type="hidden" name="ver_inactivos" value="{{ $verInactivos ? '1' : '0' }}">
                        </div>
                    </form>
                </div>

                <div>
                    <a href="{{ route('profesores.create') }}"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        + Nuevo Profesor
                    </a>
                </div>
            </div>
        </div>

        {{-- Tabla de profesores (similar a alumnos) --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-300 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Legajo
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Apellido y Nombre
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Correo
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Teléfono
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Título
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Asignaciones
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-blue-300 dark:divide-gray-700">
                            @forelse($professors as $professor)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $professor->legajo }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $professor->apellido }}, {{ $professor->nombre }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $professor->correo }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $professor->telefono }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $professor->titulo }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        @if($professor->subjects->isEmpty())
                                            <span class="italic text-gray-400">Sin asignaciones</span>
                                        @else
                                            <ul class="list-disc list-inside space-y-1">
                                                @foreach($professor->subjects as $subject)
                                                    <li>
                                                        {{ $subject->nombre }}
                                                        @if($subject->career)
                                                            ({{ $subject->career->codigo }})
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        @if($professor->activo)
                                            <span
                                                class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-3">
                                        @if($professor->activo)
                                            {{-- Editar --}}
                                            <a href="{{ route('profesores.edit', $professor->id) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                Editar
                                            </a>

                                            {{-- Desactivar --}}
                                            <form action="{{ route('profesores.destroy', $professor->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('¿Seguro que querés desactivar a este profesor?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                                    Desactivar
                                                </button>
                                            </form>
                                        @else
                                            {{-- Activar --}}
                                            <form action="{{ route('profesores.activar', $professor->id) }}" method="POST"
                                                class="inline-block"
                                                onsubmit="return confirm('¿Activar nuevamente a este profesor?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">
                                                    Activar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
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
    <!-- Botón de cierre/cancelar -->
    <div class="flex justify-center mt-6">
        <a href="{{ route('panel') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
            Volver al Panel
        </a>
    </div>
</x-app-interno-layout>
