<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Course;

class CreateLesson extends Component
{
    public $title, $description, $course_id;
    public $showModal = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'course_id' => 'required|exists:courses,id',
    ];

    public function save()
    {
        $this->validate();
    
        Lesson::create([
            'title' => $this->title,
            'description' => $this->description,
            'course_id' => $this->course_id,
        ]);
    
        $this->dispatch('lessonCreated');
    
        $this->reset(['title', 'description', 'course_id', 'showModal']);
        $this->resetErrorBag();
        $this->resetValidation();
    }
    

    public function messages()
    {
        return [
            'course_id.required' => 'Por favor, seleccioná un curso.',
            'course_id.exists' => 'El curso seleccionado no es válido.',
        ];
    }

    public function openModal()
{
    $this->reset(['title', 'description', 'course_id']);
    $this->resetErrorBag();
    $this->resetValidation();
    $this->showModal = true;
}


    public function closeModal()
    {
        $this->reset(['title', 'description', 'course_id', 'showModal']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.create-lesson', [
            'courses' => Course::all(),
        ]);
    }
}
