<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm text-gray-600 my-5">
            <div class="mb-5 sm:mb-0">
                <x-link :href="route('register')">Crear Cuenta</x-link>
            </div>
            <div>
                <x-link :href="route('password.request')">Olvidaste tu Contraseña</x-link>
            </div>
        </div>
        
        <x-primary-button class="flex w-full justify-center rounded-md">
                {{ __('Log in') }}
        </x-primary-button>
    </form>
    <a href="{{ route('socialite.redirect', 'google') }}"
            class="mt-2 flex w-full justify-center rounded-md bg-red-600 px-4 py-2
           font-semibold text-white hover:bg-red-700 focus:outline-none
           focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        Iniciar sesión con Google
    </a>
</x-guest-layout>
