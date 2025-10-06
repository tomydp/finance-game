<div class="container mx-auto p-4" wire:key="lessons-page-{{ $lessons->currentPage() }}">
    <div class="rounded-lg bg-white p-6 shadow">

  
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Título</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Curso</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Estado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Acciones</th>
                    </tr>
                </thead>
  
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($lessons as $lesson)
                        <tr wire:key="lesson-row-{{ $lesson->id }}">
                            <td class="px-6 py-7 whitespace-nowrap">{{ $lesson->title }}</td>
                            <td class="px-6 py-7 whitespace-nowrap">{{ $lesson->course?->name ?? 'Sin curso' }}</td>
                            <td class="px-6 py-7 whitespace-nowrap">
                                @php($active = $lesson->status === 'activo')
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $active ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($lesson->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap">
                              <livewire:edit-lesson :lesson-id="$lesson->id" wire:key="edit-lesson-{{ $lesson->id }}" />
                          </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
  
        <div class="mt-4">
            {{ $lessons->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>
  </div>
  
