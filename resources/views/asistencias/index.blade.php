<x-app-interno-layout>
    <x-slot name="header">
        Módulo Asistencia
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tomar Asistencia -->
        <a href="{{ route('asistencias.registros') }}"
           class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-blue-500">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Registros</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Tomar asistencia diaria.</p>
        </a>

        <!-- Reportes -->
        <a href="{{ route('asistencias.reportes') }}"
           class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-green-500">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Reportes</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Ver porcentajes y cerrar cursadas.</p>
        </a>

        <!-- Armar Cursada -->
        <a href="{{ route('asistencias.armar-cursada') }}"
           class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-purple-500">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Armar Cursada</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Gestionar alumnos por comisión.</p>
        </a>
    </div>
</x-app-interno-layout>
