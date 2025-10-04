<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Lesson;
use App\Models\Course;

class EditLesson extends Component
{
    public bool $isOpen = false;

    public int $lessonId;
    public string $title = '';
    public ?int $course_id = null;

    protected $rules = [
        'title'     => ['required','string','max:255'],
        'course_id' => ['required','exists:courses,id'],
    ];

    public function mount(int $lessonId): void
    {
        $this->lessonId = $lessonId;
    }

    public function openModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        $lesson = Lesson::findOrFail($this->lessonId);
        $this->title     = $lesson->title;
        $this->course_id = $lesson->course_id;

        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isOpen = false;
    }

    public function update(): void
    {
        $this->validate();

        Lesson::whereKey($this->lessonId)->update([
            'title'     => $this->title,
            'course_id' => $this->course_id,
        ]);

        $this->dispatch('lessonUpdated'); // ShowLesson ya escucha y hace $refresh
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.edit-lesson', [
            'courses' => Course::orderBy('name')->get(['id','name']),
        ]);
    }
}
