<x-app-interno-layout>
    @section('title', 'Detalle de Asistencia – SIGA')

    <x-slot name="header">
        Detalle de Asistencia
    </x-slot>

    <div class="space-y-6">
        {{-- Barra superior --}}
        <div class="mt-2 flex items-center justify-between">
            <div>
                <a href="{{ route('asistencias.reportes', ['subject_id' => $commission->subject_id]) }}"
                   class="inline-flex items-center px-4 py-2 border border-purple-300 rounded-md text-sm font-medium text-white bg-purple-500 hover:bg-purple-700">
                    &larr; Volver a reportes
                </a>
            </div>
            <div>
                <a href="{{ route('asistencias.reportes.detalle.exportar_completo', ['commission_id' => $commission->id]) }}"
                   class="inline-flex items-center px-4 py-2 border border-indigo-500 rounded-md text-sm font-medium text-white bg-indigo-500 hover:bg-indigo-700">
                    Exportar a Excel
                </a>
            </div>
        </div>

        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="mt-2 bg-white dark:bg-violet-950 shadow rounded-lg p-6 space-y-4">
                <div class="text-sm text-gray-800 dark:text-white">
                    <p>
                        Cátedra: <strong>{{ $commission->subject->nombre }}</strong><br>
                        Comisión: <strong>{{ $commission->nombre }}</strong>
                    </p>
                </div>

                {{-- Planilla con fechas como columnas --}}
                <div class="overflow-x-auto">
                    <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
                        <form method="POST" action="{{ route('asistencias.reportes.detalle.guardar') }}">
                            @csrf
                            <input type="hidden" name="commission_id" value="{{ $commission->id }}">

                            <table class="min-w-full divide-y divide-violet-200">
                                <thead class="bg-white/20">
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
                                <tbody class="bg-white dark:bg-purple-900 divide-y divide-violet-200">
                                    @foreach($commission->students as $alumno)
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">{{ $alumno->legajo }}</td>
                                            <td class="px-3 py-2 text-sm text-gray-900 dark:text-white">{{ $alumno->apellido }}, {{ $alumno->nombre }}</td>
                                            @foreach($fechas as $f)
                                                @php
                                                    $estado = $attendances->firstWhere(fn($a) =>
                                                        $a->student_id == $alumno->id && $a->fecha == $f
                                                    )?->estado;
                                                @endphp
                                                <td class="px-3 py-2 text-sm text-center text-gray-900">
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
                                                        class="px-3 py-1 bg-indigo-400 text-white rounded hover:bg-indigo-700">
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
                       class="inline-flex items-center px-6 py-3 bg-indigo-400 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Ver porcentajes e incumplidores
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-interno-layout>
