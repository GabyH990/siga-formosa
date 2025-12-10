<x-app-interno-layout>
    @section('title', 'Resumen de Asistencia e Incumplidores – SIGA')
    
    <x-slot name="header">
        Resumen de Asistencia e Incumplidores
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
               class="inline-flex items-center px-4 py-2 border border-purple-300 rounded-md text-sm font-medium text-white bg-purple-500 hover:bg-purple-700">
                &larr; Volver a Reportes
            </a>

            @if ($totalClases > 0)
                <button type="button"
                        onclick="window.print()"
                        class="inline-flex items-center px-4 py-2 bg-indigo-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Imprimir resumen
                </button>
            @endif
        </div>

        {{-- Datos generales --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
        <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-4 text-sm text-gray-900 dark:text-white">
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
        </div>

        {{-- Tabla general de porcentajes --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
        <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white mb-4">
                Porcentaje de asistencia por alumno
            </h2>

            @if ($totalClases === 0)
                <div class="bg-fuchsia-50 border border-fuchsia-200 text-fuchsia-800 px-4 py-3 rounded">
                    No hay asistencias cargadas para esta comisión en el rango seleccionado.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-violet-200 text-sm">
                        <thead class="bg-purple-100 dark:bg-purple-900">
                        <tr>
                            <th class="px-3 py-2 text-left font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                Legajo
                            </th>
                            <th class="px-3 py-2 text-left font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                Apellido y Nombre
                            </th>
                            <th class="px-3 py-2 text-right font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                Asistidas (P+AJ)
                            </th>
                            <th class="px-3 py-2 text-right font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                Total clases
                            </th>
                            <th class="px-3 py-2 text-right font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                %
                            </th>
                            <th class="px-3 py-2 text-center font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                Condición
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-purple-900 divide-y divide-gray-200">
                        @foreach ($resumen as $fila)
                            @php
                                $alumno     = $fila['student'];
                                $asistidas  = $fila['asistidas'];
                                $porcentaje = $fila['porcentaje'];
                                $incumple   = $fila['incumple'];
                            @endphp
                            <tr class="{{ $incumple ? 'bg-pink-50 dark:bg-pink-900/40' : '' }}">
                                <td class="px-3 py-2 text-gray-900 dark:text-white">
                                    {{ $alumno->legajo }}
                                </td>
                                <td class="px-3 py-2 text-gray-900 dark:text-white">
                                    {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-white">
                                    {{ $asistidas }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-white">
                                    {{ $fila['totalClases'] }}
                                </td>
                                <td class="px-3 py-2 text-right text-gray-900 dark:text-white">
                                    {{ number_format($porcentaje, 2) }} %
                                </td>
                                <td class="px-3 py-2 text-center">
                                    @if ($incumple)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-pink-100 text-pink-800">
                                            Incumple (&le; 75%)
                                        </span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-cyan-100 text-cyan-800">
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
        </div>

        {{-- Planilla de incumplidores --}}
        @if ($totalClases > 0)
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        Planilla de incumplidores (P+AJ / total &le; 75%)
                    </h2>
                    <div class="flex items-center gap-2">
                        {{-- "Guardar snapshot" podría ser en el futuro: guardar este resumen en otra tabla --}}
                        <button type="button"
                                onclick="window.print()"
                                class="inline-flex items-center px-3 py-1.5 bg-indigo-400 border border-transparent rounded-md text-xs font-semibold text-white hover:bg-indigo-700">
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
                        <table class="min-w-full divide-y divide-violet-200 text-sm">
                            <thead class="bg-pink-50 dark:bg-purple-900/60">
                            <tr>
                                <th class="px-3 py-2 text-left font-medium text-gray-900 dark:text-white uppercase tracking-wider">
                                    Legajo
                                </th>
                                <th class="px-3 py-2 text-left font-medium text-gray-900 dark:text-white uppercase tracking-wider">
                                    Apellido y Nombre
                                </th>
                                <th class="px-3 py-2 text-right font-medium text-gray-900 dark:text-white uppercase tracking-wider">
                                    Asistidas (P+AJ)
                                </th>
                                <th class="px-3 py-2 text-right font-medium text-gray-900 dark:text-white uppercase tracking-wider">
                                    Total clases
                                </th>
                                <th class="px-3 py-2 text-right font-medium text-gray-900 dark:text-white uppercase tracking-wider">
                                    %
                                </th>
                            </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-purple-800 divide-y divide-purple-200">
                            @foreach ($incumplidores as $fila)
                                @php
                                    $alumno     = $fila['student'];
                                    $asistidas  = $fila['asistidas'];
                                    $porcentaje = $fila['porcentaje'];
                                @endphp
                                <tr>
                                    <td class="px-3 py-2 text-gray-900 dark:text-white">
                                        {{ $alumno->legajo }}
                                    </td>
                                    <td class="px-3 py-2 text-gray-900 dark:text-white">
                                        {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                    </td>
                                    <td class="px-3 py-2 text-right text-gray-900 dark:text-white">
                                        {{ $asistidas }}
                                    </td>
                                    <td class="px-3 py-2 text-right text-gray-900 dark:text-white">
                                        {{ $fila['totalClases'] }}
                                    </td>
                                    <td class="px-3 py-2 text-right text-gray-900 dark:text-white">
                                        {{ number_format($porcentaje, 2) }} %
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        @endif

    </div>
</x-app-interno-layout>
