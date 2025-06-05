<div>
    <button wire:click="$set('showModal', true)" class="bg-blue-500 text-white px-4 py-2 rounded">Agregar Curso</button>

    @if($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl mb-4">Nuevo Curso</h2>
            <form wire:submit.prevent='save'> 
                
            <input wire:model="name" type="text" placeholder="Nombre" class="w-full mb-2 border p-2" />
            @error('name') 
            <span class="text-red-500 text-xs">{{ $message }}</span> 
            @enderror
            <textarea wire:model="description" placeholder="Descripción" class="w-full mb-2 border p-2"></textarea>
            @error('description') 
            <span class="text-red-500 text-xs">{{ $message }}</span> 
            @enderror
                <select wire:model="difficulty" class="w-full mb-4 border p-2">
                    <option value="">Seleccionar dificultad</option>
                    @foreach($difficulties as $value)
                        <option value="{{ $value }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('difficulty') 
                <span class="text-red-500 text-xs">{{ $message }}</span> 
                @enderror
                
                <div class="flex justify-end gap-2 mt-4">
                    <button wire:click="save" class="bg-green-600 text-white px-4 py-2 rounded">Guardar</button>
                    <button type="button" wire:click="closeModal" class="text-red-500">Cancelar</button>             
               </div>

            </form>
        </div>
    </div>
    @endif
</div>
