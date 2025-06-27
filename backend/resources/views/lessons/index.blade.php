<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lecciones') }}
        </h2>
        <livewire:create-lesson />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="p-6 text-gray-900">
                  <livewire:show-lesson />
                </div>
        </div>
    </div>
</x-app-layout>
