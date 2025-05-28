<div class="flex h-screen bg-gray-100">
    {{-- Sidebar — sólo módulos --}}
    <aside class="w-64 bg-white border-r">
        <div class="p-4 flex items-center justify-center">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="h-10 w-auto text-gray-800"/>
            </a>
        </div>
        <nav class="mt-6 flex flex-col gap-2">
            <x-nav-link
                :href="route('dashboard')" 
                :active="request()->routeIs('dashboard')" 
                class="block px-4 py-2">
                {{ __('Dashboard') }}
            </x-nav-link>

            {{-- Otros módulos aquí --}}
            <x-nav-link
                :href="route('users.index')" 
                :active="request()->routeIs('users.index')" 
                class="block px-4 py-2">
                {{ __('Users') }}
            </x-nav-link>

            <x-nav-link
                class="block px-4 py-2">
                {{ __('Tareas') }}
            </x-nav-link>
        </nav>
    </aside>

    {{-- Contenido principal --}}
    <div class="flex-1 flex flex-col overflow-auto">
        {{-- Header con el dropdown de usuario --}}
        <header class="flex items-center justify-between bg-white shadow px-6 py-4">
            {{-- Título de la página --}}
            <div class="text-lg font-semibold text-gray-800">
                {{ $header ?? '' }}
            </div>

            {{-- Dropdown de usuario en el header --}}
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">
                            <div>{{ Auth::user()->name }}</div>
                            <svg class="ms-1 h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        {{-- Slot de contenido --}}
        <main class="p-6 flex-1 overflow-auto">
            {{ $slot }}
        </main>
    </div>
</div>
