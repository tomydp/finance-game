<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class ShowCourse extends Component
{
    public $courses;

    // Escuchar el evento desde CreateCourse
    public $listeners = ['courseCreated' => 'refreshCourses'];

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
        return view('livewire.show-course');
    }
}
