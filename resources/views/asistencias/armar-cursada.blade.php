{{-- resources/views/asistencias/armar-cursada.blade.php --}}
<x-app-interno-layout>
    <x-slot name="header">
        Armar Cursada
    </x-slot>

    <div class="space-y-6">

        {{-- FORM SUPERIOR: filtros (GET) --}}
        <form method="GET" action="{{ route('asistencias.armar-cursada') }}" class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">

            {{-- Cátedra / Comisión / Profesor --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Cátedra --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Cátedra
                    </label>
                    <select
                        name="subject_id"
                        class="w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccione cátedra...</option>
                        @foreach ($subjects as $subject)
                            <option value="{{ $subject->id }}"
                                {{ (int) $subjectId === $subject->id ? 'selected' : '' }}>
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
                    <select
                        name="commission_nombre"
                        class="w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccione comisión...</option>
                        @foreach ($commissionOptions as $opt)
                            <option value="{{ $opt }}"
                                {{ $commissionNombre === $opt ? 'selected' : '' }}>
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
                    <select
                        name="professor_id"
                        class="w-full rounded-md border-gray-300 shadow-sm
                               focus:border-blue-500 focus:ring-blue-500
                               dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Sin profesor asignado</option>
                        @foreach ($professors as $prof)
                            <option value="{{ $prof->id }}"
                                {{ (int) $professorId === $prof->id ? 'selected' : '' }}>
                                {{ strtolower($prof->apellido) }}, {{ $prof->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Filtro --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Filtro (Legajo, Apellido, Nombre)
                </label>
                <input
                    type="text"
                    name="filter"
                    value="{{ $filter }}"
                    placeholder="Buscar alumno..."
                    class="w-full rounded-md border-gray-300 shadow-sm
                           focus:border-blue-500 focus:ring-blue-500
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="flex justify-start">
                <button
                    type="submit"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md
                           font-semibold text-xs text-white uppercase tracking-widest
                           hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2
                           focus:ring-offset-2 focus:ring-blue-500">
                    Actualizar listas
                </button>
            </div>
        </form>

        {{-- MENSAJE SI FALTA ELEGIR CÁTEDRA+COMISIÓN --}}
        @if (! $subjectId || ! $commissionNombre)
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 text-sm text-yellow-800 rounded">
                Seleccione una cátedra y una comisión para comenzar a armar la cursada.
            </div>
        @else
            {{-- COLUMNAS: Elegibles / Cursantes --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- =============== ELEGIBLES =============== --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
                        Elegibles
                    </h2>

                    {{-- FORM GUARDAR (AGREGAR) --}}
                    <form method="POST" action="{{ route('asistencias.armar-cursada.add') }}" class="flex flex-col h-full">
                        @csrf

                        <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                        <input type="hidden" name="commission_nombre" value="{{ $commissionNombre }}">
                        <input type="hidden" name="professor_id" value="{{ $professorId }}">
                        <input type="hidden" name="filter" value="{{ $filter }}">

                        <div class="flex-1 overflow-auto border border-gray-200 dark:border-gray-700 rounded">
                            @if ($elegibles->isEmpty())
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">
                                    No hay alumnos elegibles con los filtros actuales.
                                </div>
                            @else
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-3 py-2">
                                                <input type="checkbox"
                                                       onclick="document.querySelectorAll('.chk-elegible').forEach(c=>c.checked=this.checked)">
                                            </th>
                                            <th class="px-3 py-2 text-left">Legajo</th>
                                            <th class="px-3 py-2 text-left">Apellido y Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($elegibles as $alumno)
                                            <tr>
                                                <td class="px-3 py-2">
                                                    <input
                                                        type="checkbox"
                                                        class="chk-elegible"
                                                        name="students[]"
                                                        value="{{ $alumno->id }}">
                                                </td>
                                                <td class="px-3 py-2">{{ $alumno->legajo }}</td>
                                                <td class="px-3 py-2">
                                                    {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <div class="mt-3">
                            {{-- ESTE ES EL BOTÓN GUARDAR --}}
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md
                                       font-semibold text-xs text-white uppercase tracking-widest
                                       hover:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2
                                       focus:ring-offset-2 focus:ring-green-500">
                                Guardar cursada (agregar seleccionados)
                            </button>
                        </div>
                    </form>
                </div>

                {{-- =============== CURSANTES =============== --}}
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-4 flex flex-col">
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2">
                        Cursantes
                    </h2>

                    <form method="POST" action="{{ route('asistencias.armar-cursada.remove') }}" class="flex flex-col h-full">
                        @csrf

                        <input type="hidden" name="subject_id" value="{{ $subjectId }}">
                        <input type="hidden" name="commission_nombre" value="{{ $commissionNombre }}">
                        <input type="hidden" name="professor_id" value="{{ $professorId }}">
                        <input type="hidden" name="filter" value="{{ $filter }}">

                        <div class="flex-1 overflow-auto border border-gray-200 dark:border-gray-700 rounded">
                            @if ($cursantes->isEmpty())
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">
                                    Aún no hay alumnos cursando en esta comisión.
                                </div>
                            @else
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-3 py-2">
                                                <input type="checkbox"
                                                       onclick="document.querySelectorAll('.chk-cursante').forEach(c=>c.checked=this.checked)">
                                            </th>
                                            <th class="px-3 py-2 text-left">Legajo</th>
                                            <th class="px-3 py-2 text-left">Apellido y Nombre</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach ($cursantes as $alumno)
                                            <tr>
                                                <td class="px-3 py-2">
                                                    <input
                                                        type="checkbox"
                                                        class="chk-cursante"
                                                        name="selectedCursantes[]"
                                                        value="{{ $alumno->id }}">
                                                </td>
                                                <td class="px-3 py-2">{{ $alumno->legajo }}</td>
                                                <td class="px-3 py-2">
                                                    {{ $alumno->apellido }}, {{ $alumno->nombre }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>

                        <div class="mt-3">
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md
                                       font-semibold text-xs text-white uppercase tracking-widest
                                       hover:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2
                                       focus:ring-offset-2 focus:ring-red-500">
                                Quitar seleccionados de cursada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</x-app-interno-layout>
