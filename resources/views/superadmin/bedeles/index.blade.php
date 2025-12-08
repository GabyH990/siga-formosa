<x-app-interno-layout>
    <x-slot name="header">
        Gestión de Bedeles
    </x-slot>

    <div class="space-y-6">
        <div class="flex justify-end">
            <a href="{{ route('superadmin.bedeles.create') }}"
               class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                + Nuevo Bedel
            </a>
        </div>

        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1] ">
            <div class="bg-white/60 dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-blue-500 dark:divide-gray-700">
                        <thead class="bg-white/60 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Nombre</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Correo</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Carrera Asignada</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-blue-400 dark:divide-gray-700">
                            @forelse($bedeles as $bedel)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $bedel->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $bedel->email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $bedel->career->nombre ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('superadmin.bedeles.edit', $bedel->id) }}"
                                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3 btn-confirm-editar">
                                            Editar
                                        </a>
                                        <form action="{{ route('superadmin.bedeles.destroy', $bedel->id) }}"
                                              method="POST"
                                              class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 btn-confirm-eliminar">
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
                <div class="p-4">
                    {{ $bedeles->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Botón de cierre/cancelar -->
    <div class="flex justify-center mt-6">
        <a href="{{ route('panel') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
            Volver al Panel
        </a>
    </div>

    {{-- Confirmaciones para Editar / Eliminar --}}
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
                            confirmButtonText: options.confirmText || 'Sí',
                            cancelButtonText: options.cancelText || 'Cancelar'
                        }).then((result) => {
                            if (!result.isConfirmed) return;

                            if (form && (btn.type === 'submit' || btn.getAttribute('type') === 'submit')) {
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
                icon: 'question',
                confirmText: 'Sí, editar'
            });

            // Confirmación para ELIMINAR bedel
            bindConfirm('.btn-confirm-eliminar', {
                title: '¿Eliminar este bedel?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                confirmText: 'Sí, eliminar'
            });
        });
    </script>
</x-app-interno-layout>
