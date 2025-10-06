<?php

namespace App\Http\Resources;

use App\Models\Lesson;
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
            'status'         => $this->status,
            'lessons_count'  => (int) ($this->lessons_active_count ?? $this->lessons()->where('status', Lesson::STATUS_ACTIVO)->count()),
            'created_at'     => optional($this->created_at)->toISOString(),
        ];
    }
}
