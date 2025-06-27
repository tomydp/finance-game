<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;

class EditLesson extends Component
{
    public $showModal = false;

    public $lessonId;
    public $name;
    public $description;
    public $course_id;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'course_id' => 'required|exists:courses,id',
    ];

    protected $listeners = ['editLesson' => 'loadLesson'];

    public function loadLesson($id)
    {
        $lesson = Lesson::findOrFail($id);

        $this->lessonId = $lesson->id;
        $this->name = $lesson->name;
        $this->description = $lesson->description;
        $this->course_id = $lesson->course_id;

        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        Lesson::findOrFail($this->lessonId)->update([
            'name' => $this->name,
            'description' => $this->description,
            'course_id' => $this->course_id,
        ]);

        $this->reset(['lessonId', 'name', 'description', 'course_id', 'showModal']);

        $this->dispatch('lessonUpdated')->to(ShowLesson::class);
    }
    public function closeModal()
    {
        $this->reset(['lessonId', 'name', 'description', 'course_id', 'showModal']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.edit-lesson');
    }
}
