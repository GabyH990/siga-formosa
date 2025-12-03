<x-app-interno-layout>
    <x-slot name="header">
        Módulo Asistencia
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Tomar Asistencia -->
        <a href="{{ route('asistencias.registros') }}"
           class="block bg-white backdrop-blur-md border border-blue-400 shadow rounded-lg p-6 hover:bg-blue-100 transition border-t-4">
            <h3 class="text-xl font-semibold text-black">Registros</h3>
            <p class="mt-2 text-sm text-black">Tomar asistencia diaria.</p>
        </a>

        <!-- Reportes -->
        <a href="{{ route('asistencias.reportes') }}"
           class="block bg-white backdrop-blur-md border border-indigo-400 shadow rounded-lg p-6 hover:bg-indigo-100 transition border-t-4">
            <h3 class="text-xl font-semibold text-black">Reportes</h3>
            <p class="mt-2 text-sm text-black">Ver porcentajes y cerrar cursadas.</p>
        </a>

        <!-- Armar Cursada -->
        <a href="{{ route('asistencias.armar-cursada') }}"
           class="block bg-white backdrop-blur-md border border-violet-400 shadow rounded-lg p-6 hover:bg-violet-100 transition border-t-4">
            <h3 class="text-xl font-semibold text-black">Armar Cursada</h3>
            <p class="mt-2 text-sm text-black">Gestionar alumnos por comisión.</p>
        </a>
    </div>
    <div class="flex justify-center mt-6">
    <a href="{{ route('panel') }}"
       class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
        Volver al Panel
    </a>
</x-app-interno-layout>
