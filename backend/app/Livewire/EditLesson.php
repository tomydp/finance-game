<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Course;

class EditLesson extends Component
{
    public $showModal = false;

    public $lessonId;
    public $title;
    public $description;
    public $course_id;

    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'course_id' => 'required|exists:courses,id',
    ];

    protected $listeners = ['editLesson' => 'loadLesson'];

    public function loadLesson($id)
    {
        $this->resetErrorBag();
        $this->resetValidation();
    
        $lesson = Lesson::findOrFail($id);
    
        $this->lessonId = $lesson->id;
        $this->title = $lesson->title;
        $this->description = $lesson->description;
        $this->course_id = $lesson->course_id;
    
        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        Lesson::findOrFail($this->lessonId)->update([
            'title' => $this->title,
            'description' => $this->description,
            'course_id' => $this->course_id,
        ]);

        $this->reset(['lessonId', 'title', 'description', 'course_id', 'showModal']);

        $this->dispatch('lessonUpdated')->to(ShowLesson::class);
    }

    public function closeModal()
    {
        $this->reset(['lessonId', 'title', 'description', 'course_id', 'showModal']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.edit-lesson', [
            'courses' => Course::all()
        ]);
    }
}
