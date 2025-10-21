<div>
    <button
        type="button"
        wire:click="openModal"
        class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600"
    >
        Nuevo Episodio
    </button>

    @if($showModal)
        <div class="fixed inset-0 z-50">
            <div class="flex min-h-screen items-center justify-center">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

                <div class="relative z-10 w-full max-w-4xl overflow-hidden rounded-lg bg-white shadow-xl">
                    <div class="flex items-center justify-between border-b px-5 py-3">
                        <h2 class="text-lg font-semibold">Crear episodio — {{ $podcast->title }}</h2>
                        <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                    </div>

                    <div class="max-h-[80vh] overflow-y-auto p-6">
                        <form wire:submit.prevent="save" class="space-y-5">
                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Título</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="text"
                                        wire:model="title"
                                        class="w-full rounded border p-2 @error('title') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="Lección 1: Introducción"
                                    />
                                    @error('title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Slug</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="text"
                                        wire:model="slug"
                                        class="w-full rounded border p-2 @error('slug') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="introduccion"
                                    />
                                    @error('slug') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Resumen</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <textarea
                                        wire:model="summary"
                                        rows="3"
                                        class="w-full rounded border p-2 @error('summary') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="Descripción breve del episodio…"
                                    ></textarea>
                                    @error('summary') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Descripción (Markdown)</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <textarea
                                        wire:model="description_md"
                                        rows="5"
                                        class="w-full rounded border p-2 @error('description_md') border-red-500 ring-1 ring-red-500 @enderror"
                                    ></textarea>
                                    @error('description_md') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Transcripción (Markdown)</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <textarea
                                        wire:model="transcript_md"
                                        rows="5"
                                        class="w-full rounded border p-2 @error('transcript_md') border-red-500 ring-1 ring-red-500 @enderror"
                                    ></textarea>
                                    @error('transcript_md') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Audio URL</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="text"
                                        wire:model="audio_url"
                                        class="w-full rounded border p-2 @error('audio_url') border-red-500 ring-1 ring-red-500 @enderror"
                                        placeholder="https://…"
                                    />
                                    @error('audio_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Duración (segundos)</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="number"
                                        min="0"
                                        wire:model="duration_seconds"
                                        class="w-full rounded border p-2 @error('duration_seconds') border-red-500 ring-1 ring-red-500 @enderror"
                                    />
                                    @error('duration_seconds') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Estado</label>
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

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Fecha publicación</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="datetime-local"
                                        wire:model="published_at"
                                        class="w-full rounded border p-2 @error('published_at') border-red-500 ring-1 ring-red-500 @enderror"
                                    />
                                    @error('published_at') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Programar para</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <input
                                        type="datetime-local"
                                        wire:model="scheduled_for"
                                        class="w-full rounded border p-2 @error('scheduled_for') border-red-500 ring-1 ring-red-500 @enderror"
                                    />
                                    @error('scheduled_for') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Cursos vinculados</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <select
                                        multiple
                                        wire:model="selectedCourses"
                                        class="w-full rounded border p-2 @error('selectedCourses') border-red-500 ring-1 ring-red-500 @enderror"
                                    >
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedCourses') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-12 gap-3">
                                <label class="col-span-12 text-sm sm:col-span-3">Lecciones vinculadas</label>
                                <div class="col-span-12 sm:col-span-9">
                                    <select
                                        multiple
                                        wire:model="selectedLessons"
                                        class="w-full rounded border p-2 @error('selectedLessons') border-red-500 ring-1 ring-red-500 @enderror"
                                    >
                                        @foreach($lessons as $lesson)
                                            <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('selectedLessons') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white">
                                    Guardar episodio
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
