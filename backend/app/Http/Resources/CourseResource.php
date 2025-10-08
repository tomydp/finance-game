<?php

namespace App\Http\Resources;

use App\Models\Lesson;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        $user = $request->user();
        $completedAtIso = null;
        $completed = null;

        if ($user) {
            $completedAt = $this->completedAtFor($user->id);
            $completed = (bool) $completedAt;
            $completedAtIso = $completedAt ? $completedAt->toISOString() : null;
        }

        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'description'    => $this->description,
            'difficulty'     => $this->difficulty,     // 'facil'|'medio'|'dificil'
            'status'         => $this->status,
            'lessons_count'  => (int) ($this->lessons_active_count ?? $this->lessons()->where('status', Lesson::STATUS_ACTIVO)->count()),
            'created_at'     => optional($this->created_at)->toISOString(),
            'completed'      => $completed,
            'completed_at'   => $completedAtIso,
        ];
    }
}
