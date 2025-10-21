<div>
    <button
        wire:click="openModal"
        type="button"
        class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
    >
        Nuevo Podcast
    </button>

    @if($showModal)
        <div class="fixed inset-0 z-50">
            <div class="flex min-h-screen items-center justify-center">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

                <div class="relative z-10 w-full max-w-3xl overflow-hidden rounded-lg bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b px-5 py-3">
                        <h2 class="text-lg font-semibold">Crear Podcast</h2>
                        <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                    </div>

                    <div class="max-h-[80vh] overflow-y-auto p-6">
                        <form wire:submit.prevent="save" class="space-y-5">
                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Título</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="text"
                                        wire:model="title"
                                        class="w-full rounded border p-2 @error('title') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="Podcast semanal de finanzas"
                                    />
                                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Slug</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="text"
                                        wire:model="slug"
                                        class="w-full rounded border p-2 @error('slug') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="podcast-semanal-finanzas"
                                    />
                                    @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Descripción (Markdown)</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <textarea
                                        wire:model="description_md"
                                        rows="4"
                                        class="w-full rounded border p-2 @error('description_md') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="Describe de qué trata el podcast..."
                                    ></textarea>
                                    @error('description_md') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Portada (URL)</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="text"
                                        wire:model="cover_image_url"
                                        class="w-full rounded border p-2 @error('cover_image_url') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="https://..."
                                    />
                                    @error('cover_image_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Estado</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <select
                                        wire:model="status"
                                        class="w-full rounded border p-2 @error('status') border-red-500 ring-1 ring-red-500 @enderror"
                                    >
                                        @foreach($statusOptions as $option)
                                            <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                        @endforeach
                                    </select>
                                    @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 items-start gap-3">
                                <label class="col-span-12 mt-2 text-sm sm:col-span-3">Fecha de publicación</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="datetime-local"
                                        wire:model="published_at"
                                        class="w-full rounded border p-2 @error('published_at') border-red-500 ring-1 ring-red-500 @enderror"
                                    />
                                    @error('published_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white">
                                    Guardar
                                </button>
                                <button type="button" wire:click="closeModal" class="rounded bg-gray-200 px-4 py-2">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
