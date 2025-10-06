<div>
    <button wire:click="openModal" type="button" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">
        Agregar Curso
    </button>

    @if($showModal)
        <div class="fixed inset-0 z-50">
            <div class="flex min-h-screen items-center justify-center">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b px-5 py-3">
                        <h2 class="text-lg font-semibold">Nuevo Curso</h2>
                        <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                    </div>

                    <div class="p-6">
                        <form wire:submit.prevent="save" class="space-y-5">
                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Nombre</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="text"
                                        wire:model="name"
                                        class="w-full rounded border p-2 @error('name') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="Nombre"
                                    />
                                    @error('name') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Descripción</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <textarea
                                        wire:model="description"
                                        rows="4"
                                        class="w-full rounded border p-2 @error('description') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="Descripción"
                                    ></textarea>
                                    @error('description') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Dificultad</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <select
                                        wire:model="difficulty"
                                        class="w-full rounded border p-2 @error('difficulty') border-red-500 ring-1 ring-red-500 @enderror"
                                    >
                                        <option value="" @selected($difficulty===null)>Seleccionar dificultad</option>
                                        <option value="facil">Fácil</option>
                                        <option value="medio">Medio</option>
                                        <option value="dificil">Difícil</option>
                                    </select>
                                    @error('difficulty') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white">Guardar</button>
                                <button type="button" wire:click="closeModal" class="rounded bg-gray-200 px-4 py-2">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
