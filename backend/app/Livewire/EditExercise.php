<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;
use Livewire\Component;

class EditExercise extends Component
{
    public bool $showModal = false;

    public int $exerciseId;

    public ?int $courseId = null;
    public ?int $lessonId = null;

    public string $type = 'mcq';
    public string $question = '';

    public array $options = ['', '', '', ''];
    public ?int $correctIndex = null;
    public ?bool $answerBool = null;
    public array $answersFill = [''];

    protected $listeners = ['editExercise' => 'loadExercise'];

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

    public function loadExercise(int $id): void
    {
        $e = Exercise::with('lesson.course')->findOrFail($id);

        $this->exerciseId = $e->id;
        $this->courseId   = $e->lesson->course_id ?? null;
        $this->lessonId   = $e->lesson_id;
        $this->type       = $e->type;
        $this->question   = $e->question;

        if ($this->type === 'mcq') {
            $this->options = array_values(($e->options ?? ['', '', '', '']) + [0=>'',1=>'',2=>'',3=>'']);
            $correctText   = (string) $e->correct_answer;
            $this->correctIndex = collect($this->options)->search($correctText, true);
            if ($this->correctIndex === false) $this->correctIndex = null;
        } elseif ($this->type === 'true_false') {
            $this->answerBool = strtolower((string)$e->correct_answer) === 'true';
        } else {
            $decoded = json_decode((string)$e->correct_answer, true);
            $this->answersFill = is_array($decoded) && count($decoded) ? array_values($decoded) : [''];
        }

        $this->showModal = true;
    }

    public function updatedCourseId(): void
    {
        $this->lessonId = null;
    }

    public function update(): void
    {
        $this->validate();

        [$options, $correct] = match ($this->type) {
            'mcq' => [
                array_map('trim', $this->options),
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

        Exercise::whereKey($this->exerciseId)->update([
            'lesson_id'      => $this->lessonId,
            'type'           => $this->type,
            'question'       => $this->question,
            'options'        => $options,
            'correct_answer' => $correct,
        ]);

        $this->dispatch('exerciseUpdated');
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.edit-exercise', [
            'courses' => Course::orderBy('name')->get(['id','name']),
            'lessons' => $this->courseId
                ? Lesson::where('course_id', $this->courseId)->orderBy('order')->get(['id','title'])
                : collect(),
        ]);
    }
}
