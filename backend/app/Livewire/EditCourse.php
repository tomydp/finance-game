<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;

class EditCourse extends Component
{
    public int $courseId;
    public string $name = '';
    public string $description = '';
    public ?string $difficulty = null; // 'facil' | 'medio' | 'dificil' | null
    public bool $showModal = false;

    protected $listeners = ['editCourse' => 'loadCourse'];

    protected function rules(): array
    {
        return [
            'name' => ['required','string','max:255'],
            'description' => ['required','string'],
            'difficulty' => ['required','in:facil,medio,dificil'],
        ];
    }

    public function loadCourse(int $id): void
    {
        $course = Course::findOrFail($id);

        $this->courseId    = $course->id;
        $this->name        = $course->name;
        $this->description = $course->description;
        $this->difficulty  = $course->difficulty; // ← CLAVE
        $this->showModal   = true;
    }

    public function update(): void
    {
        $this->validate();

        Course::whereKey($this->courseId)->update([
            'name'        => $this->name,
            'description' => $this->description,
            'difficulty'  => $this->difficulty,
        ]);

        $this->dispatch('courseUpdated');
        $this->reset(['showModal']);
    }

    public function render()
    {
        return view('livewire.edit-course');
    }
}
