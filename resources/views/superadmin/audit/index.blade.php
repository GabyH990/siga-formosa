{{-- resources/views/superadm/audit.blade.php --}}
<x-app-interno-layout>
    <x-slot name="header">
        Auditoría del sistema
    </x-slot>

    <div class="space-y-6">

        {{-- Botón volver al panel --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('panel') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm
                      text-gray-700 bg-white hover:bg-gray-50">
                ← Volver al Panel
            </a>

            <span class="text-xs md:text-sm text-gray-500">
                Solo visible para <strong>Super Admin</strong>.
            </span>
        </div>

        {{-- Filtros de búsqueda --}}
        <div class="p-[1px] border-t-4 border-indigo-200 rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] mb-6">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4">
                <form method="GET" action="{{ request()->url() }}"
                    class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    {{-- Fecha Desde --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Desde
                        </label>
                        <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Fecha Hasta --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Hasta
                        </label>
                        <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Buscador --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Buscar
                        </label>
                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                            placeholder="Usuario, acción..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    {{-- Botones --}}
                    <div class="flex space-x-2">
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm font-semibold transition">
                            Filtrar
                        </button>
                        <a href="{{ request()->url() }}"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 text-sm font-semibold transition text-center">
                            Limpiar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de auditoría --}}
        <div class="p-[1px] border-t-4 border-violet-200 rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-indigo-200 dark:divide-gray-700">
                        <thead class="bg-indigo-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Usuario
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acción
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Entidad
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    ID Entidad
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Descripción
                                </th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    IP
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-indigo-200 dark:divide-gray-700">
                            @forelse($logs as $log)
                                <tr>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at?->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $log->user->name ?? 'Usuario eliminado' }}
                                        <span class="text-xs text-gray-400">
                                            (ID: {{ $log->user_id }})
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
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

                                        @if ($log->accion === 'editar' && $editUrl)
                                            <a href="{{ $editUrl }}" class="text-blue-600 hover:text-blue-800 underline">
                                                {{ ucfirst($log->accion) }}
                                            </a>
                                        @else
                                            {{ $log->accion }}
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $log->entidad }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                        {{ $log->entidad_id }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 dark:text-gray-200">
                                        {{ $log->descripcion }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $log->ip_address }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
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
