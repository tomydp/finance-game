<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCourse extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    public $listeners = ['courseCreated' => '$refresh', 'courseUpdated' => '$refresh'];

    public function editCourse($id)
    {
        $this->dispatch('editCourse', id: $id)->to(\App\Livewire\EditCourse::class);
    }

    public function render()
    {
        return view('livewire.show-course', [
            'courses' => Course::orderBy('id')->paginate(5),
        ]);
    }
}
