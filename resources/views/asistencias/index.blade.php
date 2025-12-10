<x-app-interno-layout>
    @section('title', 'Módulo Asistencia – SIGA')

    <x-slot name="header">
        Módulo Asistencia
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tomar Asistencia -->
        <a href="{{ route('asistencias.registros') }}"
           class="block bg-white backdrop-blur-md border border-blue-400 shadow rounded-lg p-6 hover:bg-blue-100 dark:border-blue-300 dark:bg-blue-600 dark:hover:bg-blue-400 transition border-t-4">
            <h3 class="text-xl font-semibold text-black dark:text-white">Registros</h3>
            <p class="mt-2 text-sm text-black dark:text-white">Tomar asistencia diaria.</p>
        </a>

        <!-- Reportes -->
        <a href="{{ route('asistencias.reportes') }}"
           class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 dark:border-indigo-300 dark:bg-indigo-600 dark:hover:bg-indigo-400 transition border-t-4">
            <h3 class="text-xl font-semibold text-black dark:text-white">Reportes</h3>
            <p class="mt-2 text-sm text-black dark:text-white">Ver porcentajes y cerrar cursadas.</p>
        </a>

        <!-- Armar Cursada -->
        <a href="{{ route('asistencias.armar-cursada') }}"
           class="block bg-white backdrop-blur-md border border-violet-400 shadow rounded-lg p-6 hover:bg-violet-100 dark:border-violet-300 dark:bg-violet-600 dark:hover:bg-violet-400 transition border-t-4">
            <h3 class="text-xl font-semibold text-black dark:text-white">Armar Cursada</h3>
            <p class="mt-2 text-sm text-black dark:text-white">Gestionar alumnos por comisión.</p>
        </a>
    </div>
    <div class="flex justify-center mt-6">
    <a href="{{ route('panel') }}"
       class="text-black dark:text-white bg-purple-500 hover:bg-purple-700 font-bold py-2 px-6 rounded">
        Volver al Panel
    </a>
</x-app-interno-layout>
