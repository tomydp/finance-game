<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'difficulty'     => $this->difficulty,     // 'facil'|'medio'|'dificil'
            'lessons_count'  => (int) ($this->lessons_count ?? $this->lessons()->count()),
            'created_at'     => optional($this->created_at)->toISOString(),
        ];
    }
}
