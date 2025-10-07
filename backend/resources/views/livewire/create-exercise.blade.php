<div class="space-y-3">
    {{-- Botón principal --}}
    <div class="relative">
        <button type="button"
                wire:click="openTypePicker"
                class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            Agregar Ejercicio
        </button>

        {{-- Picker de tipo --}}
        @if($showTypePicker)
            <div class="absolute mt-2 w-72 rounded-lg border bg-white shadow z-20">
                <div class="border-b px-3 py-2 text-sm font-semibold text-gray-600">
                    Elegí el tipo de ejercicio
                </div>
                <div class="p-2 space-y-1">
                    <button type="button" wire:click="chooseType('mcq')" class="w-full rounded px-3 py-2 text-left hover:bg-gray-50">
                        Opción múltiple
                    </button>
                    <button type="button" wire:click="chooseType('true_false')" class="w-full rounded px-3 py-2 text-left hover:bg-gray-50">
                        Verdadero / Falso
                    </button>
                    <button type="button" wire:click="chooseType('fill_blank')" class="w-full rounded px-3 py-2 text-left hover:bg-gray-50">
                        Completar
                    </button>
                </div>
                <div class="border-t p-2">
                    <button type="button" wire:click="closeTypePicker" class="text-sm text-gray-500 hover:text-gray-700">
                        Cancelar
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-50">
            <div class="flex min-h-screen items-center justify-center">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-xl"
                     wire:key="create-modal-{{ $type }}-{{ ($lessonId === '' || $lessonId === null) ? 'x' : $lessonId }}-{{ $uiNonce }}">
                    {{-- Header --}}
                    <div class="flex items-center justify-between bg-blue-600 px-4 py-3 text-white">
                        <h2 class="text-lg font-medium">
                            Nuevo ejercicio:
                            @if($type === 'mcq') Opción múltiple
                            @elseif($type === 'true_false') Verdadero / Falso
                            @else Completar @endif
                        </h2>
                        <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                    </div>

                    {{-- Body --}}
                    <div class="p-6">
                        <form wire:submit.prevent="save" class="space-y-4">
                            {{-- Fila 1: Curso/Lección --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Curso</label>
                                    <select
                                        wire:model="courseId"
                                        wire:change="handleCourse($event.target.value)"
                                        class="mt-1 w-full rounded border p-2 @error('courseId') border-red-500 ring-1 ring-red-500 @enderror">
                                        <option value="">Seleccionar…</option>
                                        @foreach($courses as $c)
                                            <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('courseId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Lección</label>
                                    <select
                                        wire:model="lessonId"
                                        wire:key="lesson-select-create-{{ $courseId ?? 'x' }}"
                                        class="mt-1 w-full rounded border p-2 @error('lessonId') border-red-500 ring-1 ring-red-500 @enderror"
                                        @disabled(!$courseId)>
                                        <option value="" @selected($lessonId==='' || $lessonId===null)">Seleccionar…</option>
                                        @foreach($lessons as $l)
                                            <option value="{{ $l['id'] }}">{{ $l['title'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('lessonId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Estado</label>
                                <select
                                    wire:model="status"
                                    class="mt-1 w-full rounded border p-2 @error('status') border-red-500 ring-1 ring-red-500 @enderror"
                                >
                                    @foreach($statusOptions as $option)
                                        <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                    @endforeach
                                </select>
                                @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Enunciado + Insertar hueco (solo Completar) --}}
                            <div
                                class="md:col-span-2"
                                wire:key="question-block-{{ $uiNonce }}"
                                x-data="{
                                    q: @entangle('question'),
                                    insertBlank() {
                                        const el = $refs.q;
                                        const s  = el.selectionStart ?? 0;
                                        const e  = el.selectionEnd ?? s;
                                        const sel = el.value.slice(s, e);
                                        $wire.makeBlank(s, e, sel);
                                        requestAnimationFrame(() => {
                                            el.focus();
                                            el.setSelectionRange(s + 9, s + 9);
                                        });
                                    }
                                }"
                            >
                                <label class="block text-sm font-medium text-gray-700">Enunciado</label>
                                <textarea
                                    x-ref="q"
                                    x-model="q"
                                    wire:key="question-ta-{{ $uiNonce }}"
                                    rows="3"
                                    class="mt-1 w-full rounded border p-2 @error('question') border-red-500 ring-1 ring-red-500 @enderror"
                                ></textarea>
                                @error('question') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                                @if($type === 'fill_blank')
                                    <div class="mt-2 mx-auto w-full md:w-3/4 lg:w-2/3">
                                        <div class="grid grid-cols-2 gap-3">
                                            <button
                                                type="button"
                                                class="inline-flex w-full items-center justify-center rounded border px-4 py-2 text-center hover:bg-gray-50"
                                                @click="insertBlank"
                                            >
                                                Insertar hueco en el cursor
                                            </button>

                                            <button
                                                type="button"
                                                class="inline-flex w-full items-center justify-center rounded border px-4 py-2 text-center hover:bg-gray-50"
                                                @click="$wire.clearBlank(); requestAnimationFrame(() => $refs.q.focus())"
                                            >
                                                Quitar hueco
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        <span class="font-medium">Vista previa:</span>
                                        <span class="font-mono break-words whitespace-pre-wrap"
                                              x-text="(q ?? '').split('[[BLANK]]').join('_____')"></span>
                                    </div>
                                @endif
                            </div>

                            {{-- Bloque dinámico --}}
                            <div class="mt-2"
                                 wire:key="create-dyn-{{ $type }}-{{ ($lessonId === '' || $lessonId === null) ? 'x' : $lessonId }}-{{ $uiNonce }}">
                                @if($type === 'mcq')
                                    <div class="space-y-2">
                                        <p class="text-sm font-semibold text-gray-700">Opciones (marca la correcta)</p>
                                        @foreach($options as $i => $op)
                                            <div class="flex items-center gap-2" wire:key="mcq-opt-{{ $i }}">
                                                <input type="radio" name="mcq_correct_create" wire:model="correctIndex" value="{{ $i }}" class="h-4 w-4">
                                                <input type="text" wire:model="options.{{ $i }}" class="w-full rounded border p-2" placeholder="Opción {{ $i+1 }}">
                                            </div>
                                        @endforeach
                                        @error('options.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        @error('correctIndex') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                @elseif($type === 'true_false')
                                    <div class="space-y-2">
                                        <p class="text-sm font-semibold text-gray-700">Respuesta correcta</p>
                                        <div class="flex gap-6">
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="tf_answer_create" wire:model="answerBool" value="1" class="h-4 w-4">
                                                <span>Verdadero</span>
                                            </label>
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="tf_answer_create" wire:model="answerBool" value="0" class="h-4 w-4">
                                                <span>Falso</span>
                                            </label>
                                        </div>
                                        @error('answerBool') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                @else {{-- fill_blank --}}
                                    <div class="space-y-2">
                                        <p class="text-sm font-semibold text-gray-700">Respuesta válida</p>
                                        <input
                                            type="text"
                                            wire:model="answersFill.0"
                                            class="w-full rounded border p-2 @error('answersFill.0') border-red-500 ring-1 ring-red-500 @enderror"
                                            placeholder="Respuesta correcta">
                                        @error('answersFill.0') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                @endif
                            </div>

                            {{-- NUEVO: Explicación Markdown --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Explicación (Markdown, opcional)</label>
                                <textarea
                                    wire:model.defer="explanation_md"
                                    rows="6"
                                    class="mt-1 w-full rounded border p-2 @error('explanation_md') border-red-500 ring-1 ring-red-500 @enderror"
                                    placeholder="Explicá por qué la respuesta correcta es X, con ejemplo numérico…"></textarea>
                                @error('explanation_md') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>

                            {{-- Footer --}}
                            <div class="mt-6 flex justify-between">
                                <button type="button" wire:click="openTypePicker" class="rounded px-3 py-2 text-gray-600 hover:bg-gray-100">
                                    ← Cambiar tipo
                                </button>
                                <div class="flex gap-2">
                                    <button type="submit" class="rounded bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                                        Guardar
                                    </button>
                                    <button type="button" wire:click="closeModal" class="rounded px-4 py-2 text-red-500 hover:bg-red-50">
                                        Cancelar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- /Body --}}
                </div>
            </div>
        </div>
    @endif
</div>
