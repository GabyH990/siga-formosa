<x-app-interno-layout>
    <x-slot name="header">
        Mi Perfil
    </x-slot>

    <div class="max-w-3xl mx-auto bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <div class="flex flex-col items-center mb-6">
            @if($user->foto)
                <img src="{{ asset($user->foto) }}" alt="Avatar" class="w-32 h-32 rounded-full object-cover mb-4 shadow-lg">
            @else
                <div
                    class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-4xl font-bold mb-4 shadow-lg">
                    {{ substr($user->name, 0, 1) }}
                </div>
            @endif
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
            <p class="text-gray-500 dark:text-gray-400">{{ $user->role->nombre ?? 'Sin Rol' }}</p>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Correo Electrónico</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Carrera Asignada</dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $user->career->nombre ?? 'Todas / Ninguna' }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-8 flex justify-end">
            <a href="{{ route('perfil.edit') }}"
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Editar Perfil
            </a>
        </div>
    </div>
</x-app-interno-layout>