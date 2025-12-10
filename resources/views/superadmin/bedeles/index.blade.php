<x-app-interno-layout>
    @section('title', 'Gestion de Bedeles – SIGA')

    <x-slot name="header">
        Gestión de Bedeles
    </x-slot>

    <div class="space-y-6">
        <div class="flex justify-end">
            <a href="{{ route('superadmin.bedeles.create') }}"
                class="bg-indigo-400 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow">
                + Nuevo Bedel
            </a>
        </div>

        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white/60 dark:bg-violet-950 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-500 dark:divide-purple-400">
                        <thead class="bg-white/60 dark:bg-violet-950">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Correo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Carrera Asignada</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-800 dark:text-white uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-violet-900 divide-y divide-blue-400 dark:divide-purple-400">
                            @forelse($bedeles as $bedel)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $bedel->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                                        {{ $bedel->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-white">
                                        {{ $bedel->career->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Botón Editar con clase btn-confirm-editar --}}
                                        <a href="{{ route('superadmin.bedeles.edit', $bedel->id) }}"
                                            class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 mr-3 btn-confirm-editar">
                                            Editar
                                        </a>

                                        {{-- Formulario Eliminar sin onsubmit nativo --}}
                                        <form action="{{ route('superadmin.bedeles.destroy', $bedel->id) }}"
                                            method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            {{-- Botón Eliminar con clase btn-confirm-eliminar --}}
                                            <button type="submit"
                                                class="text-pink-600 hover:text-pink-800 dark:text-pink-500 dark:hover:text-pink-700 btn-confirm-eliminar">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                        No hay bedeles registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="p-4">
            {{ $bedeles->links() }}
        </div>
    </div>

    <div class="flex justify-center mt-6">
        <a href="{{ route('panel') }}"
            class="bg-purple-400 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded shadow">
            Volver al Panel
        </a>
    </div>

    {{-- Scripts: SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const bindConfirm = (selector, options) => {
                document.querySelectorAll(selector).forEach((btn) => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();

                        const href = btn.getAttribute('href');
                        const form = btn.closest('form');

                        Swal.fire({
                            title: options.title,
                            text: options.text,
                            icon: options.icon || 'question',
                            showCancelButton: true,
                            confirmButtonColor: options.confirmButtonColor || '#8b5cf6',
                            cancelButtonColor: '#d1d5db',
                            confirmButtonText: options.confirmText || 'Sí',
                            cancelButtonText: options.cancelText || 'Cancelar',
                            color: '#4b5563'
                        }).then((result) => {
                            if (!result.isConfirmed) return;

                            if (form && (btn.type === 'submit' || btn.getAttribute('type') ===
                                    'submit')) {
                                form.submit();
                            } else if (href) {
                                window.location.href = href;
                            }
                        });
                    });
                });
            };

            // Confirmación para EDITAR bedel
            bindConfirm('.btn-confirm-editar', {
                title: '¿Editar este bedel?',
                text: 'Vas a abrir la pantalla de edición del bedel.',
                icon: 'info',
                confirmText: 'Sí, editar'
            });

            // Confirmación para ELIMINAR bedel
            bindConfirm('.btn-confirm-eliminar', {
                title: '¿Eliminar este bedel?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                confirmText: 'Sí, eliminar',
                confirmButtonColor: '#db2777' // Rojo/Rosa para eliminar
            });
        });
    </script>
</x-app-interno-layout>