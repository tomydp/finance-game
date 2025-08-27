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
    public $listeners = ['lessonCreated' => '$refresh', 'lessonUpdated' => '$refresh'];

    public function editLesson($id)
    {
        $this->dispatch('editLesson', id: $id)->to(\App\Livewire\EditLesson::class);
    }

    public function render()
    {
        return view('livewire.show-lesson', [
            'lessons' => Lesson::with('course')->orderBy('id')->paginate(5),
            'courses' => Course::all(),
        ]);
    }
}
