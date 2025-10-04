<div>
    <button wire:click="openModal" class="rounded bg-blue-500 px-4 py-2 text-white">Agregar Lección</button>

    @if($showModal)
        <div class="fixed inset-0 z-50">
            <div class="flex min-h-screen items-center justify-center">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b px-5 py-3">
                        <h2 class="text-lg font-semibold">Nueva Lección</h2>
                        <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                    </div>

                    <div class="p-6">
                        <form wire:submit.prevent="save" class="space-y-4">
                            <div>
                                <label class="text-sm">Título</label>
                                <input
                                    wire:model="title"
                                    type="text"
                                    class="mt-1 w-full rounded border p-2 @error('title') border-red-500 ring-1 ring-red-500 @enderror"
                                    placeholder="Título"
                                />
                                @error('title') <span class="mt-1 block text-left text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm">Curso</label>
                                <select
                                    wire:model="course_id"
                                    class="mt-1 w-full rounded border p-2 @error('course_id') border-red-500 ring-1 ring-red-500 @enderror"
                                >
                                    <option value="">Seleccionar curso</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                                @error('course_id') <span class="mt-1 block text-left text-xs text-red-600">{{ $message }}</span> @enderror
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white">Guardar</button>
                                <button type="button" wire:click="closeModal" class="rounded px-4 py-2 text-red-500">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
