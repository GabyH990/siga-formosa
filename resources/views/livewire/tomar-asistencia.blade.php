<x-app-interno-layout>
    <x-slot name="header">
        Tomar asistencia
    </x-slot>

    <div class="space-y-6">
        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cátedra</label>
                    <select wire:model.live="subject_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="">Seleccione Cátedra</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comisión</label>
                    <select wire:model.live="commission_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        {{ !$subject_id ? 'disabled' : '' }}>
                        <option value="">Seleccione Comisión</option>
                        @foreach($commissions as $commission)
                            <option value="{{ $commission->id }}">{{ $commission->nombre }} ({{ $commission->anio }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha</label>
                    <input type="date" wire:model.live="fecha"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>
        </div>

        @if($commission_id)
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Listado de Alumnos</h3>
                    <div class="flex gap-2">
                        <a href="{{ route('asistencias.armar-cursada', ['subject_id' => $subject_id, 'commission_id' => $commission_id]) }}"
                            class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            Ir a Armar Cursada
                        </a>
                        <button wire:click="save" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar Asistencia
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Legajo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Alumno</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Presente</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Ausente</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Aus. Justif.</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($students as $student)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $student->legajo }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $student->apellido }}, {{ $student->nombre }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="radio" wire:model="attendanceData.{{ $student->id }}" value="P"
                                            class="text-green-600 focus:ring-green-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="radio" wire:model="attendanceData.{{ $student->id }}" value="A"
                                            class="text-red-600 focus:ring-red-500">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <input type="radio" wire:model="attendanceData.{{ $student->id }}" value="AJ"
                                            class="text-yellow-600 focus:ring-yellow-500">
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No
                                        hay alumnos en esta comisión.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 dark:text-gray-400">Seleccione una Cátedra y Comisión para comenzar.</p>
            </div>
        @endif
    </div>
</x-app-interno-layout>
