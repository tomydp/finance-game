<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;
use Livewire\WithPagination;

class ShowCourse extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    protected string $pageName = 'coursesPage';

    /** Path base correcto para la paginación (no /livewire/update) */
    public string $basePath = '/courses';


    public $listeners = [
        'courseCreated' => '$refresh',
        'courseUpdated' => '$refresh',
    ];

    public function mount(): void
    {
        // En el primer render (GET) esto será /courses (u otra ruta donde montes el listado)
        $this->basePath = url()->current();
    }

    public function render()
    {
        $q = Course::query();

        $courses = $q->orderBy('id')
            ->paginate(5, ['*'], $this->pageName);

        // Fuerza que los links apunten siempre al path real de la página, no a /livewire/update
        $courses->withPath($this->basePath);

        return view('livewire.show-course', [
            'courses' => $courses,
        ]);
    }
}
