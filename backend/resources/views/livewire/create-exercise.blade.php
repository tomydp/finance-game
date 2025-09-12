<div>
    <button wire:click="openModal" class="bg-blue-500 text-white px-4 py-2 rounded">
        Agregar Ejercicio
    </button>

    @if($showModal)
    <div class="fixed inset-0 z-50">
        <div class="flex min-h-screen items-center justify-center">
            <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

            <div class="relative z-10 w-full max-w-2xl rounded bg-white p-6 shadow-lg">
                <div class="flex items-center justify-between border-b pb-3">
                    <h2 class="text-xl">Nuevo Ejercicio</h2>
                    <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
                    <div>
                        <label class="text-sm">Curso</label>
                        <select
                            wire:model="courseId"
                            wire:change="handleCourse($event.target.value)"
                            class="w-full rounded border p-2 @error('courseId') border-red-500 ring-1 ring-red-500 @enderror"
                        >
                            <option value="">Seleccionar…</option>
                            @foreach($courses as $c)
                                <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                            @endforeach
                        </select>
                        @error('courseId') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm">Lección</label>
                        <select
                            wire:model="lessonId"
                            wire:key="lesson-select-create-{{ $courseId ?? 'x' }}"
                            class="w-full rounded border p-2 @error('lessonId') border-red-500 ring-1 ring-red-500 @enderror"
                            @disabled(!$courseId)
                        >
                            <option value="" @selected($lessonId==='' || $lessonId===null)">Seleccionar…</option>
                            @foreach($lessons as $l)
                                <option value="{{ $l['id'] }}">{{ $l['title'] }}</option>
                            @endforeach
                        </select>
                        @error('lessonId') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm">Tipo</label>
                        <select
                            wire:model.live="type"
                            class="w-full rounded border p-2 @error('type') border-red-500 ring-1 ring-red-500 @enderror"
                        >
                            <option value="mcq">Opción múltiple</option>
                            <option value="true_false">Verdadero / Falso</option>
                            <option value="fill_blank">Completar</option>
                        </select>
                        @error('type') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm">Enunciado</label>
                        <textarea
                            wire:model="question"
                            class="w-full rounded border p-2 @error('question') border-red-500 ring-1 ring-red-500 @enderror"
                            rows="3"
                        ></textarea>
                        @error('question') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- dinámico igual que antes (sin cambios de validación) --}}

                @if($type === 'mcq')
                    <div class="mt-4 space-y-2">
                        <p class="text-sm font-semibold">Opciones (marca la correcta)</p>
                        @foreach($options as $i => $op)
                        <div class="flex items-center gap-2">
                            <input type="radio" name="mcq_correct_create" wire:model="correctIndex" value="{{ $i }}" class="h-4 w-4">
                            <input type="text" wire:model="options.{{ $i }}" class="w-full rounded border p-2" placeholder="Opción {{ $i+1 }}">
                        </div>
                        @endforeach
                        @error('options.*') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                        @error('correctIndex') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                @elseif($type === 'true_false')
                    <div class="mt-4">
                        <p class="text-sm font-semibold">Respuesta correcta</p>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2">
                                <input type="radio" name="tf_answer_create" wire:model="answerBool" value="1" class="h-4 w-4"> Verdadero
                            </label>
                            <label class="flex items-center gap-2">
                                <input type="radio" name="tf_answer_create" wire:model="answerBool" value="0" class="h-4 w-4"> Falso
                            </label>
                        </div>
                        @error('answerBool') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                @else
                    <div class="mt-4">
                        <p class="mb-2 text-sm font-semibold">Respuestas válidas (una o más)</p>
                        @foreach($answersFill as $i => $ans)
                            <div class="mb-2 flex gap-2">
                                <input type="text" wire:model="answersFill.{{ $i }}" class="w-full rounded border p-2" placeholder="Respuesta {{ $i+1 }}">
                                <button type="button" class="rounded border px-2"
                                    wire:click="$set('answersFill', {{ collect($answersFill)->except($i)->values() }})">Quitar</button>
                            </div>
                        @endforeach
                        <button type="button" class="rounded border px-3 py-1"
                            wire:click="$set('answersFill', [...$answersFill, ''])">Agregar respuesta</button>
                        @error('answersFill.*') <p class="mt-1 block text-left text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endif

                <div class="mt-6 flex justify-end gap-2">
                    <button wire:click="save" class="rounded bg-green-600 px-4 py-2 text-white">Guardar</button>
                    <button type="button" wire:click="closeModal" class="rounded px-4 py-2 text-red-500">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
