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

    public $listeners = ['lessonCreated' => '$refresh', 'lessonUpdated' => '$refresh'];

    public function mount(): void
    {
        $this->basePath = url()->current();
    }

    public function render()
    {
        $lessons = Lesson::with('course')
            ->orderBy('id')
            ->paginate(5, ['*'], $this->pageName);

        $lessons->withPath($this->basePath);

        return view('livewire.show-lesson', [
            'lessons' => $lessons,
            'courses' => Course::all(),
        ]);
    }
}
