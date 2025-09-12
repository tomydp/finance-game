<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;
use Livewire\Component;
use Livewire\WithPagination;

class ShowExercise extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';
    protected string $pageName = 'exercisesPage';

    public string $search = '';
    public ?int $courseId = null;
    public ?int $lessonId = null;
    public string $type = '';

    public $listeners = ['exerciseCreated' => '$refresh', 'exerciseUpdated' => '$refresh'];

    public function updatedCourseId(): void
    {
        $this->lessonId = null;
        $this->resetPage($this->pageName);
    }

    public function render()
    {
        $query = Exercise::query()
            ->with(['lesson.course'])
            ->when($this->type !== '', fn ($q) => $q->where('type', $this->type))
            ->when($this->lessonId, fn ($q) => $q->where('lesson_id', $this->lessonId))
            ->when($this->courseId, fn ($q) => $q->whereHas('lesson', fn ($qq) => $qq->where('course_id', $this->courseId)))
            ->when($this->search !== '', fn ($q) => $q->where('question', 'like', '%' . $this->search . '%'))
            ->orderBy('id');

        return view('livewire.show-exercise', [
            'exercises' => $query->paginate(5, ['*'], $this->pageName),
            'courses'   => Course::orderBy('name')->get(['id','name']),
            'lessons'   => $this->courseId
                ? Lesson::where('course_id', $this->courseId)->orderBy('order')->get(['id','title'])
                : collect(),
        ]);
    }
}
