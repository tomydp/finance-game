<div>
    <button
        type="button"
        wire:click="openModal"
        class="rounded bg-blue-500 px-4 py-2 text-white transition hover:bg-blue-600"
    >
        Editar
    </button>

    @if ($isOpen)
        {{-- Overlay + wrapper scrollable --}}
        <div class="fixed inset-0 z-[9999]">
            {{-- Overlay (cierra al click) --}}
            <div class="absolute inset-0 bg-black/50" wire:click="closeModal"></div>

            {{-- Wrapper con scroll si el contenido excede la pantalla --}}
            <div class="relative z-10 min-h-full p-4 sm:p-6 flex items-start sm:items-center justify-center overflow-y-auto">
                {{-- Card del modal: alto máximo y layout en columna --}}
                <div class="w-full max-w-2xl bg-white rounded-lg shadow-xl flex flex-col max-h-[90vh]"
                     wire:key="edit-modal-{{ $exerciseId }}-{{ $uiNonce }}">

                    {{-- Header (fijo dentro del modal) --}}
                    <div class="flex items-center justify-between bg-blue-600 px-4 py-3 text-white shrink-0">
                        <h2 class="text-lg font-medium">Editar Ejercicio #{{ $exerciseId }}</h2>
                        <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                    </div>

                    {{-- Body scrollable --}}
                    <div class="p-6 text-left overflow-y-auto">
                        <form wire:submit.prevent="update" class="space-y-6">
                            {{-- Fila 1: Curso/Lección --}}
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Curso</label>
                                    <select
                                        wire:model="courseId"
                                        wire:change="handleCourse($event.target.value)"
                                        class="mt-1 w-full rounded border p-2 @error('courseId') border-red-500 ring-1 ring-red-500 @enderror"
                                    >
                                        <option value="">Seleccionar…</option>
                                        @foreach($courses as $c)
                                            <option value="{{ $c['id'] }}">{{ $c['name'] }}@if(($c['status'] ?? null) === 'inactivo') (Inactivo) @endif</option>
                                        @endforeach
                                    </select>
                                    @error('courseId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Lección</label>
                                    <select
                                        wire:model="lessonId"
                                        wire:key="lesson-select-edit-{{ $courseId ?? 'x' }}"
                                        class="mt-1 w-full rounded border p-2 @error('lessonId') border-red-500 ring-1 ring-red-500 @enderror"
                                        @disabled(!$courseId)
                                    >
                                        <option value="" @selected($lessonId==='' || $lessonId===null)">Seleccionar…</option>
                                        @foreach($lessons as $l)
                                            <option value="{{ $l['id'] }}">{{ $l['title'] }}@if(($l['status'] ?? null) === 'inactivo') (Inactivo) @endif</option>
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

                            {{-- Tipo (solo lectura) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tipo</label>
                                <div class="mt-1">
                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                                        @switch($editType)
                                            @case('mcq') Opción múltiple @break
                                            @case('true_false') Verdadero / Falso @break
                                            @case('fill_blank') Completar @break
                                        @endswitch
                                    </span>
                                </div>
                                <input type="hidden" wire:model="editType">
                            </div>

                            {{-- Enunciado + helpers fill_blank --}}
                            <div class="md:col-span-2"
                                 x-data="{
                                   q: @entangle('question'),
                                   preview() { return (this.q || '').split('[[BLANK]]').join('_____').replaceAll('\n','<br>'); },
                                   insertBlank() {
                                       const el = $refs.q; const s = el.selectionStart ?? 0; const e = el.selectionEnd ?? s;
                                       if ((this.q || '').includes('[[BLANK]]')) return;
                                       this.q = (this.q || '').slice(0, s) + '[[BLANK]]' + (this.q || '').slice(e);
                                       this.$nextTick(() => { el.focus(); el.setSelectionRange(s + 9, s + 9); });
                                   },
                                   clearBlank() { this.q = (this.q || '').replace('[[BLANK]]', ''); }
                                 }">
                                <label class="block text-sm font-medium text-gray-700">Enunciado</label>
                                <textarea
                                    x-ref="q"
                                    x-model="q"
                                    wire:model.defer="question"
                                    rows="3"
                                    class="mt-1 w-full rounded border p-2 @error('question') border-red-500 ring-1 ring-red-500 @enderror"
                                ></textarea>
                                @error('question') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror

                                @if($editType === 'fill_blank')
                                    <div class="mt-2 mx-auto w-full md:w-3/4 lg:w-2/3">
                                        <div class="grid grid-cols-2 gap-3">
                                            <button type="button"
                                                    class="inline-flex w-full items-center justify-center rounded border px-4 py-2 text-center hover:bg-gray-50"
                                                    @click="insertBlank()">
                                                Insertar hueco en el cursor
                                            </button>
                                            <button type="button"
                                                    class="inline-flex w-full items-center justify-center rounded border px-4 py-2 text-center hover:bg-gray-50"
                                                    @click="clearBlank()">
                                                Quitar hueco
                                            </button>
                                        </div>
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500">
                                        <span class="font-medium">Vista previa:</span>
                                        <span class="font-mono" x-html="preview()"></span>
                                    </div>
                                @endif
                            </div>

                            {{-- Bloque dinámico por tipo --}}
                            <div class="mt-2"
                                 wire:replace
                                 wire:key="edit-dyn-{{ $editType }}-{{ $lessonId === null ? 'x' : $lessonId }}-{{ $uiNonce }}">
                                @if($editType === 'mcq')
                                    <div class="space-y-2">
                                        <p class="text-sm font-semibold text-gray-700">Opciones (marca la correcta)</p>
                                        @foreach($options as $i => $op)
                                            <div class="flex items-center gap-2" wire:key="mcq-opt-edit-{{ $i }}">
                                                <input type="radio" name="mcq_correct_edit" wire:model="correctIndex" value="{{ $i }}" class="h-4 w-4">
                                                <input type="text" wire:model="options.{{ $i }}" class="w-full rounded border p-2" placeholder="Opción {{ $i+1 }}">
                                            </div>
                                        @endforeach
                                        @error('options.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                        @error('correctIndex') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                @elseif($editType === 'true_false')
                                    <div class="space-y-2">
                                        <p class="text-sm font-semibold text-gray-700">Respuesta correcta</p>
                                        <div class="flex gap-6">
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="tf_answer_edit" wire:model="answerBool" value="1" class="h-4 w-4">
                                                <span>Verdadero</span>
                                            </label>
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="tf_answer_edit" wire:model="answerBool" value="0" class="h-4 w-4">
                                                <span>Falso</span>
                                            </label>
                                        </div>
                                        @error('answerBool') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                @else {{-- fill_blank --}}
                                    <div class="space-y-2">
                                        <label class="block text-sm font-semibold text-gray-700">Respuesta válida</label>
                                        <input
                                            type="text"
                                            wire:model="answersFill.0"
                                            class="w-full rounded border p-2 @error('answersFill.0') border-red-500 ring-1 ring-red-500 @enderror"
                                            placeholder="Respuesta correcta">
                                        @error('answersFill.0') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                @endif
                            </div>

                            {{-- Explicación Markdown --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Explicación (Markdown, opcional)</label>
                                <textarea
                                    wire:model.defer="explanation_md"
                                    rows="6"
                                    class="mt-1 w-full rounded border p-2 @error('explanation_md') border-red-500 ring-1 ring-red-500 @enderror"
                                ></textarea>
                                @error('explanation_md') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                            </div>
                        </form>
                    </div>

                    {{-- Footer fijo dentro del modal --}}
                    <div class="flex justify-end gap-2 border-t bg-white p-4 shrink-0">
                        <button type="button" wire:click="closeModal" class="rounded bg-gray-200 px-4 py-2 hover:bg-gray-300">
                            Cancelar
                        </button>
                        <button type="button" wire:click="update" class="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                            Actualizar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
