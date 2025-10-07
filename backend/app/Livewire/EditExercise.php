<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditExercise extends Component
{
    public int $exerciseId;
    public bool $isOpen = false;

    public ?int $courseId = null;
    public ?int $lessonId = null;

    /** @var 'mcq'|'true_false'|'fill_blank' */
    public string $editType = 'mcq';
    public string $question = '';

    // dinámicos
    public array $options = ['', '', '', ''];
    public ?int $correctIndex = null;
    public ?bool $answerBool = null;
    public array $answersFill = [''];

    // NUEVO
    public ?string $explanation_md = null;

    public string $status = Exercise::STATUS_ACTIVO;
    public array $statusOptions = Exercise::STATUSES;

    /** catálogos */
    public array $courses = [];
    public array $lessons = [];

    /** para forzar remount si hace falta */
    public int $uiNonce = 0;

    public function mount(int $exerciseId): void
    {
        $this->exerciseId = $exerciseId;
        $this->courses = Course::orderBy('name')->get(['id','name','status'])->toArray();
    }

    protected function rules(): array
    {
        $base = [
            'courseId'       => ['required','exists:courses,id'],
            'lessonId'       => ['required','exists:lessons,id'],
            'editType'       => ['required','in:mcq,true_false,fill_blank'],
            'question'       => ['required','string','max:2000'],
            'explanation_md' => ['nullable','string','max:20000'],
            'status'         => ['required', Rule::in(Exercise::STATUSES)],
        ];

        return match ($this->editType) {
            'mcq' => $base + [
                'options'      => ['array','size:4'],
                'options.*'    => ['required','string','max:255'],
                'correctIndex' => ['required','integer','between:0,3'],
            ],
            'true_false' => $base + [
                'answerBool'   => ['required','boolean'],
            ],
            'fill_blank' => $base + [
                'answersFill'   => ['array','min:1'],
                'answersFill.*' => ['required','string','max:255'],
            ],
        };
    }

    protected function loadLessons(): void
    {
        $this->lessons = $this->courseId
            ? Lesson::where('course_id', $this->courseId)->orderBy('order')->get(['id','title','status'])->toArray()
            : [];
    }

    protected function loadFromDb(): void
    {
        $e = Exercise::with('lesson.course')->findOrFail($this->exerciseId);

        $this->courseId = $e->lesson?->course_id;
        $this->lessonId = $e->lesson_id;
        $this->editType = $e->type;      // bloqueado en UI
        $this->question = $e->question;

        $this->explanation_md = $e->explanation_md; // ✅
        $this->status         = $e->status;

        $this->loadLessons();

        if ($this->editType === 'mcq') {
            $this->options = array_values(($e->options ?? ['', '', '', '']) + [0=>'',1=>'',2=>'',3=>'']);
            $correctText   = (string) $e->correct_answer;
            $idx = collect($this->options)->search($correctText, true);
            $this->correctIndex = $idx === false ? null : $idx;
            $this->answerBool   = null;
            $this->answersFill  = [''];
        } elseif ($this->editType === 'true_false') {
            $this->answerBool   = strtolower((string)$e->correct_answer) === 'true';
            $this->options      = ['', '', '', ''];
            $this->correctIndex = null;
            $this->answersFill  = [''];
        } else { // fill_blank
            $decoded = json_decode((string)$e->correct_answer, true);
            $this->answersFill = is_array($decoded) && count($decoded) ? array_values($decoded) : [''];
            $this->options      = ['', '', '', ''];
            $this->correctIndex = null;
            $this->answerBool   = null;
        }
    }

    public function openModal(): void
    {
        $this->loadFromDb();
        $this->resetErrorBag();
        $this->resetValidation();
        $this->uiNonce = 0;
        $this->isOpen = true;
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->isOpen = false;
    }

    public function handleCourse($id): void
    {
        $this->courseId = $id ? (int)$id : null;
        $this->lessonId = null;
        $this->loadLessons();
    }

    public function makeBlank(?int $start = null, ?int $end = null, string $selected = ''): void
    {
        $token = '[[BLANK]]';
        if (str_contains((string) $this->question, $token)) return;

        $text = (string) $this->question;
        $s    = max(0, (int) ($start ?? 0));
        $e    = max($s, (int) ($end ?? $s));

        $this->question = mb_substr($text, 0, $s) . $token . mb_substr($text, $e);

        $selected = trim($selected);
        if ($selected !== '') {
            $this->answersFill = [$selected];
        }
    }

    public function clearBlank(): void
    {
        $this->question = str_replace('[[BLANK]]', '', (string) $this->question);
    }

    public function update(): void
    {
        $this->validate();

        if ($this->editType === 'fill_blank' && !str_contains((string)$this->question, '[[BLANK]]')) {
            $this->addError('question', 'Usá “Insertar hueco” para marcar dónde se responde.');
            return;
        }

        [$options, $correct] = match ($this->editType) {
            'mcq' => [
                array_map('trim', $this->options),
                (string) ($this->options[$this->correctIndex] ?? ''),
            ],
            'true_false' => [null, $this->answerBool ? 'true' : 'false'],
            'fill_blank' => [
                null,
                json_encode([trim((string)($this->answersFill[0] ?? ''))], JSON_UNESCAPED_UNICODE),
            ],
        };

        Exercise::whereKey($this->exerciseId)->update([
            'lesson_id'       => $this->lessonId,
            'type'            => $this->editType,
            'question'        => $this->question,
            'options'         => $options,
            'correct_answer'  => $correct,
            'explanation_md'  => $this->explanation_md, // ✅
            'status'          => $this->status,
        ]);

        $this->dispatch('exerciseUpdated');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.edit-exercise');
    }
}
