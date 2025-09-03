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
    public ?int $lessonId = null;

    public string $type = 'mcq';      // 'mcq' | 'true_false' | 'fill_blank'
    public string $question = '';

    // MCQ
    public array $options = ['', '', '', ''];
    public ?int $correctIndex = null;

    // True/False
    public ?bool $answerBool = null;

    // Fill blank
    public array $answersFill = [''];

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

    public function updatedCourseId(): void
    {
        $this->lessonId = null;
    }

    public function save(): void
    {
        $this->validate();

        [$options, $correct] = match ($this->type) {
            'mcq' => [
                array_map('trim', $this->options),
                // guardamos el texto de la opción correcta (tu checkAnswer lo compara ok)
                (string) ($this->options[$this->correctIndex] ?? ''),
            ],
            'true_false' => [null, $this->answerBool ? 'true' : 'false'],
            'fill_blank' => [
                null,
                json_encode(
                    array_values(array_filter(array_map('trim', $this->answersFill), fn($s) => $s !== '')),
                    JSON_UNESCAPED_UNICODE
                ),
            ],
        };

        Exercise::create([
            'lesson_id'      => $this->lessonId,
            'type'           => $this->type,
            'question'       => $this->question,
            'options'        => $options,       // cast a array en el modelo
            'correct_answer' => $correct,       // string o json-string (soportado por tu checkAnswer)
        ]);

        $this->dispatch('exerciseCreated');

        $this->reset([
            'showModal','courseId','lessonId','type','question',
            'options','correctIndex','answerBool','answersFill'
        ]);
        $this->type = 'mcq';
        $this->options = ['', '', '', ''];
        $this->answersFill = [''];
    }

    public function render()
    {
        return view('livewire.create-exercise', [
            'courses' => Course::orderBy('name')->get(['id','name']),
            'lessons' => $this->courseId
                ? Lesson::where('course_id', $this->courseId)->orderBy('order')->get(['id','title'])
                : collect(),
        ]);
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showModal = false;
    }
}
