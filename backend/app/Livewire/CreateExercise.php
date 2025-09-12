<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;
use Livewire\Component;

class CreateExercise extends Component
{
    public bool $showModal = false;

    public ?int $courseId = null;
    /** Puede ser '' para quedar en "Seleccionar…" */
    public $lessonId = '';

    public string $type = 'mcq';      // 'mcq' | 'true_false' | 'fill_blank'
    public string $question = '';

    // MCQ
    public array $options = ['', '', '', ''];
    public ?int $correctIndex = null;

    // True/False
    public ?bool $answerBool = null;

    // Fill blank
    public array $answersFill = [''];

    // Catálogos
    public array $courses = [];
    public array $lessons = [];

    protected function rules(): array
    {
        $base = [
            'courseId' => ['required','exists:courses,id'],
            'lessonId' => ['required','exists:lessons,id'],
            'type'     => ['required','in:mcq,true_false,fill_blank'],
            'question' => ['required','string','max:2000'],
        ];

        return match ($this->type) {
            'mcq' => $base + [
                'options'      => ['array','size:4'],
                'options.*'    => ['required','string','max:255'],
                'correctIndex' => ['required','integer','between:0,3'],
            ],
            'true_false' => $base + [
                'answerBool'   => ['required','boolean'],
            ],
            'fill_blank' => $base + [
                'answersFill'  => ['array','min:1'],
                'answersFill.*'=> ['required','string','max:255'],
            ],
        };
    }

    public function mount(): void
    {
        $this->courses = Course::orderBy('name')->get(['id','name'])->toArray();
        $this->refreshLessons();
    }

    /** Abre SIEMPRE vacío */
    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    /** Limpia campos y repuebla selects */
    private function resetForm(): void
    {
        $this->courseId     = null;
        $this->lessonId     = '';
        $this->type         = 'mcq';
        $this->question     = '';
        $this->options      = ['', '', '', ''];
        $this->correctIndex = null;
        $this->answerBool   = null;
        $this->answersFill  = [''];
        $this->refreshLessons();
    }

    /** Change handler explícito: deja "Seleccionar…" y repuebla */
    public function handleCourse(string $value): void
    {
        $this->courseId = $value !== '' ? (int) $value : null;
        $this->lessonId = '';
        $this->refreshLessons();
    }

    private function refreshLessons(): void
    {
        $this->lessons = $this->courseId
            ? Lesson::where('course_id', $this->courseId)->orderBy('order')->get(['id','title'])->toArray()
            : [];
    }

    public function save(): void
    {
        $this->validate();

        [$options, $correct] = match ($this->type) {
            'mcq' => [
                array_map('trim', $this->options),
                (string) ($this->options[$this->correctIndex] ?? ''),
            ],
            'true_false' => [[], $this->answerBool ? 'true' : 'false'],
            'fill_blank' => [
                [],
                json_encode(
                    array_values(array_filter(array_map('trim', $this->answersFill), fn($s) => $s !== '')),
                    JSON_UNESCAPED_UNICODE
                ),
            ],
        };

        Exercise::create([
            'lesson_id'      => (int) $this->lessonId,
            'type'           => $this->type,
            'question'       => $this->question,
            'options'        => $options,
            'correct_answer' => $correct,
        ]);

        $this->dispatch('exerciseCreated');

        // Cerrar y dejar todo limpio
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetForm();
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.create-exercise', [
            'courses' => $this->courses,
            'lessons' => $this->lessons,
        ]);
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetForm();   // ← limpia todo aunque no guarde
        $this->showModal = false;
    }
}
