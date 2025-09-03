<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase">ID</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase">Curso / Lección</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase">Tipo</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase">Enunciado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($exercises as $e)
                        <tr>
                            <td class="px-6 py-7">{{ $e->id }}</td>
                            <td class="px-6 py-7">
                                <div class="font-medium">{{ $e->lesson->course->name ?? '-' }}</div>
                                <div class="text-gray-500">{{ $e->lesson->title ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-7 uppercase text-xs font-semibold">{{ str_replace('_',' ',$e->type) }}</td>
                            <x-tooltip-cell :text="$e->question" />
                            <td class="px-6 py-7">
                                <button wire:click="editExercise({{ $e->id }})"
                                        class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition">
                                    Editar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $exercises->onEachSide(1)->links() }}
        </div>
    </div>

    <livewire:edit-exercise />
</div>
