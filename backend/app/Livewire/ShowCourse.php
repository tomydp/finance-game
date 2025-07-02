<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCourse extends Component
{
    use WithPagination;

    public $listeners = ['courseCreated' => '$refresh', 'courseUpdated' => '$refresh'];

    public function editCourse($id)
    {
        $this->dispatch('editCourse', id: $id)->to(\App\Livewire\EditCourse::class);
    }

    public function render()
    {
        return view('livewire.show-course', [
            'courses' => Course::paginate(5),
        ]);
    }
}
