<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'      => $this->id,
            'type'    => $this->type,          // 'mcq' | 'true_false' | 'fill_blank'
            'prompt'  => $this->question,      // renombrado para el frontend
            'options' => $this->options,       // array|null (cast en el modelo)
            // ⚠️ Nunca exponer 'correct_answer'
            'id'               => $this->id,
            'type'             => $this->type,          // p.ej. multiple_choice
            'question'         => $this->question,
            'options'          => $this->options,
            'correct_answer' => $this->correct_answer,     // array (JSON)
        ];
    }
}
