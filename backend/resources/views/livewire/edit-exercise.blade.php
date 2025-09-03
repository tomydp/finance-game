<div>
    @if($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
        <div class="bg-white p-6 rounded shadow w-full max-w-2xl">
            <h2 class="text-xl mb-4">Editar Ejercicio</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="text-sm">Curso</label>
                    <select wire:model="courseId" class="w-full border p-2">
                        <option value="">Seleccionar…</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('courseId') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm">Lección</label>
                    <select wire:model="lessonId" class="w-full border p-2" @disabled(!$courseId)>
                        <option value="">Seleccionar…</option>
                        @foreach($lessons as $l)
                            <option value="{{ $l->id }}">{{ $l->title }}</option>
                        @endforeach
                    </select>
                    @error('lessonId') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="text-sm">Tipo</label>
                    <select wire:model.live="type" class="w-full border p-2">
                        <option value="mcq">Opción múltiple</option>
                        <option value="true_false">Verdadero / Falso</option>
                        <option value="fill_blank">Completar</option>
                    </select>
                    @error('type') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="text-sm">Enunciado</label>
                    <textarea wire:model="question" class="w-full border p-2" rows="3"></textarea>
                    @error('question') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Dinámico --}}
            @if($type === 'mcq')
                <div class="mt-4 space-y-2">
                    <p class="text-sm font-semibold">Opciones (marca la correcta)</p>
                    @foreach($options as $i => $op)
                    <div class="flex items-center gap-2">
                        <input type="radio" wire:model="correctIndex" value="{{ $i }}" class="h-4 w-4">
                        <input type="text" wire:model="options.{{ $i }}" class="w-full border p-2" placeholder="Opción {{ $i+1 }}">
                    </div>
                    @endforeach
                    @error('options.*') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('correctIndex') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @elseif($type === 'true_false')
                <div class="mt-4">
                    <p class="text-sm font-semibold">Respuesta correcta</p>
                    <div class="flex gap-6">
                        <label class="flex items-center gap-2">
                            <input type="radio" wire:model="answerBool" value="1" class="h-4 w-4"> Verdadero
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" wire:model="answerBool" value="0" class="h-4 w-4"> Falso
                        </label>
                    </div>
                    @error('answerBool') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @else
                <div class="mt-4">
                    <p class="text-sm font-semibold mb-2">Respuestas válidas</p>
                    @foreach($answersFill as $i => $ans)
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="answersFill.{{ $i }}" class="w-full border p-2" placeholder="Respuesta {{ $i+1 }}">
                            <button type="button" class="px-2 border rounded"
                                wire:click="$set('answersFill', {{ collect($answersFill)->except($i)->values() }})">Quitar</button>
                        </div>
                    @endforeach
                    <button type="button" class="px-3 py-1 border rounded"
                        wire:click="$set('answersFill', [...$answersFill, ''])">Agregar respuesta</button>
                    @error('answersFill.*') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            @endif

            <div class="mt-6 flex justify-end gap-2">
                <button wire:click="update" class="px-4 py-2 bg-blue-600 text-white rounded">Editar</button>
                <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-200 rounded">Cancelar</button>
            </div>
        </div>
    </div>
    @endif
</div>
