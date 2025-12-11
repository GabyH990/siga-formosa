<x-app-interno-layout>
    @section('title', 'Nuevo Alumno – SIGA')

    <x-slot name="header">
        Nuevo Alumno
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="p-[1px] rounded-lg bg-gradient-to-r from-[#ca98f5] to-[#6daff1]">
            
            <div class="bg-white dark:bg-violet-950 rounded-lg p-6 shadow">
                
                {{-- ID del formulario para el script --}}
                <form id="form-create-alumno" method="POST" action="{{ route('alumnos.store') }}" 
                      x-data="{ dirty: false }" x-on:change="dirty = true">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Legajo <span class="text-violet-400">*</span></label>
                            <input type="text" name="legajo" value="{{ old('legajo') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('legajo') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">DNI <span class="text-violet-400">*</span></label>
                            <input type="text" name="dni" value="{{ old('dni') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('dni') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Nombre <span class="text-violet-400">*</span></label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('nombre') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Apellido <span class="text-violet-400">*</span></label>
                            <input type="text" name="apellido" value="{{ old('apellido') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('apellido') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Fecha Nacimiento <span class="text-violet-400">*</span></label>
                            <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('fecha_nacimiento') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Cohorte (Año) <span class="text-violet-400">*</span></label>
                            <input type="number" name="cohorte" value="{{ old('cohorte', date('Y')) }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('cohorte') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Carrera <span class="text-violet-400">*</span></label>
                            <select name="career_id" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                                <option value="">Seleccione Carrera</option>
                                @foreach($careers as $career)
                                    <option value="{{ $career->id }}" {{ old('career_id') == $career->id ? 'selected' : '' }}>
                                        {{ $career->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('career_id') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Correo <span class="text-violet-400">*</span></label>
                            <input type="email" name="correo" value="{{ old('correo') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('correo') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Teléfono <span class="text-violet-400">*</span></label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('telefono') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-900 dark:text-white">Dirección <span class="text-violet-400">*</span></label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}" required
                                class="mt-1 block w-full rounded-md border-violet-300 shadow-sm 
                                       focus:border-violet-500 focus:ring-violet-500 
                                       invalid:border-violet-400 invalid:text-violet-600 focus:invalid:border-violet-500 focus:invalid:ring-violet-400
                                       dark:bg-indigo-300 dark:border-violet-600 dark:text-black">
                            @error('direccion') <span class="text-violet-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('alumnos.index') }}"
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition">
                            Cancelar
                        </a>
                        
                        {{-- Botón TIPO BUTTON (Importante para que el JS intercepte) --}}
                        <button type="button" id="btn-guardar"
                            class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Script de Validación y SweetAlert --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('form-create-alumno');
                const btnGuardar = document.getElementById('btn-guardar');

                if (!form || !btnGuardar) return;

                btnGuardar.addEventListener('click', (e) => {
                    e.preventDefault();

                    // 1) VALIDACIÓN HTML5:
                    if (!form.checkValidity()) {
                        form.reportValidity();
                        return;
                    }

                    // 2) Preparar datos para el resumen
                    const legajo   = form.legajo.value || '-';
                    const dni      = form.dni.value || '-';
                    const nombre   = form.nombre.value || '-';
                    const apellido = form.apellido.value || '-';
                    const fechaNac = form.fecha_nacimiento.value || '-';
                    const cohorte  = form.cohorte.value || '-';
                    const correo   = form.correo.value || '-';
                    const telefono = form.telefono.value || '-';
                    const direccion = form.direccion.value || '-';

                    // Texto del select de carrera
                    const careerSelect = form.career_id;
                    const carreraTexto = (careerSelect.selectedIndex >= 0 && careerSelect.value !== "") 
                        ? careerSelect.options[careerSelect.selectedIndex].text 
                        : '-';

                    // 3) Mostrar SweetAlert
                    Swal.fire({
                        title: '¿Confirmar registro?',
                        html: `
                            <div style="text-align:left; font-size: 0.9em; line-height: 1.5;">
                                <p><strong>Legajo:</strong> ${legajo}</p>
                                <p><strong>DNI:</strong> ${dni}</p>
                                <p><strong>Alumno:</strong> ${apellido}, ${nombre}</p>
                                <p><strong>Fecha Nac.:</strong> ${fechaNac}</p>
                                <p><strong>Carrera:</strong> ${carreraTexto}</p>
                                <p><strong>Correo:</strong> ${correo}</p>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#6366f1', // Indigo
                        cancelButtonColor: '#a855f7',  // Purple
                        confirmButtonText: 'Sí, guardar',
                        cancelButtonText: 'Revisar',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush

</x-app-interno-layout>