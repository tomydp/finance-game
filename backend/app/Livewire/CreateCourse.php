<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class CreateCourse extends Component
{
    public $name, $description, $difficulty;
    public $showModal = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'difficulty' => 'required|string',
    ];

    public function save()
    {
        $this->validate();

        Course::create([
            'name' => $this->name,
            'description' => $this->description,
            'difficulty' => $this->difficulty,
        ]);

        $this->dispatch('courseCreated');

        $this->reset(['name', 'description', 'difficulty', 'showModal']);
    }
    public function render()
    {
        $difficulties = ['Facil', 'Medio', 'Dificil'];
        return view('livewire.create-course',compact('difficulties'));
    }


}
