<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Exercise;
use App\Models\Lesson;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateExercise extends Component
{
    public bool $showTypePicker = false;
    public bool $showModal = false;

    public ?int $courseId = null;
    /** '' representa “Seleccionar…” en el UI */
    public $lessonId = '';

    /** @var 'mcq'|'true_false'|'fill_blank' */
    public string $type = 'mcq';
    public string $question = '';

    // MCQ
    public array $options = ['', '', '', ''];
    public ?int  $correctIndex = null;

    // True/False
    public ?bool $answerBool = null;

    // Fill blank (una sola respuesta)
    public array $answersFill = [''];

    // NUEVO
    public ?string $explanation_md = null;

    public string $status = Exercise::STATUS_ACTIVO;
    public array $statusOptions = Exercise::STATUSES;

    // Catálogos
    public array $courses = [];
    public array $lessons = [];

    /** Nonce para reforzar remount de UI dinámico */
    public int $uiNonce = 0;

    protected function rules(): array
    {
        $base = [
            'courseId'       => ['required','exists:courses,id'],
            'lessonId'       => ['required','exists:lessons,id'],
            'type'           => ['required','in:mcq,true_false,fill_blank'],
            'question'       => ['required','string','max:2000'],
            'explanation_md' => ['nullable','string','max:20000'],
            'status'         => ['required', Rule::in(Exercise::STATUSES)],
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
                'answersFill'   => ['array','size:1'],
                'answersFill.0' => ['required','string','max:255'],
            ],
        };
    }

    public function mount(): void
    {
        $this->courses = Course::active()->orderBy('name')->get(['id','name'])->toArray();
        $this->refreshLessons();
    }

    public function openTypePicker(): void
    {
        $this->resetForm();
        $this->showTypePicker = true;
    }
    public function closeTypePicker(): void
    {
        $this->showTypePicker = false;
    }
    public function chooseType(string $type): void
    {
        $this->type = $type;
        $this->uiNonce++;
        $this->showTypePicker = false;
        $this->showModal = true;
    }

    public function handleCourse(string $value): void
    {
        $this->courseId = $value !== '' ? (int) $value : null;
        $this->lessonId = '';
        $this->refreshLessons();
    }

    public function updatedType(string $value): void
    {
        $this->uiNonce++;
        $this->options      = ['', '', '', ''];
        $this->correctIndex = null;
        $this->answerBool   = null;
        $this->answersFill  = [''];
    }

    public function makeBlank(?int $start = null, ?int $end = null, string $selected = ''): void
    {
        $token = '[[BLANK]]';
        if (str_contains((string)$this->question, $token)) return;

        $text = (string) $this->question;
        $s    = max(0, (int)($start ?? 0));
        $e    = max($s, (int)($end ?? $s));

        $this->question = mb_substr($text, 0, $s) . $token . mb_substr($text, $e);

        $selected = trim($selected);
        if ($selected !== '') {
            $this->answersFill = [$selected];
        }

        $this->uiNonce++;
    }

    public function clearBlank(): void
    {
        $this->question = str_replace('[[BLANK]]', '', (string)$this->question);
        $this->uiNonce++;
    }

    public function openModal(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }
    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showModal = false;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->type === 'fill_blank' && !str_contains((string)$this->question, '[[BLANK]]')) {
            $this->addError('question', 'Usá “Insertar hueco” para marcar dónde se responde.');
            return;
        }

        [$options, $correct] = match ($this->type) {
            'mcq' => [
                array_map('trim', $this->options),
                (string) ($this->options[$this->correctIndex] ?? ''),
            ],
            'true_false' => [[], $this->answerBool ? 'true' : 'false'],
            'fill_blank' => [
                [],
                json_encode([trim($this->answersFill[0] ?? '')], JSON_UNESCAPED_UNICODE),
            ],
        };

        Exercise::create([
            'lesson_id'       => (int) $this->lessonId,
            'type'            => $this->type,
            'question'        => $this->question,
            'options'         => $options,
            'correct_answer'  => $correct,
            'explanation_md'  => $this->explanation_md, // ✅
            'status'          => $this->status,
        ]);

        $this->dispatch('exerciseCreated');
        $this->closeModal();
        $this->showTypePicker = false;
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.create-exercise', [
            'courses' => $this->courses,
            'lessons' => $this->lessons,
            'statusOptions' => $this->statusOptions,
        ]);
    }

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
        $this->explanation_md = null; // ✅
        $this->status       = Exercise::STATUS_ACTIVO;
        $this->uiNonce++;
        $this->refreshLessons();
    }

    private function refreshLessons(): void
    {
        $this->lessons = $this->courseId
            ? Lesson::where('course_id', $this->courseId)
                ->active()
                ->orderBy('order')
                ->get(['id','title'])
                ->toArray()
            : [];
    }
}
