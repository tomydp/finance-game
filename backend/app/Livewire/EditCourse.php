<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;

class EditCourse extends Component
{
    public int $courseId;

    public bool $isOpen = false;

    public string $name = '';
    public string $description = '';
    public ?string $difficulty = null;

    public function mount(int $courseId): void
    {
        $this->courseId = $courseId;
    }

    protected function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'description' => ['required','string'],
            'difficulty'  => ['required','in:facil,medio,dificil'],
        ];
    }

    protected function loadFromDb(): void
    {
        $course = Course::findOrFail($this->courseId);

        $this->name        = $course->name;
        $this->description = $course->description;
        $this->difficulty  = $course->difficulty;
    }

    public function openModal(): void
    {
        $this->loadFromDb();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        // Limpia validación y cierra. En la próxima apertura se recarga desde DB.
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isOpen = false;
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
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.edit-course');
    }
}
