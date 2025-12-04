<x-app-interno-layout>
    <x-slot name="header">
        Detalle de asistencia
    </x-slot>

    <div class="space-y-6">
        {{-- Barra superior --}}
        <div class="mt-2 flex items-center justify-between">
            <div>
                <a href="{{ route('asistencias.reportes', ['subject_id' => $commission->subject_id]) }}"
                   class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    &larr; Volver a reportes
                </a>
            </div>
            <div>
                <a href="{{ route('asistencias.reportes.detalle.exportar_completo', ['commission_id' => $commission->id]) }}"
                   class="inline-flex items-center px-4 py-2 border border-green-500 rounded-md text-sm font-medium text-green-700 bg-teal-300 hover:bg-blue-50 dark:border-blue-400 dark:text-white dark:bg-gray-800 dark:hover:bg-teal-700">
                    Exportar a Excel
                </a>
            </div>
        </div>

        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="mt-2 bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    <p>
                        Cátedra: <strong>{{ $commission->subject->nombre }}</strong><br>
                        Comisión: <strong>{{ $commission->nombre }}</strong>
                    </p>
                </div>

                {{-- Planilla con fechas como columnas --}}
                <div class="overflow-x-auto">
                    <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
                        <form method="POST" action="{{ route('asistencias.reportes.detalle.guardar') }}">
                            @csrf
                            <input type="hidden" name="commission_id" value="{{ $commission->id }}">

                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-blueblack-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Legajo</th>
                                        <th class="px-3 py-2 text-left text-xs font-medium text-black uppercase tracking-wider">Apellido y Nombre</th>
                                        @foreach($fechas as $f)
                                            <th class="px-3 py-2 text-center text-xs font-medium text-black uppercase tracking-wider">
                                                {{ \Carbon\Carbon::parse($f)->format('d/m/Y') }}
                                            </th>
                                        @endforeach
                                        <th class="px-3 py-2 text-center text-xs font-medium text-black uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($commission->students as $alumno)
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $alumno->legajo }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-900 dark:text-gray-100">{{ $alumno->apellido }}, {{ $alumno->nombre }}</td>
                                            @foreach($fechas as $f)
                                                @php
                                                    $estado = $attendances->firstWhere(fn($a) =>
                                                        $a->student_id == $alumno->id && $a->fecha == $f
                                                    )?->estado;
                                                @endphp
                                                <td class="px-3 py-2 text-sm text-center text-gray-900 dark:text-gray-100">
                                                    <select name="estados[{{ $alumno->id }}][{{ $f }}]" class="border rounded">
                                                        <option value="">-</option>
                                                        <option value="P" {{ $estado === 'P' ? 'selected' : '' }}>P</option>
                                                        <option value="A" {{ $estado === 'A' ? 'selected' : '' }}>A</option>
                                                        <option value="AJ" {{ $estado === 'AJ' ? 'selected' : '' }}>AJ</option>
                                                    </select>
                                                </td>
                                            @endforeach
                                            <td class="px-3 py-2 text-sm text-center">
                                                <button type="submit"
                                                        class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                                    Guardar
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>

                {{-- Botón debajo de la planilla --}}
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('asistencias.reportes.porcentajes', [
                            'subject_id'    => $commission->subject_id,
                            'commission_id' => $commission->id,
                        ]) }}"
                       class="inline-flex items-center px-6 py-3 bg-green-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Ver porcentajes e incumplidores
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-interno-layout>
