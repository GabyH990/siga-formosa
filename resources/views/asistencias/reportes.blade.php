<x-app-interno-layout>
     @section('title', 'Reportes de Asistencia – SIGA')

    <x-slot name="header">
        Reportes de Asistencia
    </x-slot>

    <div class="space-y-6">

        {{-- Botón volver --}}
        <div>
            <a href="{{ route('asistencias.index') }}"
                class="inline-flex items-center px-4 py-2 border border-pule-300 rounded-md text-sm font-medium text-white bg-purple-500 hover:bg-purple-700">
                &larr; Volver al módulo de Asistencia
            </a>
        </div>

        {{-- Selección de cátedra --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6">
                <form method="GET" action="{{ route('asistencias.reportes') }}" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white mb-1">
                                Cátedra
                            </label>
                            <select name="subject_id" class="w-full rounded-md border-gray-300 shadow-sm
                                       focus:border-violet-500 focus:ring-violet-500
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                                <option value="">Seleccione cátedra...</option>
                                @foreach ($subjects as $subject)
                                    @php
                                        $cantidad = $subject->commissions_with_attendances_count ?? 0;
                                    @endphp
                                    <option value="{{ $subject->id }}" {{ (string) $subjectId === (string) $subject->id ? 'selected' : '' }}>
                                        {{ $subject->career->codigo ?? '' }} - {{ $subject->nombre }}
                                        @if ($cantidad > 0)
                                            ({{ $cantidad }} planillas)
                                        @else
                                            (sin registros)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-indigo-400 border border-transparent
                                   rounded-md font-semibold text-xs text-white uppercase tracking-widest
                                   hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Ver planillas
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Listado de comisiones y fechas --}}
        @if ($subjectId)
            @if ($commissions && $commissions->isEmpty())
                <div class="bg-fuchsia-50 border border-fuchsia-200 text-fuchsia-700 px-4 py-3 rounded">
                    Esta cátedra aún no tiene planillas de asistencia registradas.
                </div>
            @elseif ($commissions && $commissions->isNotEmpty())
                <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
                    <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-6 space-y-4">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                            Planillas por comisión
                        </h3>

                        <div class="space-y-4 mb-4">
                            @foreach ($commissions as $commission)
                                <div class="border border-violet-200 rounded-md p-4 flex items-center justify-between">
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white">
                                        Comisión {{ $commission->nombre }}
                                    </h4>
                                    <a href="{{ route('asistencias.reportes.detalle', ['commission_id' => $commission->id]) }}"
                                        class="inline-flex items-center px-3 py-1 bg-indigo-400 rounded-md text-sm font-medium text-white hover:bg-indigo-500">
                                        Ver detalle
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        {{-- Paginación --}}
                        <div>
                            {{ $commissions->links() }}
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
