<div class="container mx-auto p-4" wire:key="episodes-page-{{ $episodes->currentPage() }}">
    <div class="rounded-lg bg-white p-6 shadow">
        <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-1 flex-col gap-3 sm:flex-row">
                <div class="flex-1">
                    <label class="mb-1 block text-sm font-medium text-gray-700">Buscar</label>
                    <input
                        type="text"
                        wire:model.debounce.400ms="search"
                        class="w-full rounded border px-3 py-2"
                        placeholder="Buscar episodio…"
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
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Duración</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Cursos</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Lecciones</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($episodes as $episode)
                        <tr wire:key="episode-row-{{ $episode->id }}">
                            <td class="px-6 py-7">
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-900">{{ $episode->title }}</span>
                                    <span class="text-sm text-gray-500">{{ $episode->slug }}</span>
                                    @php
                                        $descriptionPreview = $episode->description_md
                                            ? \Illuminate\Support\Str::limit(strip_tags($episode->description_md), 80)
                                            : null;
                                    @endphp
                                    @if($descriptionPreview)
                                        <span class="mt-1 text-xs text-gray-500">{{ $descriptionPreview }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-7">
                                @php
                                    $statusClasses = [
                                        'draft'     => 'bg-gray-100 text-gray-700',
                                        'published' => 'bg-emerald-100 text-emerald-700',
                                        'private'   => 'bg-indigo-100 text-indigo-700',
                                    ];
                                    $badgeClass = $statusClasses[$episode->status] ?? 'bg-gray-100 text-gray-700';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $badgeClass }}">
                                    {{ ucfirst($episode->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap text-sm text-gray-600">
                                {{ optional($episode->published_at)->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap text-sm text-gray-600">
                                @if($episode->duration_seconds)
                                    {{ gmdate('H:i:s', $episode->duration_seconds) }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-7 text-sm text-gray-600">
                                @forelse($episode->courses as $course)
                                    <span class="mr-1 inline-flex rounded bg-slate-100 px-2 py-0.5 text-xs text-slate-700">{{ $course->name }}</span>
                                @empty
                                    <span class="text-xs text-gray-400">Sin cursos</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-7 text-sm text-gray-600">
                                @forelse($episode->lessons as $lesson)
                                    <span class="mr-1 inline-flex rounded bg-emerald-50 px-2 py-0.5 text-xs text-emerald-700">{{ $lesson->title }}</span>
                                @empty
                                    <span class="text-xs text-gray-400">Sin lecciones</span>
                                @endforelse
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap">
                                <div class="flex flex-wrap items-center gap-2">
                                    <livewire:edit-podcast-episode
                                        :podcast-id="$podcast->id"
                                        :episode-id="$episode->id"
                                        wire:key="edit-episode-{{ $episode->id }}"
                                    />

                                    <button
                                        type="button"
                                        class="rounded bg-red-500 px-3 py-1 text-sm font-semibold text-white hover:bg-red-600"
                                        onclick="if(!confirm('¿Eliminar este episodio?')) { event.stopImmediatePropagation(); return; }"
                                        wire:click="deleteEpisode({{ $episode->id }})"
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
            {{ $episodes->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
