<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lesson;
use App\Models\Course;

class ShowLesson extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    protected string $pageName = 'lessonsPage';

    public string $basePath = '/lessons';

    /** Buscador */
    public string $search = '';

    public $listeners = ['lessonCreated' => '$refresh', 'lessonUpdated' => '$refresh'];

    public function mount(): void
    {
        $this->basePath = url()->current();
    }

    /** Al cambiar el término de búsqueda, volver a la página 1 */
    public function updatingSearch(): void
    {
        $this->resetPage($this->pageName);
    }

    public function render()
    {
        $q = Lesson::with('course');

        if ($this->search !== '') {
            $s = '%' . trim($this->search) . '%';
            $q->where(function ($qq) use ($s) {
                $qq->where('title', 'like', $s)
                   ->orWhereHas('course', fn($c) => $c->where('name', 'like', $s));
            });
        }

        $lessons = $q->orderBy('id')
            ->paginate(5, ['*'], $this->pageName);

        $lessons->withPath($this->basePath);

        return view('livewire.show-lesson', [
            'lessons' => $lessons,
            'courses' => Course::all(),
        ]);
    }
}
