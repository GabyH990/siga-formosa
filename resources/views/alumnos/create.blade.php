<x-app-interno-layout>
    <x-slot name="header">
        Nuevo Alumno
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="rounded-lg p-[2px] bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            <div class="bg-white dark:bg-white rounded-lg p-6 shadow">
                {{-- IMPORTANTE: id para el JS --}}
                <form id="form-create-alumno" method="POST" action="{{ route('alumnos.store') }}" x-on:change="dirty = true">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Legajo -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">Legajo</label>
                            <input type="text" name="legajo" value="{{ old('legajo') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 text-gray-500 dark:border-gray-600 dark:text-white">
                            @error('legajo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- DNI -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">DNI</label>
                            <input type="text" name="dni" value="{{ old('dni') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                            @error('dni') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">Nombre</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                            @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Apellido -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">Apellido</label>
                            <input type="text" name="apellido" value="{{ old('apellido') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                            @error('apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Fecha Nacimiento -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">Fecha Nacimiento</label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                            @error('fecha_nacimiento') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Cohorte -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">Cohorte (Año)</label>
                            <input type="number" name="cohorte" value="{{ old('cohorte', date('Y')) }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                            @error('cohorte') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Carrera -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">Carrera</label>
                            <select name="career_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-white text-gray-700 focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Seleccione Carrera</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ old('career_id') == $career->id ? 'selected' : '' }}>
                                        {{ $career->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('career_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Correo -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">Correo</label>
                            <input type="email" name="correo" value="{{ old('correo') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                            @error('correo') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label class="block text-sm font-medium text-black dark:text-white">Teléfono</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                            @error('telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-black dark:text-white">Dirección</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 text-gray-600 dark:text-white">
                            @error('direccion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('alumnos.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                            Cancelar
                        </a>

                        {{-- Botón que dispara validación + confirmación --}}
                        <button type="button" id="btn-guardar"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('form-create-alumno');
            const btnGuardar = document.getElementById('btn-guardar');

            if (!form || !btnGuardar) return;

            btnGuardar.addEventListener('click', () => {
                // 1) Validación HTML5 primero (required, tipo email, etc.)
                if (typeof form.reportValidity === 'function') {
                    if (!form.reportValidity()) {
                        return; // si falla, no sigue a la confirmación
                    }
                } else if (!form.checkValidity()) {
                    return;
                }

                // 2) Si el form es válido en el cliente, armamos el resumen
                const legajo   = form.legajo.value || '';
                const dni      = form.dni.value || '';
                const nombre   = form.nombre.value || '';
                const apellido = form.apellido.value || '';
                const fechaNac = form.fecha_nacimiento.value || '';
                const cohorte  = form.cohorte.value || '';

                const careerSelect = form.career_id;
                const carreraTexto = (careerSelect && careerSelect.value)
                    ? careerSelect.options[careerSelect.selectedIndex].text
                    : '';

                const correo    = form.correo.value || '';
                const telefono  = form.telefono.value || '';
                const direccion = form.direccion.value || '';

                Swal.fire({
                    title: '¿Confirmar registro del alumno?',
                    html: `
                        <div style="text-align:left">
                            <p><strong>Legajo:</strong> ${legajo}</p>
                            <p><strong>DNI:</strong> ${dni}</p>
                            <p><strong>Nombre:</strong> ${apellido}, ${nombre}</p>
                            <p><strong>Fecha de Nacimiento:</strong> ${fechaNac || '—'}</p>
                            <p><strong>Cohorte:</strong> ${cohorte}</p>
                            <p><strong>Carrera:</strong> ${carreraTexto || '—'}</p>
                            <p><strong>Correo:</strong> ${correo || '—'}</p>
                            <p><strong>Teléfono:</strong> ${telefono || '—'}</p>
                            <p><strong>Dirección:</strong> ${direccion || '—'}</p>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Revisar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // acá va a store(), se corre la validación de Laravel
                    }
                });
            });
        });
    </script>
</x-app-interno-layout>
