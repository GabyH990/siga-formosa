<x-app-interno-layout>
    <x-slot name="header">
        Reportes de asistencia
    </x-slot>

    <div class="space-y-6">
        
        {{-- Botón volver --}}
        <div>
            <a href="{{ route('asistencias.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                &larr; Volver al módulo de Asistencia
            </a>
        </div>

        {{-- Selección de cátedra --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form method="GET" action="{{ route('asistencias.reportes') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                </div>

                <div class="mt-4">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent
                                   rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Ver planillas
                    </button>
                </div>
            </form>
        </div>
        </div>

        {{-- Listado de comisiones y fechas --}}
        @if ($subjectId)
            @if ($commissionsGroups->isEmpty())
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    No hay planillas de asistencia cargadas para esta cátedra.
                </div>
            @else
            <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
                    <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                        Planillas por comisión y fecha
                    </h3>

                    <div class="space-y-4">
                        @foreach ($commissionsGroups as $commissionId => $rows)
                         @php
                         $commissionName = $rows->first()->commission_nombre;
                          @endphp
                          <div class="border border-violet-300 rounded-md p-4 flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-100">
                                Comisión {{ $commissionName }}
                            </h4>
                            <a href="{{ route('asistencias.reportes.detalle', ['commission_id' => $commissionId]) }}"
                                class="inline-flex items-center px-3 py-1 rounded-md text-sm font-mediumbg-blue-600 text-white hover:bg-blue-700">
                                Ver detalle
                            </a>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
            @endif
        @else
            <div class="bg-cyan-50 border border-cyan-200 text-cyan-800 px-4 py-3 rounded">
                Seleccione una cátedra para ver las planillas disponibles.
            </div>
        @endif

    </div>
</x-app-interno-layout>
