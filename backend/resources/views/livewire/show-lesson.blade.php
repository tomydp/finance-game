<div class="container mx-auto p-4">
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-2xl font-bold mb-4">Lecciones</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium text-gray-500 uppercase tracking-wider leading-normal">ID</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-gray-500 uppercase tracking-wider leading-normal">Nombre</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-gray-500 uppercase tracking-wider leading-normal">Descripcion</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-gray-500 uppercase tracking-wider leading-normal">Dificultad</th>
                        <th class="px-6 py-7 text-left text-sm font-medium text-gray-500 uppercase tracking-wider leading-normal">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($lessons as $lesson)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $lesson->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $lesson->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $lesson->description }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $lesson->course_id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button wire:click="editLesson({{ $lesson->id }})"
                                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                                    Editar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>    
        </div>
        

    </div>


    <livewire:edit-lesson />
</div>
