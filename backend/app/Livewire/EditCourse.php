<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class EditCourse extends Component
{
    public $showModal = false;

    public $courseId;
    public $name;
    public $description;
    public $difficulty;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'difficulty' => 'required|string',
    ];

    protected $listeners = ['editCourse' => 'loadCourse'];

    public function loadCourse($id)
    {
        $course = Course::findOrFail($id);

        $this->courseId = $course->id;
        $this->name = $course->name;
        $this->description = $course->description;
        $this->difficulty = $course->difficulty;

        $this->showModal = true;
    }

    public function update()
    {
        $this->validate();

        Course::findOrFail($this->courseId)->update([
            'name' => $this->name,
            'description' => $this->description,
            'difficulty' => $this->difficulty,
        ]);

        $this->reset(['courseId', 'name', 'description', 'difficulty', 'showModal']);

        $this->dispatch('courseUpdated')->to(ShowCourse::class);
    }

    public function render()
    {
        return view('livewire.edit-course');
    }

    public function closeModal()
{
    $this->reset(['courseId', 'name', 'description', 'difficulty', 'showModal']);
    $this->resetErrorBag();
    $this->resetValidation();
}

}