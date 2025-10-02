<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;

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

    public array $courses = [];
    public array $lessons = [];

    public int $uiNonce = 0;

    public function mount(int $exerciseId): void
    {
        $this->exerciseId = $exerciseId;
        $this->courses = Course::orderBy('name')->get(['id','name'])->toArray();
    }

    protected function rules(): array
    {
        $base = [
            'courseId' => ['required','exists:courses,id'],
            'lessonId' => ['required','exists:lessons,id'],
            'editType' => ['required','in:mcq,true_false,fill_blank'],
            'question' => ['required','string','max:2000'],
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
            ? Lesson::where('course_id', $this->courseId)->orderBy('order')->get(['id','title'])->toArray()
            : [];
    }

    protected function loadFromDb(): void
    {
        $e = Exercise::with('lesson.course')->findOrFail($this->exerciseId);

        $this->courseId = $e->lesson?->course_id;
        $this->lessonId = $e->lesson_id;
        $this->editType = $e->type;
        $this->question = $e->question;

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
        } else {
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
        // (opcional) $this->uiNonce++; $this->dispatch('$refresh');
    }

    public function updatedLessonId($value): void
    {
        $this->uiNonce++;
        $this->dispatch('$refresh');
    }

    public function updatedEditType(string $value): void
    {
        $this->resetErrorBag();
        $this->resetValidation();

        if ($value === 'mcq') {
            $this->options      = ['', '', '', ''];
            $this->correctIndex = null;
            $this->answerBool   = null;
            $this->answersFill  = [''];
        } elseif ($value === 'true_false') {
            $this->answerBool   = null;
            $this->options      = ['', '', '', ''];
            $this->correctIndex = null;
            $this->answersFill  = [''];
        } else {
            $this->answersFill  = [''];
            $this->options      = ['', '', '', ''];
            $this->correctIndex = null;
            $this->answerBool   = null;
        }

        $this->uiNonce++;            // remount
        $this->dispatch('$refresh'); // refresh explícito
    }

    // Fill-blank helpers
    public function addFillAnswer(): void
    {
        $this->answersFill[] = '';
        $this->uiNonce++;
        $this->dispatch('$refresh');
    }

    public function removeFillAnswer(int $index): void
    {
        if (count($this->answersFill) <= 1) return;

        if (isset($this->answersFill[$index])) {
            array_splice($this->answersFill, $index, 1);
            $this->uiNonce++;
            $this->dispatch('$refresh');
        }
    }

    public function update(): void
    {
        $this->validate();

        [$options, $correct] = match ($this->editType) {
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
            'type'           => $this->editType,
            'question'       => $this->question,
            'options'        => $options,
            'correct_answer' => $correct,
        ]);

        $this->dispatch('exerciseUpdated');
        $this->closeModal();
    }

    public function render()
    {
        return view('livewire.edit-exercise');
    }
}
