<?php

namespace App\Livewire;

use App\Models\Course;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateCourse extends Component
{
    public bool $showModal = false;

    public string $name = '';
    public string $description = '';
    public ?string $difficulty = null; // 'facil' | 'medio' | 'dificil' | null
    public string $status = Course::STATUS_ACTIVO;

    public array $statusOptions = Course::STATUSES;

    protected function rules(): array
    {
        return [
            'name'        => ['required','string','max:255'],
            'description' => ['required','string'],
            'difficulty'  => ['required','in:facil,medio,dificil'],
            'status'      => ['required', Rule::in(Course::STATUSES)],
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
        $this->status = Course::STATUS_ACTIVO;
    }

    public function save(): void
    {
        $this->validate();

        Course::create([
            'name'        => $this->name,
            'description' => $this->description,
            'difficulty'  => $this->difficulty,
            'status'      => $this->status,
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
