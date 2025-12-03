<x-app-interno-layout>
    <x-slot name="header">
        Registros de asistencia
    </x-slot>

    <div class="space-y-6">

        {{-- Botón volver al módulo de asistencia --}}
        <div>
            <a href="{{ route('asistencias.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                &larr; Volver al módulo de Asistencia
            </a>
        </div>

        {{-- Mensajes --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Formulario de selección de cátedra / comisión / fecha --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form method="GET" action="{{ route('asistencias.registros') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Cátedra --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Cátedra
                        </label>
                        <select name="subject_id"
                                class="w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccione cátedra...</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ (string) $subjectId === (string) $subject->id ? 'selected' : '' }}>
                                    {{ $subject->career->codigo ?? '' }} - {{ $subject->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Comisión --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Comisión
                        </label>
                        <select name="commission_id"
                                class="w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-blue-500 focus:ring-blue-500
                                       dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccione comisión...</option>
                            @foreach ($commissions as $commission)
                                <option value="{{ $commission->id }}"
                                    {{ (string) $commissionId === (string) $commission->id ? 'selected' : '' }}>
                                    {{ $commission->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Fecha
                        </label>
                        <input type="date"
                               name="fecha"
                               value="{{ $fecha }}"
                               class="w-full rounded-md border-gray-300 shadow-sm
                                      focus:border-blue-500 focus:ring-blue-500
                                      dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent
                                   rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cargar planilla
                    </button>
                </div>
            </form>
        </div>

        {{-- Planilla de asistencia --}}
        @if ($subjectId && $commissionId && $fecha)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">

                @if ($students->isEmpty())
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                        No hay cursantes para esta cátedra, comisión y fecha.
                    </div>
                @else
                    <form method="POST" action="{{ route('asistencias.registros.guardar') }}">
                        @csrf

                        <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                        <input type="hidden" name="commission_id" value="{{ $commissionId }}">
                        <input type="hidden" name="fecha" value="{{ $fecha }}">

                        <div class="mb-4 text-sm text-gray-700 dark:text-gray-300">
                            <p>
                                Cátedra seleccionada:
                                <strong>{{ optional($subjects->firstWhere('id', $subjectId))->nombre }}</strong><br>
                                Comisión:
                                <strong>{{ optional($commissions->firstWhere('id', $commissionId))->nombre }}</strong><br>
                                Fecha:
                                <strong>{{ \Illuminate\Support\Carbon::parse($fecha)->format('d/m/Y') }}</strong>
                            </p>
                        </div>

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
                                @foreach ($students as $alumno)
                                    @php
                                        $att    = $attendances->get($alumno->id);
                                        $estado = $att->estado ?? 'P';
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
                                                               {{ $estado === $valor ? 'checked' : '' }}>
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

                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent
                                           rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                           hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Guardar asistencia
                            </button>

                            {{-- Botón para ver porcentajes e incumplidores --}}
                            <a href="{{ route('asistencias.reportes.porcentajes', [
                                    'subject_id'    => $subjectId,
                                    'commission_id' => $commissionId,
                                ]) }}"
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent
                                      rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                      hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Ver porcentajes e incumplidores
                            </a>

                            <a href="{{ route('asistencias.index') }}"
                               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-xs font-semibold
                                      text-gray-700 bg-white hover:bg-gray-50">
                                Volver al módulo de Asistencia
                            </a>
                        </div>
                    </form>
                @endif
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                Seleccione cátedra, comisión y fecha para tomar asistencia.
            </div>
        @endif
    </div>
</x-app-interno-layout>
