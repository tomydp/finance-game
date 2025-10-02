<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Exercise;
use Livewire\Component;

class CreateExercise extends Component
{
    /** Paso 0: lista de tipos / Paso 1: modal del formulario */
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

    // Fill blank
    public array $answersFill = [''];

    // Catálogos
    public array $courses = [];
    public array $lessons = [];

    /** Nonce para identidad de UI (por seguridad) */
    public int $uiNonce = 0;

    public function mount(): void
    {
        $this->courses = Course::orderBy('name')->get(['id','name'])->toArray();
        $this->refreshLessons();
    }

    /* =====================  UI FLOW  ===================== */

    public function openTypePicker(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->showModal = false;
        $this->showTypePicker = true;
    }

    public function closeTypePicker(): void
    {
        $this->showTypePicker = false;
    }

    public function chooseType(string $type): void
    {
        if (!in_array($type, ['mcq','true_false','fill_blank'], true)) {
            return;
        }

        // Reseteamos el form pero respetando el tipo elegido
        $this->resetFormForType($type);

        $this->showTypePicker = false;
        $this->showModal = true;

        // Cambiamos identidad visual del modal
        $this->uiNonce++;
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        // no tocamos el tipo para que al reabrir puedas elegir otro desde la lista
        $this->showModal = false;
    }

    /* =====================  HANDLERS  ===================== */

    public function handleCourse(string $value): void
    {
        $this->courseId = $value !== '' ? (int) $value : null;
        // Convención: al cambiar el padre, resetear el hijo
        $this->lessonId = '';
        $this->refreshLessons();
    }

    /** Fill-blank: agregar / quitar (sin permitir borrar el último) */
    public function addFillAnswer(): void
    {
        $this->answersFill[] = '';
        $this->uiNonce++;
    }

    public function removeFillAnswer(int $index): void
    {
        if (count($this->answersFill) <= 1) {
            return; // no se puede borrar la única
        }
        if (isset($this->answersFill[$index])) {
            array_splice($this->answersFill, $index, 1);
            $this->uiNonce++;
        }
    }

    /* =====================  VALIDACIÓN  ===================== */

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
                'answersFill'   => ['array','min:1'],
                'answersFill.*' => ['required','string','max:255'],
            ],
        };
    }

    /* =====================  PERSISTENCIA  ===================== */

    public function save(): void
    {
        $this->validate();
    
        [$options, $correct] = match ($this->type) {
            'mcq' => [array_map('trim', $this->options), (string) ($this->options[$this->correctIndex] ?? '')],
            'true_false' => [[], $this->answerBool ? 'true' : 'false'],
            'fill_blank' => [[], json_encode(
                array_values(array_filter(array_map('trim', $this->answersFill), fn($s) => $s !== '')),
                JSON_UNESCAPED_UNICODE
            )],
        };
    
        \App\Models\Exercise::create([
            'lesson_id'      => (int) $this->lessonId,
            'type'           => $this->type,
            'question'       => $this->question,
            'options'        => $options,
            'correct_answer' => $correct,
        ]);
    
        // Notifica al listado si lo necesitas
        $this->dispatch('exerciseCreated');
    
        // Cerrar todo después de guardar
        $this->showModal = false;
        $this->showTypePicker = false;
    }
    

    /* =====================  RENDER  ===================== */

    public function render()
    {
        return view('livewire.create-exercise', [
            'courses' => $this->courses,
            'lessons' => $this->lessons,
        ]);
    }

    /* =====================  HELPERS  ===================== */

    private function resetFormForType(string $type): void
    {
        $this->type         = $type;
        $this->courseId     = null;
        $this->lessonId     = '';
        $this->question     = '';

        // Reset campos específicos
        $this->options      = ['', '', '', ''];
        $this->correctIndex = null;
        $this->answerBool   = null;
        $this->answersFill  = [''];

        $this->refreshLessons();
    }

    private function refreshLessons(): void
    {
        $this->lessons = $this->courseId
            ? Lesson::where('course_id', $this->courseId)->orderBy('order')->get(['id','title'])->toArray()
            : [];
    }
}
