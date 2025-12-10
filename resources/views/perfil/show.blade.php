<x-app-interno-layout>
    @section('title', 'Mi Perfil – SIGA')

    <x-slot name="header">
        Mi Perfil
    </x-slot>

    <div class="max-w-3xl mx-auto">
        {{-- Borde Gradiente --}}
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            
            {{-- Tarjeta Principal --}}
            <div class="bg-white dark:bg-violet-950 shadow rounded-lg p-8">
                
                {{-- Cabecera del Perfil (Foto + Nombre) --}}
                <div class="flex flex-col items-center mb-8">
                    @if($user->foto)
                        <img src="{{ asset($user->foto) }}" alt="Avatar" 
                             class="w-32 h-32 rounded-full object-cover mb-4 shadow-lg border-4 border-violet-100 dark:border-violet-800">
                    @else
                        <div class="w-32 h-32 rounded-full bg-indigo-100 dark:bg-indigo-900 border-4 border-violet-100 dark:border-violet-800 flex items-center justify-center text-indigo-600 dark:text-indigo-200 text-5xl font-bold mb-4 shadow-lg">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                    
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 mt-2">
                        {{ $user->role->nombre ?? 'Sin Rol' }}
                    </span>
                </div>

                {{-- Información Detallada --}}
                <div class="border-t border-violet-100 dark:border-violet-800 pt-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
                        
                        {{-- Correo --}}
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Correo Electrónico
                            </dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
                                {{ $user->email }}
                            </dd>
                        </div>

                        {{-- Carrera --}}
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                Carrera Asignada
                            </dt>
                            <dd class="mt-1 text-lg text-gray-900 dark:text-white font-medium">
                                {{ $user->career->nombre ?? 'Todas / Ninguna' }}
                            </dd>
                        </div>
                    </dl>
                </div>

                {{-- Botón de Acción --}}
                <div class="mt-10 flex justify-end">
                    <a href="{{ route('perfil.edit') }}" 
                       id="btn-editar-perfil"
                       class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-6 rounded-full shadow transition transform hover:scale-105">
                        Editar Perfil
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const btnEditar = document.getElementById('btn-editar-perfil');
                
                if (btnEditar) {
                    btnEditar.addEventListener('click', function (e) {
                        e.preventDefault();
                        const url = this.href;

                        Swal.fire({
                            title: "¿Editar tu perfil?",
                            text: "Vas a ingresar a la pantalla de edición de tu información personal.",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonColor: '#6366f1', // Indigo-500
                            cancelButtonColor: '#a855f7',  // Purple-500
                            confirmButtonText: "Sí, continuar",
                            cancelButtonText: "Cancelar"
                        }).then(result => {
                            if (result.isConfirmed) {
                                window.location.href = url;
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
</x-app-interno-layout>