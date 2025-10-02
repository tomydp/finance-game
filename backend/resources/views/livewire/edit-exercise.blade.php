<div>
    <button
        type="button"
        wire:click="openModal"
        class="rounded bg-blue-500 px-4 py-2 text-white transition hover:bg-blue-600"
    >
        Editar
    </button>

    @if ($isOpen)
        <div class="fixed inset-0 z-[9999]">
            <div class="flex min-h-screen items-center justify-center">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-xl">
                    <div class="flex items-center justify-between bg-blue-600 px-4 py-3 text-white">
                        <h2 class="text-lg font-medium">Editar Ejercicio #{{ $exerciseId }}</h2>
                        <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                    </div>

                    <div class="p-6">
                        <form wire:submit.prevent="update">
                            <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                <div>
                                    <label class="text-sm">Curso</label>
                                    <select
                                        wire:model="courseId"
                                        wire:change="handleCourse($event.target.value)"
                                        class="mt-1 w-full rounded border p-2 @error('courseId') border-red-500 ring-1 ring-red-500 @enderror"
                                    >
                                        <option value="">Seleccionar…</option>
                                        @foreach($courses as $c)
                                            <option value="{{ $c['id'] }}">{{ $c['name'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('courseId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="text-sm">Lección</label>
                                    <select
                                        wire:model="lessonId"
                                        wire:key="lesson-select-edit-{{ $courseId ?? 'x' }}"
                                        class="mt-1 w-full rounded border p-2 @error('lessonId') border-red-500 ring-1 ring-red-500 @enderror"
                                        @disabled(!$courseId)
                                    >
                                        <option value="" @selected($lessonId==='' || $lessonId===null)">Seleccionar…</option>
                                        @foreach($lessons as $l)
                                            <option value="{{ $l['id'] }}">{{ $l['title'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('lessonId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label for="editType" class="block text-sm font-medium text-gray-700">Tipo</label>
                                    <select
                                        id="editType"
                                        wire:model.change="editType"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-slate-600 focus:ring focus:ring-slate-600 sm:text-sm"
                                    >
                                        <option value="mcq">Opción múltiple</option>
                                        <option value="true_false">Verdadero / Falso</option>
                                        <option value="fill_blank">Completar</option>
                                    </select>
                                    @error('editType') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="text-sm">Enunciado</label>
                                    <textarea
                                        wire:model="question"
                                        class="mt-1 w-full rounded border p-2 @error('question') border-red-500 ring-1 ring-red-500 @enderror"
                                        rows="3"
                                    ></textarea>
                                    @error('question') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- 🔑 Bloque dinámico: wire:replace + key fuerte --}}
                            <div class="mt-4"
                                 wire:replace
                                 wire:key="edit-dyn-{{ $editType }}-{{ $lessonId === null ? 'x' : $lessonId }}-{{ $uiNonce }}">
                                @if($editType === 'mcq')
                                    <div class="space-y-2" wire:key="edit-case-mcq-{{ $lessonId ?? 'x' }}-{{ $uiNonce }}">
                                        <p class="text-sm font-semibold">Opciones (marca la correcta)</p>
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
                                    <div wire:key="edit-case-tf-{{ $lessonId ?? 'x' }}-{{ $uiNonce }}">
                                        <p class="text-sm font-semibold">Respuesta correcta</p>
                                        <div class="flex gap-6">
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="tf_answer_edit" wire:model="answerBool" value="1" class="h-4 w-4">
                                                Verdadero
                                            </label>
                                            <label class="flex items-center gap-2">
                                                <input type="radio" name="tf_answer_edit" wire:model="answerBool" value="0" class="h-4 w-4">
                                                Falso
                                            </label>
                                        </div>
                                        @error('answerBool') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                @else
                                    <div wire:key="edit-case-fill-{{ $lessonId ?? 'x' }}-{{ $uiNonce }}">
                                        <p class="mb-2 text-sm font-semibold">Respuestas válidas</p>
                                        @foreach($answersFill as $i => $ans)
                                            <div class="mb-2 flex gap-2" wire:key="fill-ans-edit-{{ $i }}">
                                                <input type="text" wire:model="answersFill.{{ $i }}" class="w-full rounded border p-2" placeholder="Respuesta {{ $i+1 }}">
                                                <button type="button" class="rounded border px-2"
                                                        wire:click="removeFillAnswer({{ $i }})"
                                                        @disabled(count($answersFill) <= 1)">Quitar</button>
                                            </div>
                                        @endforeach
                                        <button type="button" class="rounded border px-3 py-1"
                                                wire:click="addFillAnswer">Agregar respuesta</button>
                                        @error('answersFill.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="mt-6 flex justify-end gap-2">
                                <button type="submit" class="rounded bg-blue-600 px-4 py-2 text-white">Actualizar</button>
                                <button type="button" wire:click="closeModal" class="rounded bg-gray-200 px-4 py-2">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
