<div class="container mx-auto p-4" wire:key="courses-page-{{ $courses->currentPage() }}">
    <div class="rounded-lg bg-white p-6 shadow">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-500">
                    <tr>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Nombre</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Descripción</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Dificultad</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Estado</th>
                        <th class="px-6 py-7 text-left text-sm font-medium uppercase leading-normal tracking-wider text-white">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($courses as $course)
                        <tr wire:key="course-row-{{ $course->id }}">
                            <td class="px-6 py-7 whitespace-nowrap">{{ $course->name }}</td>
                            <x-tooltip-cell :text="$course->description" />
                            <td class="px-6 py-7 whitespace-nowrap">{{ $course->difficulty }}</td>
                            <td class="px-6 py-7 whitespace-nowrap">
                                @php($isActive = $course->status === 'activo')
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold {{ $isActive ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-7 whitespace-nowrap text-center">
                                {{-- Hijo por fila (abre modal y funciona en cualquier página) --}}
                                <livewire:edit-course :course-id="$course->id" wire:key="edit-course-{{ $course->id }}" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación con tu estilo "pill" --}}
        <div class="mt-4">
            {{ $courses->onEachSide(1)->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
