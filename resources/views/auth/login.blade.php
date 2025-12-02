<x-guest-layout>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- CONTENEDOR DEL LOGIN -->
    <div class="bg-white/10 backdrop-blur-lg p-8 rounded-2xl shadow-xl border border-white/20 max-w-md mx-auto mt-10">

        <h2 class="text-3xl font-semibold text-center text-white mb-6 drop-shadow">
            Iniciar Sesión
        </h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label class="text-white" for="email" :value="__('Correo electrónico')" />
                
                <x-text-input
                    id="email"
                    class="block mt-1 w-full text-black"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                />
                
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label class="text-white" for="password" :value="__('Contraseña')" />

                <x-text-input
                    id="password"
                    class="block mt-1 w-full text-black"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center text-white">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded bg-gray-800 border-gray-600 text-blue-500 focus:ring-blue-400"
                        name="remember"
                    >
                    <span class="ms-2 text-sm">Recordarme</span>
                </label>
            </div>

            <!-- Footer: Olvidé mi contraseña + botón -->
            <div class="flex items-center justify-between mt-6">


                <x-primary-button class="ms-3 bg-blue-600 hover:bg-blue-700">
                    {{ __('Ingresar') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</x-guest-layout>
