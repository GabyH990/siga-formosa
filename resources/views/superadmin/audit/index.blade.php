{{-- resources/views/superadm/audit.blade.php --}}
<x-app-interno-layout>
    @section('title', 'Auditoría del Sistema – SIGA')

    <x-slot name="header">
        Auditoría del sistema
    </x-slot>

    <div class="space-y-6">

        {{-- Botón volver al panel --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('panel') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm
                      text-black bg-purple-500 hover:bg-purple-700">
                ← Volver al Panel
            </a>

            <span class="text-xs md:text-sm text-gray-500">
                Solo visible para <strong>Super Admin</strong>.
            </span>
        </div>

        {{-- Filtros de búsqueda --}}
        <div class="p-[1px] border-t-4 border-indigo-200 rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] mb-6">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-4">
                <form method="GET" action="{{ request()->url() }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    {{-- Fecha Desde --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                            Desde
                        </label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    </div>

                    {{-- Fecha Hasta --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                            Hasta
                        </label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    </div>

                    {{-- Buscador --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                            Buscar
                        </label>
                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                            placeholder="Usuario, acción..."
                            class="w-full rounded-md border-gray-300 shadow-sm ffocus:border-violet-500 focus:ring-violet-500 dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                    </div>

                    {{-- Botones --}}
                    <div class="flex space-x-2">
                        <button type="submit"
                            class="px-4 py-2 bg-purple-500 text-white rounded-md hover:bg-indigo-700 text-sm font-semibold transition">
                            Filtrar
                        </button>
                        <a href="{{ request()->url() }}"
                            class="px-4 py-2 bg-indigo-300 text-gray-700 rounded-md hover:bg-indigo-600 text-sm font-semibold transition text-center">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de auditoría --}}
        <div class="p-[1px] border-t-4 border-violet-200 rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-indigo-200 dark:divide-gray-700">
                        <thead class="bg-indigo-50 dark:bg-violet-950">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Acción
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Entidad
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    ID Entidad
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    IP
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-purple-950 divide-y divide-indigo-200 dark:divide-purple-300">
                            @forelse($logs as $log)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                                        {{ $log->created_at?->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                                        {{ $log->user->name ?? 'Usuario eliminado' }}
                                        <span class="text-xs text-gray-600 dark:text-gray-300">
                                            (ID: {{ $log->user_id }})
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                                        @php
                                            $editUrl = null;

                                            // Podés ir sumando entidades acá
                                            if ($log->entidad === 'Professor') {
                                                $editUrl = route('profesores.edit', $log->entidad_id);
                                            }

                                            if ($log->entidad === 'Alumno' || $log->entidad === 'Student') {
                                                $editUrl = route('alumnos.edit', $log->entidad_id);
                                            }
                                          @endphp
                                            {{ $log->accion }}    
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                                        {{ $log->entidad }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                                        {{ $log->entidad_id }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-800 dark:text-white">
                                        {{ $log->descripcion }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-800 dark:text-gray-200">
                                        {{ $log->ip_address }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                        No hay registros de auditoría.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="p-4">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-interno-layout>
