<div class="container mx-auto p-4">
    <div class="rounded-lg bg-white p-6 shadow">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase text-white">ID</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase text-white">Curso / Lección</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase text-white">Tipo</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase text-white">Enunciado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase text-white">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($exercises as $e)
                        <tr wire:key="exercise-row-{{ $e->id }}">
                            <td class="px-6 py-7">{{ $e->id }}</td>
                            <td class="px-6 py-7">
                                <div class="font-medium">{{ $e->lesson->course->name ?? '-' }}</div>
                                <div class="text-gray-500">{{ $e->lesson->title ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-7 text-xs font-semibold uppercase">{{ str_replace('_',' ',$e->type) }}</td>
                            <x-tooltip-cell :text="$e->question" />
                            <td class="px-6 py-7 text-center">
                                <livewire:edit-exercise
                                    :exercise-id="$e->id"
                                    wire:key="edit-exercise-{{ $e->id }}"
                                />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación: forzamos nuestra vista Tailwind custom --}}
        <div class="mt-4">
            {{ $exercises->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
