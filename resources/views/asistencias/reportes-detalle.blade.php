<x-app-interno-layout>
    <x-slot name="header">
        Detalle de asistencia
    </x-slot>

    <div class="space-y-6">

        {{-- Barra superior --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('asistencias.reportes', ['subject_id' => $commission->subject_id]) }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                &larr; Volver a reportes
            </a>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded text-sm">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
            <div class="text-sm text-gray-700 dark:text-gray-300">
                <p>
                    Cátedra: <strong>{{ $commission->subject->nombre }}</strong><br>
                    Comisión: <strong>{{ $commission->nombre }}</strong><br>
                    Fecha: <strong>{{ \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') }}</strong>
                </p>
            </div>

            {{-- Filtro + exportar --}}
            <div class="flex items-center justify-between">
                <form method="GET" action="{{ route('asistencias.reportes.detalle.exportar') }}" class="flex items-center space-x-2">
                    <input type="hidden" name="commission_id" value="{{ $commission->id }}">
                    <input type="hidden" name="fecha" value="{{ $fecha }}">

                    <label class="text-xs text-gray-600">Estado:</label>
                    <select name="estado"
                            class="rounded-md border-gray-300 text-xs
                                   focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="P">P</option>
                        <option value="A">A</option>
                        <option value="AJ">AJ</option>
                    </select>

                    <button type="submit"
                            class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent
                                   rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Exportar a Excel
                    </button>
                </form>
            </div>

            {{-- Planilla --}}
            <form method="POST" action="{{ route('asistencias.reportes.detalle.guardar') }}">
                @csrf

                <input type="hidden" name="commission_id" value="{{ $commission->id }}">
                <input type="hidden" name="fecha" value="{{ $fecha }}">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Legajo
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Apellido y Nombre
                            </th>
                            <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($attendances as $att)
                            @php
                                $alumno = $att->student;
                            @endphp
                            <tr>
                                <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $alumno->legajo }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center space-x-4">
                                        @foreach (['P' => 'P', 'A' => 'A', 'AJ' => 'AJ'] as $valor => $label)
                                            <label class="inline-flex items-center space-x-1">
                                                <input type="radio"
                                                       name="estados[{{ $alumno->id }}]"
                                                       value="{{ $valor }}"
                                                       {{ $att->estado === $valor ? 'checked' : '' }}>
                                                <span>{{ $label }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent
                                   rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

    </div>
</x-app-interno-layout>
