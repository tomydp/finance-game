<?php

namespace App\Livewire;

use App\Models\Exercise;
use Livewire\Component;
use Livewire\WithPagination;

class ShowExercise extends Component
{
    use WithPagination;

    // refresca el listado cuando se crea/edita
    protected $listeners = [
        'exerciseCreated' => '$refresh',
        'exerciseUpdated' => '$refresh',
    ];

    /** Buscador por enunciado */
    public string $search = '';

    /** Al cambiar el término de búsqueda, volver a la página 1 */
    public function updatingSearch(): void
    {
        $this->resetPage('exercisePage');
    }

    public function render()
    {
        $q = Exercise::with('lesson.course');

        if ($this->search !== '') {
            $s = '%' . trim($this->search) . '%';
            $q->where('question', 'like', $s);
        }

        $exercises = $q->orderBy('id')
            ->paginate(5, ['*'], 'exercisePage');

        return view('livewire.show-exercise', compact('exercises'));
    }
}
