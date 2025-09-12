<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Course;

class CreateLesson extends Component
{
    public ?string $title = null;
    public ?int $course_id = null;

    public bool $showModal = false;

    protected $rules = [
        'title'     => 'required|string|max:255',
        'course_id' => 'required|exists:courses,id',
    ];

    public function openModal(): void
    {
        $this->reset(['title','course_id']);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->reset(['title','course_id','showModal']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        Lesson::create([
            'title'     => $this->title,
            'course_id' => $this->course_id,
        ]);

        $this->dispatch('lessonCreated');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.create-lesson', [
            'courses' => Course::orderBy('name')->get(),
        ]);
    }
}
