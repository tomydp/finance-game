<div>
    <button wire:click="$set('showModal', true)" class="bg-blue-500 text-white px-4 py-2 rounded">Agregar Curso</button>

    @if($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl mb-4">Nuevo Curso</h2>

            <input wire:model="name" type="text" placeholder="Nombre" class="w-full mb-2 border p-2" />
            <textarea wire:model="description" placeholder="Descripción" class="w-full mb-2 border p-2"></textarea>
                <select wire:model="difficulty" class="w-full mb-4 border p-2">
                    <option value="">Seleccionar dificultad</option>
                    @foreach($difficulties as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>

            <button wire:click="save" class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
            <button wire:click="$set('showModal', false)" class="ml-2 text-red-500">Cancelar</button>
        </div>
    </div>
    @endif
</div>
