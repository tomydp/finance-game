<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;

class CreateCourse extends Component
{
    public bool $showModal = false;

    public string $name = '';
    public string $description = '';
    public ?string $difficulty = null; // 'facil' | 'medio' | 'dificil' | null

    protected function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'description' => ['required','string'],
            'difficulty'  => ['required','in:facil,medio,dificil'],
        ];
    }

    /** Abre SIEMPRE limpio */
    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    /** Cierra y limpia */
    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    private function resetForm(): void
    {
        $this->name = '';
        $this->description = '';
        $this->difficulty = null;
    }

    public function save(): void
    {
        $this->validate();

        Course::create([
            'name'        => $this->name,
            'description' => $this->description,
            'difficulty'  => $this->difficulty,
        ]);

        // Notifica y cierra limpio
        $this->dispatch('courseCreated');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.create-course');
    }
}
