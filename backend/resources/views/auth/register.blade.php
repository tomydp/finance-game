<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center text-sm text-gray-600 my-5">
            <div class="mb-5 sm:mb-0">
                <x-link :href="route('login')">Iniciar Sesion</x-link>
            </div>
            <div>
                <x-link :href="route('password.request')">Olvidaste tu Contraseña</x-link>
            </div>
        </div>
        <x-primary-button class="flex w-full justify-center rounded-md">
                {{ __('Register') }}
        </x-primary-button>
    </form>
    <a href="{{ route('socialite.redirect', 'google') }}"
            class="mt-2 flex w-full justify-center rounded-md bg-red-600 px-4 py-2
           font-semibold text-white hover:bg-red-700 focus:outline-none
           focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
        Registrarse con Google
    </a>
</x-guest-layout>
