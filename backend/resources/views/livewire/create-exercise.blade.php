<div class="space-y-3">
    <!-- Botón principal -->
    <div class="relative">
        <button type="button" wire:click="openTypePicker" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Agregar Ejercicio
        </button>

        <!-- Lista de tipos (picker) -->
        @if($showTypePicker)
            <div class="absolute mt-2 w-64 rounded-lg border bg-white shadow z-10">
                <div class="px-3 py-2 text-sm font-semibold text-gray-600 border-b">
                    Elegí el tipo de ejercicio
                </div>
                <div class="p-2 space-y-1">
                    <button type="button"
                            wire:click="chooseType('mcq')"
                            class="w-full text-left px-3 py-2 rounded hover:bg-gray-50">
                        Opción múltiple
                    </button>
                    <button type="button"
                            wire:click="chooseType('true_false')"
                            class="w-full text-left px-3 py-2 rounded hover:bg-gray-50">
                        Verdadero / Falso
                    </button>
                    <button type="button"
                            wire:click="chooseType('fill_blank')"
                            class="w-full text-left px-3 py-2 rounded hover:bg-gray-50">
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

    <!-- Modal del formulario (solo curso, lección y estructura del tipo elegido) -->
    @if($showModal)
        <div class="fixed inset-0 z-50">
            <div class="flex min-h-screen items-center justify-center">
                <div class="fixed inset-0 bg-black/50" wire:click="closeModal"></div>

                <div class="relative z-10 w-full max-w-2xl overflow-hidden rounded-lg bg-white shadow-xl"
                     wire:key="create-modal-{{ $type }}-{{ ($lessonId === '' || $lessonId === null) ? 'x' : $lessonId }}-{{ $uiNonce }}">
                    <div class="flex items-center justify-between bg-blue-600 px-4 py-3 text-white">
                        <h2 class="text-lg font-medium">
                            Nuevo ejercicio:
                            @if($type === 'mcq') Opción múltiple
                            @elseif($type === 'true_false') Verdadero / Falso
                            @else Completar @endif
                        </h2>
                        <button type="button" wire:click="closeModal" class="text-xl leading-none">×</button>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                            <!-- Curso -->
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

                            <!-- Lección (dependiente) -->
                            <div>
                                <label class="text-sm">Lección</label>
                                <select
                                    wire:model="lessonId"
                                    wire:key="lesson-select-create-{{ $courseId ?? 'x' }}"
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

                            <!-- Enunciado -->
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

                        {{-- Bloque del tipo elegido (sin selector de tipo) --}}
                        <div class="mt-4"
                             wire:key="create-dyn-{{ $type }}-{{ ($lessonId === '' || $lessonId === null) ? 'x' : $lessonId }}-{{ $uiNonce }}">
                            @if($type === 'mcq')
                                <div class="space-y-2">
                                    <p class="text-sm font-semibold">Opciones (marca la correcta)</p>
                                    @foreach($options as $i => $op)
                                        <div class="mt-2 flex items-center gap-2" wire:key="mcq-opt-{{ $i }}">
                                            <input type="radio" name="mcq_correct_create" wire:model="correctIndex" value="{{ $i }}" class="h-4 w-4">
                                            <input type="text" wire:model="options.{{ $i }}" class="w-full rounded border p-2" placeholder="Opción {{ $i+1 }}">
                                        </div>
                                    @endforeach
                                    @error('options.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                    @error('correctIndex') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                            @elseif($type === 'true_false')
                                <div>
                                    <p class="text-sm font-semibold">Respuesta correcta</p>
                                    <div class="mt-2 flex gap-6">
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="tf_answer_create" wire:model="answerBool" value="1" class="h-4 w-4">
                                            Verdadero
                                        </label>
                                        <label class="flex items-center gap-2">
                                            <input type="radio" name="tf_answer_create" wire:model="answerBool" value="0" class="h-4 w-4">
                                            Falso
                                        </label>
                                    </div>
                                    @error('answerBool') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>

                            @else {{-- fill_blank --}}
                                <div>
                                    <p class="mb-2 text-sm font-semibold">Respuestas válidas (una o más)</p>
                                    @foreach($answersFill as $i => $ans)
                                        <div class="mb-2 flex gap-2" wire:key="fill-ans-create-{{ $i }}">
                                            <input
                                                type="text"
                                                wire:model="answersFill.{{ $i }}"
                                                class="w-full rounded border p-2"
                                                placeholder="Respuesta {{ $i+1 }}"
                                            >
                                            <button
                                                type="button"
                                                class="rounded border px-2"
                                                wire:click="removeFillAnswer({{ $i }})"
                                                @disabled(count($answersFill) <= 1)
                                            >
                                                Quitar
                                            </button>
                                        </div>
                                    @endforeach
                                    <button
                                        type="button"
                                        class="rounded border px-3 py-1"
                                        wire:click="addFillAnswer"
                                    >
                                        Agregar respuesta
                                    </button>
                                    @error('answersFill.*') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex justify-between">
                            <button type="button" wire:click="openTypePicker" class="rounded px-3 py-2 text-gray-600 hover:bg-gray-100">
                                ← Cambiar tipo
                            </button>
                            <div class="flex gap-2">
                                <!-- Importante: BUTTON (no submit) -->
                                <button type="button" wire:click="save" class="rounded bg-green-600 px-4 py-2 text-white">
                                    Guardar
                                </button>
                                <button type="button" wire:click="closeModal" class="rounded px-4 py-2 text-red-500">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endif
</div>
