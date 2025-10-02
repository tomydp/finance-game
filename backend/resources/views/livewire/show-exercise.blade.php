<div class="container mx-auto p-4" wire:key="exercises-page-{{ $exercises->currentPage() }}">
    <div class="rounded-lg bg-white p-6 shadow">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">ID</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Curso / Lección</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Tipo</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Enunciado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 bg-white">
                    @forelse ($exercises as $e)
                        <tr wire:key="exercise-row-{{ $e->id }}">
                            <td class="px-6 py-7 whitespace-nowrap">{{ $e->id }}</td>

                                <x-tooltip-cell :text="optional($e->lesson?->course)->name . ' — ' . optional($e->lesson)->title" />

                            <td class="px-6 py-7 whitespace-nowrap">
                                @switch($e->type)
                                    @case('mcq') Opción múltiple @break
                                    @case('true_false') Verdadero / Falso @break
                                    @case('fill_blank') Completar @break
                                @endswitch
                            </td>

                            {{-- Enunciado con tooltip (mismo componente que en Cursos) --}}
                            <x-tooltip-cell :text="$e->question" />

                            <td class="px-6 py-7 whitespace-nowrap text-center">
                                {{-- mismo patrón que en Courses/Lecciones --}}
                                <livewire:edit-exercise :exercise-id="$e->id" wire:key="edit-exercise-{{ $e->id }}" />
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-7 text-center text-gray-500" colspan="5">
                                Sin ejercicios.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- MISMO paginador que usan los otros módulos --}}
        <div class="mt-4">
            {{ $exercises->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
