<div>
    @if($showModal)
    <div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
      <div class="bg-white p-6 rounded shadow w-full max-w-md">
        <h2 class="text-xl mb-4">Editar Curso</h2>
    
        <div class="space-y-3">
          <input type="text" wire:model="name" class="w-full border p-2" placeholder="Nombre" />
          <textarea wire:model="description" class="w-full border p-2" placeholder="Descripción"></textarea>
    
          <select wire:model="difficulty" class="w-full border p-2">
            <option value="" disabled @selected($difficulty===null)>Seleccionar dificultad</option>
            <option value="facil">Fácil</option>
            <option value="medio">Medio</option>
            <option value="dificil">Difícil</option>
          </select>
          @error('difficulty') <p class="text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    
        <div class="mt-5 flex justify-end gap-2">
          <button wire:click="update" class="px-4 py-2 bg-blue-600 text-white rounded">Editar</button>
          <button wire:click="$set('showModal', false)" class="px-4 py-2 bg-gray-200 rounded">Cancelar</button>
        </div>
      </div>
    </div>
    @endif
    
</div>
