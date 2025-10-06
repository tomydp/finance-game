<?php

namespace App\Livewire;

use App\Models\Exercise;
use Livewire\Component;
use Livewire\WithPagination;

class ShowExercise extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    protected string $pageName = 'exercisePage';

    /** Asegura que los links apunten a /exercises (no a /livewire/update) */
    public string $basePath = '/exercises';

    protected $listeners = [
        'exerciseCreated' => '$refresh',
        'exerciseUpdated' => '$refresh',
    ];

    public function mount(): void
    {
        // En el primer render (GET) esto será la URL real del listado
        $this->basePath = url()->current();
    }

    public function render()
    {
        $exercises = Exercise::with('lesson.course')
            ->orderBy('id')                            // ascendente como lo pediste
            ->paginate(5, ['*'], $this->pageName);     // usa el pageName “exercisePage”

        // Fuerza la ruta base del paginador
        $exercises->withPath($this->basePath);

        return view('livewire.show-exercise', compact('exercises'));
    }
}
