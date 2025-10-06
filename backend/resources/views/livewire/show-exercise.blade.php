<div class="container mx-auto p-4" wire:key="exercises-page-{{ $exercises->currentPage() }}">
    <div class="rounded-lg bg-white p-6 shadow">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Tipo</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Enunciado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Explicación</th> {{-- ✅ --}}
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Estado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($exercises as $e)
                        <tr wire:key="exercise-row-{{ $e->id }}">
                            <td class="px-6 py-7 whitespace-nowrap">
                                @switch($e->type)
                                    @case('mcq') Opción múltiple @break
                                    @case('true_false') Verdadero / Falso @break
                                    @case('fill_blank') Completar @break
                                @endswitch
                            </td>

                            <x-tooltip-cell :text="$e->question" />

                            {{-- Nueva columna: check si tiene explicación --}}
                            <td class="px-6 py-7 whitespace-nowrap">
                                @if(filled($e->explanation_md))
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-1 text-xs font-medium text-emerald-700">
                                        ✓ Tiene
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-500">
                                        — 
                                    </span>
                                @endif
                            </td>

                            <td class="px-6 py-7 whitespace-nowrap">
                                @php($active = $e->status === 'activo')
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($e->status) }}
                                </span>
                            </td>

                            <td class="px-6 py-7 whitespace-nowrap text-center">
                                <livewire:edit-exercise :exercise-id="$e->id" wire:key="edit-exercise-{{ $e->id }}" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-7 text-center text-gray-500" colspan="6">
                                Sin ejercicios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $exercises->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
