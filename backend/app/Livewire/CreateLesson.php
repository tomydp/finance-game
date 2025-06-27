<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Course;

class CreateLesson extends Component
{
    public $name, $description, $course_id;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'course_id' => 'required|exists:courses,id',
    ];

    public function save()
    {
        $this->validate();

        Lesson::create([
            'name' => $this->name,
            'description' => $this->description,
            'course_id' => $this->course_id,
        ]);

        $this->dispatch('lessonCreated');

        $this->reset(['name', 'description', 'course_id', 'showModal']);
    }

    public function closeModal()
    {
        $this->reset(['name', 'description', 'course_id', 'showModal']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
    public function render()
    {
        $courses = Course::all();
        return view('livewire.create-lesson', [
            'courses' => $courses
        ]);
    }
}
