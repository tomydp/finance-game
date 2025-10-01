<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        $totalExercises = (int) ($this->exercises_count ?? 0);

        $user = $request->user();
        $completed = $user
            ? (int) $this->completedExercises($user->id) // usa la relación hasManyThrough
            : null;

        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'order'              => (int) $this->order,
            'exercises_count'    => $totalExercises,
            'completed_exercises'=> $user ? $completed : null,
            'progress_percent'   => ($user && $totalExercises > 0)
                ? (int) round(($completed / $totalExercises) * 100)
                : null,
        ];
    }
}
