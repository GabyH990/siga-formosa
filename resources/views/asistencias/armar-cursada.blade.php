<x-app-interno-layout>
    <x-slot name="header">
        Armar Cursada
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-6">

        {{-- Barra superior --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('asistencias.index') }}"
               class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                ← Volver al módulo de Asistencia
            </a>

            @if (session('success'))
                <div class="text-sm text-green-700 bg-green-100 border border-green-300 px-3 py-2 rounded-md">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        {{-- Filtros principales (GET) --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form method="GET" action="{{ route('asistencias.armar-cursada') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {{-- Cátedra --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Cátedra
                        </label>
                        <select name="subject_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccione cátedra…</option>
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}"
                                    {{ (string) $subjectId === (string) $subject->id ? 'selected' : '' }}>
                                    {{ $subject->career->codigo ?? '' }} · {{ $subject->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Comisión --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Comisión
                        </label>
                        <select name="commission_nombre"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Seleccione comisión…</option>
                            @foreach ($commissionOptions as $opt)
                                <option value="{{ $opt }}"
                                    {{ (string) $commissionNombre === (string) $opt ? 'selected' : '' }}>
                                    Comisión {{ $opt }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Profesor --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Profesor
                        </label>
                        <select name="professor_id"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Sin profesor asignado</option>
                            @foreach ($professors as $prof)
                                <option value="{{ $prof->id }}"
                                    {{ (string) $professorId === (string) $prof->id ? 'selected' : '' }}>
                                    {{ strtolower($prof->apellido) }}, {{ $prof->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Filtro de alumnos --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Filtro (Legajo, Apellido, Nombre)
                        </label>
                        <input type="text"
                               name="filter"
                               value="{{ $filter }}"
                               placeholder="Buscar alumno…"
                               class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <div class="flex md:justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Actualizar listas
                        </button>
                    </div>
                </div>
            </form>
        </div>
        </div>

        {{-- Aviso si falta selección --}}
        @if (!$subjectId || !$commissionNombre)
            <div class="bg-cyan-50 border-l-4 border-cyan-400 p-4 text-sm text-cyan-800">
                Seleccione una cátedra y una comisión para comenzar a armar la cursada.
            </div>
        @endif

        {{-- Formulario general de guardar cursada --}}
        @if ($subjectId && $commissionNombre)
            <form method="POST" action="{{ route('asistencias.armar-cursada.guardar') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                <input type="hidden" name="commission_nombre" value="{{ $commissionNombre }}">
                <input type="hidden" name="professor_id" value="{{ $professorId }}">
                <input type="hidden" name="filter" value="{{ $filter }}">

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- ==== ELEGIBLES ==== --}}
                    <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">
                            Elegibles
                        </h3>

                        @if ($elegibles->isEmpty())
                            <p class="text-sm text-gray-500">
                                No hay alumnos elegibles con los filtros actuales.
                            </p>
                        @else
                            <div class="flex-1 overflow-auto border rounded-md">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 w-10">
                                            <input type="checkbox"
                                                   onclick="(function(cb){
                                                       const boxes = cb.closest('table').querySelectorAll('tbody input[type=checkbox]');
                                                       boxes.forEach(x => x.checked = cb.checked);
                                                   })(this)">
                                        </th>
                                        <th class="px-3 py-2 text-left">Legajo</th>
                                        <th class="px-3 py-2 text-left">Apellido y Nombre</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach ($elegibles as $alumno)
                                        <tr>
                                            <td class="px-3 py-1">
                                                <input type="checkbox" name="add_students[]" value="{{ $alumno->id }}">
                                            </td>
                                            <td class="px-3 py-1 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $alumno->legajo }}
                                            </td>
                                            <td class="px-3 py-1 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    </div>

                    {{-- ==== CURSANTES ==== --}}
                    <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-3">
                            Cursantes
                        </h3>

                        @if ($cursantes->isEmpty())
                            <p class="text-sm text-gray-500">
                                Aún no hay alumnos cursando en esta comisión.
                            </p>
                        @else
                            <div class="flex-1 overflow-auto border rounded-md">
                                <table class="min-w-full text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-3 py-2 w-10">
                                            <input type="checkbox"
                                                   onclick="(function(cb){
                                                       const boxes = cb.closest('table').querySelectorAll('tbody input[type=checkbox]');
                                                       boxes.forEach(x => x.checked = cb.checked);
                                                   })(this)">
                                        </th>
                                        <th class="px-3 py-2 text-left">Legajo</th>
                                        <th class="px-3 py-2 text-left">Apellido y Nombre</th>
                                    </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach ($cursantes as $alumno)
                                        <tr>
                                            <td class="px-3 py-1">
                                                <input type="checkbox" name="remove_students[]" value="{{ $alumno->id }}">
                                            </td>
                                            <td class="px-3 py-1 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $alumno->legajo }}
                                            </td>
                                            <td class="px-3 py-1 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    </div>
                </div>

                {{-- BOTÓN GENERAL GUARDAR ABAJO --}}
                <div class="flex justify-end">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-800 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        Guardar cursada
                    </button>
                </div>
            </form>
        @endif
    </div>
</x-app-interno-layout>