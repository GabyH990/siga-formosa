<x-app-interno-layout>
    <x-slot name="header">
        Módulo Profesores
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Listado -->
        <a href="{{ route('profesores.listado') }}"
            class="block p-6 bg-white/20 dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-blue-500">
            <h3 class="text-lg font-semibold text-wite dark:text-white mb-2">Listado de Profesores</h3>
            <p class="text-white dark:text-white text-sm">Ver todos los docentes y sus asignaciones.</p>
        </a>

        <!-- Nuevo -->
        <a href="{{ route('profesores.create') }}"
            class="block p-6 bg-white/20 dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-green-500">
            <h3 class="text-lg font-semibold text-white dark:text-white mb-2">Nuevo Profesor</h3>
            <p class="text-white dark:text-white text-sm">Registrar un nuevo docente.</p>
        </a>

        <!-- Editar (Buscador) -->
        <a href="{{ route('profesores.buscar') }}"
            class="block p-6 bg-white/20 dark:bg-gray-800 rounded-lg shadow hover:shadow-md transition duration-200 border-t-4 border-purple-500">
            <h3 class="text-lg font-semibold text-white dark:text-white mb-2">Editar Profesor</h3>
            <p class="text-white dark:text-white text-sm">Buscar y modificar datos de un docente.</p>
        </a>
    </div>

     <!-- Botón de cierre/cancelar -->
<div class="flex justify-center mt-6">
    <a href="{{ route('panel') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
        Cerrar
    </a>
</div>
</x-app-interno-layout>