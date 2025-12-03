<x-app-interno-layout>
    <x-slot name="header">
        Resumen de asistencia e incumplidores
    </x-slot>

    <div class="space-y-6 max-w-7xl mx-auto">

        {{-- Volver a Reportes --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('asistencias.reportes', [
                    'subject_id'    => $subjectId,
                    'commission_id' => $commissionId,
                    'desde'         => $desde,
                    'hasta'         => $hasta,
                ]) }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                &larr; Volver a Reportes
            </a>

            @if ($totalClases > 0)
                <button type="button"
                        onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900">
                    Imprimir resumen
                </button>
            @endif
        </div>

        {{-- Datos generales --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 text-sm text-gray-700 dark:text-gray-300">
            <p><strong>Cátedra:</strong> {{ $subject->nombre }}</p>
            <p><strong>Comisión:</strong> {{ $commission->nombre }}</p>
            <p>
                <strong>Rango de fechas:</strong>
                @if ($desde || $hasta)
                    {{ $desde ? \Illuminate\Support\Carbon::parse($desde)->format('d/m/Y') : 'inicio' }}
                    &mdash;
                    {{ $hasta ? \Illuminate\Support\Carbon::parse($hasta)->format('d/m/Y') : 'fin' }}
                @else
                    Todas las fechas registradas
                @endif
            </p>
            <p><strong>Total de clases (fechas distintas):</strong> {{ $totalClases }}</p>
        </div>

        {{-- Tabla general de porcentajes --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Porcentaje de asistencia por alumno
            </h2>

            @if ($totalClases === 0)
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    No hay asistencias cargadas para esta comisión en el rango seleccionado.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                Legajo
                            </th>
                            <th class="px-3 py-2 text-left font-medium text-gray-500 uppercase tracking-wider">
                                Apellido y Nombre
                            </th>
                            <th class="px-3 py-2 text-right font-medium text-gray-500 uppercase tracking-wider">
                                Asistidas (P+AJ)
                            </th>
                            <th class="px-3 py-2 text-right font-medium text-gray-500 uppercase tracking-wider">
                                Total clases
                            </th>
                            <th class="px-3 py-2 text-right font-medium text-gray-500 uppercase tracking-wider">
                                %
                            </th>
                            <th class="px-3 py-2 text-center font-medium text-gray-500 uppercase tracking-wider">
                                Condición
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($resumen as $fila)
                            @php
                                $alumno     = $fila['student'];
                                $asistidas  = $fila['asistidas'];
                                $porcentaje = $fila['porcentaje'];
                                $incumple   = $fila['incumple'];
                            @endphp
                            <tr class="{{ $incumple ? 'bg-red-50 dark:bg-red-900/40' : '' }}">
                                <td class="px-3 py-2 text-gray-900 dark:text-gray-100">
                                    {{ $alumno->legajo }}
                                </td>
                                <td class="px-3 py-2 text-gray-900 dark:text-gray-100">
                                    {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    {{ $asistidas }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    {{ $fila['totalClases'] }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                    {{ number_format($porcentaje, 2) }} %
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @if ($incumple)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Incumple (&le; 75%)
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Cumple
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        {{-- Planilla de incumplidores --}}
        @if ($totalClases > 0)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        Planilla de incumplidores (P+AJ / total &le; 75%)
                    </h2>
                    <div class="flex items-center gap-2">
                        {{-- "Guardar snapshot" podría ser en el futuro: guardar este resumen en otra tabla --}}
                        <button type="button"
                                onclick="window.print()"
                                class="inline-flex items-center px-3 py-1.5 bg-gray-800 border border-transparent rounded-md text-xs font-semibold text-white hover:bg-gray-900">
                            Imprimir
                        </button>
                    </div>
                </div>

                @if ($incumplidores->isEmpty())
                    <p class="text-sm text-gray-500">
                        No hay incumplidores según el criterio de 75%.
                    </p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-red-50 dark:bg-red-900/60">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-red-900 uppercase tracking-wider">
                                    Legajo
                                </th>
                                <th class="px-3 py-2 text-left font-medium text-red-900 uppercase tracking-wider">
                                    Apellido y Nombre
                                </th>
                                <th class="px-3 py-2 text-right font-medium text-red-900 uppercase tracking-wider">
                                    Asistidas (P+AJ)
                                </th>
                                <th class="px-3 py-2 text-right font-medium text-red-900 uppercase tracking-wider">
                                    Total clases
                                </th>
                                <th class="px-3 py-2 text-right font-medium text-red-900 uppercase tracking-wider">
                                    %
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($incumplidores as $fila)
                                @php
                                    $alumno     = $fila['student'];
                                    $asistidas  = $fila['asistidas'];
                                    $porcentaje = $fila['porcentaje'];
                                @endphp
                                <tr>
                                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">
                                        {{ $alumno->legajo }}
                                    </td>
                                    <td class="px-3 py-2 text-gray-900 dark:text-gray-100">
                                        {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                    </td>
                                    <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                        {{ $asistidas }}
                                    </td>
                                    <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                        {{ $fila['totalClases'] }}
                                    </td>
                                    <td class="px-3 py-2 text-right text-gray-900 dark:text-gray-100">
                                        {{ number_format($porcentaje, 2) }} %
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @endif

    </div>
</x-app-interno-layout>
