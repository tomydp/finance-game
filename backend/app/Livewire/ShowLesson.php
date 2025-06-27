<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lesson;
use App\Models\Course;

class ShowLesson extends Component
{
    use WithPagination;
    public $lessons;
    public $course_id;
    public $listeners = ['lessonCreated' => 'refreshLessons','lessonUpdated' => 'refreshLessons'];

    public function mount()
    {
        $this->lessons = Lesson::all();
    }

    public function editLesson($id)
    {
        $this->dispatch('editLesson', id: $id)->to(\App\Livewire\EditLesson::class);
    }

    public function refreshLessons()
    {
        $this->lessons = Lesson::all();
    }
    public function render()
    {
        $courses = Course::all();
        return view('livewire.show-lesson', [
            'courses' => $courses
        ]);
    }
}
