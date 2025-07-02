<div>
    @if($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-md">
            <h2 class="text-xl mb-4">Editar Leccion</h2>

            <form wire:submit.prevent='update'> 
            <input wire:model="title" type="text" class="w-full mb-2 border p-2" placeholder="Titulo" />
            @error('title') 
            <span class="text-red-500 text-xs">{{ $message }}</span> 
            @enderror
            <textarea wire:model="description" class="w-full mb-2 border p-2" placeholder="Descripción"></textarea>
            @error('description') 
            <span class="text-red-500 text-xs">{{ $message }}</span> 
            @enderror
            <select wire:model="course_id" class="w-full mb-4 border p-2">
                <option value="">Seleccionar curso</option>
                @foreach($courses as $courses)
                    <option value="{{ $courses->id }}">{{ $courses->name }}</option>
                @endforeach
            </select>
            @error('course_id') 
            <span class="text-red-500 text-xs">{{ $message }}</span> 
            @enderror
   
            <div class="flex justify-end gap-2 mt-4">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Editar</button>
                <button type="button" wire:click="closeModal" class="ml-2 text-red-500">Cancelar</button>
            </div>
            
        </form>

        </div>
    </div>
    @endif
</div>
