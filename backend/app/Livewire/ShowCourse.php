<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCourse extends Component
{
    use WithPagination;
    public $courses;

    // Escuchar el evento desde CreateCourse
    public $listeners = ['courseCreated' => 'refreshCourses','courseUpdated' => 'refreshCourses'];


    public function editCourse($id)
    {
        $this->dispatch('editCourse', id: $id)->to(\App\Livewire\EditCourse::class);
    }


    public function mount()
    {
        $this->courses = Course::all();
    }

    // Método que se ejecuta al recibir el evento
    public function refreshCourses()
    {
        $this->courses = Course::all();
    }

    public function render()
    {
        $courses = Course::paginate(5);
    return view('livewire.show-course', [
        'courses' => $courses
    ]);

    }
}
