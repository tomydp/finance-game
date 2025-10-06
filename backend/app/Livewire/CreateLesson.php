<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateLesson extends Component
{
    public ?string $title = null;
    public ?int $course_id = null;
    public string $status = Lesson::STATUS_ACTIVO;

    public bool $showModal = false;

    protected function rules(): array
    {
        return [
            'title'     => ['required','string','max:255'],
            'course_id' => ['required','exists:courses,id'],
            'status'    => ['required', Rule::in(Lesson::STATUSES)],
        ];
    }

    public function openModal(): void
    {
        $this->reset(['title','course_id']);
        $this->resetErrorBag();
        $this->resetValidation();
        $this->status = Lesson::STATUS_ACTIVO;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->reset(['title','course_id','status','showModal']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        Lesson::create([
            'title'     => $this->title,
            'course_id' => $this->course_id,
            'status'    => $this->status,
        ]);

        $this->dispatch('lessonCreated');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.create-lesson', [
            'courses' => Course::active()->orderBy('name')->get(),
            'statusOptions' => Lesson::STATUSES,
        ]);
    }
}
