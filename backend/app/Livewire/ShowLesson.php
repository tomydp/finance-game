<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Course;
use App\Livewire\EditLesson;

class ShowLesson extends Component
{
    public $listeners = ['lessonCreated' => '$refresh', 'lessonUpdated' => '$refresh'];

    public function editLesson($id)
    {
        $this->dispatch('editLesson', id: $id)->to(EditLesson::class);
    }

    public function render()
    {
        return view('livewire.show-lesson', [
            'lessons' => Lesson::with('course')->get(),
            'courses' => Course::all(),
        ]);
    }
}
