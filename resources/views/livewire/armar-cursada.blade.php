{{-- resources/views/livewire/asistencias/armar-cursada.blade.php --}}
<div class="space-y-6">

    {{-- Fila superior: cátedra, comisión, profesor, filtro --}}
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Cátedra --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Cátedra
                </label>
                <select
                    wire:model="subjectId"
                    class="w-full rounded-md border-gray-300 shadow-sm
                           focus:border-blue-500 focus:ring-blue-500
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Seleccione cátedra...</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">
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
                    wire:model="commissionNombre"
                    class="w-full rounded-md border-gray-300 shadow-sm
                           focus:border-blue-500 focus:ring-blue-500
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Seleccione comisión...</option>
                    @foreach($commissionOptions as $opt)
                        <option value="{{ $opt }}">Comisión {{ $opt }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Profesor --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Profesor
                </label>
                <select
                    wire:model="professorId"
                    class="w-full rounded-md border-gray-300 shadow-sm
                           focus:border-blue-500 focus:ring-blue-500
                           dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Sin profesor asignado</option>
                    @foreach($professors as $prof)
                        <option value="{{ $prof->id }}">
                            {{ $prof->apellido }}, {{ $prof->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Filtro --}}
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Filtro (Legajo, Apellido, Nombre)
            </label>
            <input type="text"
                   wire:model.debounce.500ms="filter"
                   class="w-full rounded-md border-gray-300 shadow-sm
                          focus:border-blue-500 focus:ring-blue-500
                          dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                   placeholder="Buscar alumno...">
        </div>
    </div>

    {{-- Si falta elegir materia o comisión --}}
    @if(!$subjectId || !$commissionNombre)
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 text-sm text-gray-600 dark:text-gray-300">
            Seleccione una cátedra y una comisión para comenzar a armar la cursada.
        </div>
    @else
        {{-- Grillas de Elegibles y Cursantes --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Elegibles --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        Elegibles
                    </h3>

                    @if(empty($elegibles))
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            No hay alumnos elegibles con los filtros actuales.
                        </p>
                    @else
                        <div class="border border-gray-200 dark:border-gray-700 rounded-md max-h-80 overflow-y-auto">
                            <table class="min-w-full text-xs">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-2 py-1 w-6"></th>
                                        <th class="px-2 py-1 text-left text-gray-700 dark:text-gray-200">
                                            Legajo
                                        </th>
                                        <th class="px-2 py-1 text-left text-gray-700 dark:text-gray-200">
                                            Apellido y Nombre
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($elegibles as $alumno)
                                        <tr>
                                            <td class="px-2 py-1">
                                                <input type="checkbox"
                                                       wire:model="selectedElegibles"
                                                       value="{{ $alumno->id }}">
                                            </td>
                                            <td class="px-2 py-1">{{ $alumno->legajo }}</td>
                                            <td class="px-2 py-1">{{ $alumno->apellido }}, {{ $alumno->nombre }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Cursantes --}}
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">
                        Cursantes
                    </h3>

                    @if(empty($cursantes))
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Aún no hay alumnos cursando en esta comisión.
                        </p>
                    @else
                        <div class="border border-gray-200 dark:border-gray-700 rounded-md max-h-80 overflow-y-auto">
                            <table class="min-w-full text-xs">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-2 py-1 w-6"></th>
                                        <th class="px-2 py-1 text-left text-gray-700 dark:text-gray-200">
                                            Legajo
                                        </th>
                                        <th class="px-2 py-1 text-left text-gray-700 dark:text-gray-200">
                                            Apellido y Nombre
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($cursantes as $alumno)
                                        <tr>
                                            <td class="px-2 py-1">
                                                <input type="checkbox"
                                                       wire:model="selectedCursantes"
                                                       value="{{ $alumno->id }}">
                                            </td>
                                            <td class="px-2 py-1">{{ $alumno->legajo }}</td>
                                            <td class="px-2 py-1">{{ $alumno->apellido }}, {{ $alumno->nombre }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Botones --}}
            <div class="mt-4 flex flex-wrap gap-3">
                <button type="button"
                        wire:click="addSelected"
                        class="px-4 py-2 rounded-md text-xs font-semibold text-white bg-green-600 hover:bg-green-700">
                    Agregar seleccionados como cursantes
                </button>

                <button type="button"
                        wire:click="removeSelected"
                        class="px-4 py-2 rounded-md text-xs font-semibold text-white bg-red-600 hover:bg-red-700">
                    Quitar seleccionados de cursada
                </button>
            </div>
        </div>
    @endif
</div>
