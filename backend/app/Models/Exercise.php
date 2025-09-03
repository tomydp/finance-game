<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Exercise extends Model
{
    protected $fillable = [
        'lesson_id',
        'type',           // 'mcq', 'true_false', 'fill_blank'
        'question',
        'options',        // json
        'correct_answer', // string o json-string para MCQ múltiple
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function checkAnswer($answer): bool
    {
        // Evitamos loguear respuestas reales en prod
        $shouldLog = app()->environment(['local', 'testing']) || config('app.debug');
    
        // Intentamos decodificar la respuesta correcta (para MCQ múltiple)
        $decoded = json_decode($this->correct_answer, true);
        $isJson  = json_last_error() === JSON_ERROR_NONE;
    
        if ($isJson) {
            $given   = is_array($answer) ? array_values($answer) : [$answer];
            $correct = is_array($decoded) ? array_values($decoded) : [$decoded];
    
            // normalizamos a string-lower y ordenamos
            $norm = fn($arr) => collect($arr)->map(fn($v) => mb_strtolower(trim((string)$v)))->sort()->values()->all();
            $givenN   = $norm($given);
            $correctN = $norm($correct);
    
            $result = $givenN === $correctN;
    
            if ($shouldLog) {
                Log::debug('Exercise::checkAnswer (MCQ)', [
                    'exercise_id' => $this->id,
                    'type'        => $this->type,
                    'given'       => $givenN,
                    'correct'     => $correctN,
                    'result'      => $result,
                ]);
            }
    
            return $result;
        }
    
        // Texto plano / true_false
        $lhs = mb_strtolower(trim((string) $answer));
        $rhs = mb_strtolower(trim((string) $this->correct_answer));
        $result = $lhs === $rhs;
    
        if ($shouldLog) {
            Log::debug('Exercise::checkAnswer (plain)', [
                'exercise_id' => $this->id,
                'type'        => $this->type,
                'given'       => $lhs,
                'correct'     => $rhs,
                'result'      => $result,
            ]);
        }
    
        return $result;
    }
}
