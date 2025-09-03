<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow p-6">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-blue-500">
            <tr>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">ID</th>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">Título</th>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">Descripción</th>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">Curso</th>
              <th class="px-6 py-7 text-left text-sm font-medium text-white uppercase tracking-wider leading-normal">Acciones</th>
            </tr>
          </thead>
  
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($lessons as $lesson)
              <tr>
                <td class="px-6 py-7 whitespace-nowrap">{{ $lesson->id }}</td>
                <td class="px-6 py-7 whitespace-nowrap">{{ $lesson->title }}</td>
  
                {{-- misma celda con tooltip que usas en cursos --}}
                <x-tooltip-cell :text="$lesson->description" />
  
                <td class="px-6 py-7 whitespace-nowrap">
                  {{ $lesson->course?->name ?? 'Sin curso' }}
                </td>
  
                <td class="px-6 py-7 whitespace-nowrap">
                  <button wire:click="editLesson({{ $lesson->id }})"
                          class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600 transition">
                    Editar
                  </button>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  
      <div class="mt-4">
        {{ $lessons->onEachSide(1)->links() }}
      </div>
    </div>
    <livewire:edit-lesson />
  </div>
  