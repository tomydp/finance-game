<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Exercise extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_id',
        'type',
        'question',
        'options',
        'correct_answer',
    ];

     protected $casts = [
        'options' => 'array',
    ];

    public function checkAnswer(string|array $answer): bool
    {
        // Lógica básica; ajusta según tipos:
        // - multiple_choice / true_false  ➜ comparación directa
        // - fill_blank                    ➜ strtolower, trim, etc.
        return $this->correct_answer === $answer;
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
