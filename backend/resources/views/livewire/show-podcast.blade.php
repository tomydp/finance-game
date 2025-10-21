<div class="container mx-auto p-4" wire:key="podcasts-page-{{ $podcasts->currentPage() }}">
    <div class="rounded-lg bg-white p-6 shadow">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-1 flex-col gap-3 sm:flex-row">
                <div class="flex-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Buscar</label>
                    <input
                        type="text"
                        wire:model.debounce.400ms="search"
                        class="w-full rounded border px-3 py-2"
                        placeholder="Buscar por título…"
                    />
                </div>
                <div class="sm:w-56">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Estado</label>
                    <select
                        wire:model="statusFilter"
                        class="w-full rounded border px-3 py-2"
                    >
                        <option value="">Todos</option>
                        @foreach($statusOptions as $option)
                            <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Título</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Estado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Publicado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Episodios</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Creado por</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($podcasts as $podcast)
                        <tr wire:key="podcast-row-{{ $podcast->id }}">
                            <td class="px-6 py-7">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-900">{{ $podcast->title }}</span>
                                    <span class="text-sm text-gray-500">{{ $podcast->slug }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-7">
                                @php
                                    $statusClasses = [
                                        'draft'     => 'bg-gray-100 text-gray-700',
                                        'scheduled' => 'bg-amber-100 text-amber-700',
                                        'published' => 'bg-emerald-100 text-emerald-700',
                                        'private'   => 'bg-indigo-100 text-indigo-700',
                                    ];
                                    $badgeClass = $statusClasses[$podcast->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst($podcast->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap text-sm text-gray-600">
                                {{ optional($podcast->published_at)->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap text-center text-sm text-gray-600">
                                {{ $podcast->episodes_count }}
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap text-sm text-gray-600">
                                {{ $podcast->creator?->name ?? '—' }}
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap">
                                <div class="flex flex-wrap items-center gap-2">
                                    <a
                                        href="{{ route('backoffice.podcasts.episodes', ['podcast' => $podcast->id]) }}"
                                        class="rounded bg-slate-200 px-3 py-1 text-sm font-semibold text-slate-700 hover:bg-slate-300"
                                    >
                                        Episodios
                                    </a>

                                    <livewire:edit-podcast :podcast-id="$podcast->id" wire:key="edit-podcast-{{ $podcast->id }}" />

                                    <button
                                        type="button"
                                        class="rounded bg-red-500 px-3 py-1 text-sm font-semibold text-white hover:bg-red-600"
                                        onclick="if(!confirm('¿Seguro que querés eliminar este podcast?')) { event.stopImmediatePropagation(); return; }"
                                        wire:click="deletePodcast({{ $podcast->id }})"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $podcasts->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
