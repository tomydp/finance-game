<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'lesson_id'       => $this->lesson_id,
            'type'            => $this->type,
            'question'        => $this->question,
            'options'         => $this->options,
            'has_explanation' => filled($this->explanation_md),
        ];
    }
}
