<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-500">
                    <a href="{{ route('backoffice.podcasts.index') }}" class="text-blue-600 hover:underline">← Volver</a>
                </p>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Episodios de {{ $podcast->title }}
                </h2>
                <p class="text-sm text-gray-500">
                    Estado: <span class="font-semibold">{{ ucfirst($podcast->status) }}</span>
                </p>
            </div>
            <livewire:create-podcast-episode :podcast="$podcast" />
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900">
                <livewire:show-podcast-episodes :podcast="$podcast" />
            </div>
        </div>
    </div>
</x-app-layout>
