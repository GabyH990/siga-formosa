<x-app-interno-layout>
    <x-slot name="header">
        Módulo Profesores
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Listado -->
        <a href="{{ route('profesores.listado') }}"
            class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-blue-500">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Listado de Profesores</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Ver todos los docentes y sus asignaciones.</p>
        </a>

        <!-- Nuevo -->
        <a href="{{ route('profesores.create') }}"
            class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-green-500">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Nuevo Profesor</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Registrar un nuevo docente.</p>
        </a>

        <!-- Editar (Buscador) -->
        <a href="{{ route('profesores.buscar') }}"
            class="block p-6 bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-purple-500">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Editar Profesor</h3>
            <p class="text-gray-600 dark:text-gray-400 text-sm">Buscar y modificar datos de un docente.</p>
        </a>
    </div>
</x-app-interno-layout>