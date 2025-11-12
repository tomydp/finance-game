<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        $totalExercises = (int) ($this->exercises_count ?? 0);

        $user = $request->user('sanctum') ?? $request->user();
        $completedExercises = $user
            ? (int) $this->completedExercises($user->id) // usa la relación hasManyThrough
            : null;

        $progressPercent = ($user && $totalExercises > 0 && $completedExercises !== null)
            ? (int) round(($completedExercises / max(1, $totalExercises)) * 100)
            : null;

        $completed = false;
        if ($user) {
            if ($this->relationLoaded('users')) {
                $existing = $this->users->firstWhere('id', $user->id);
                $completed = (bool) ($existing?->pivot?->completed_at);
            }

            if (!$completed) {
                $completed = $this->users()
                    ->where('users.id', $user->id)
                    ->wherePivotNotNull('completed_at')
                    ->exists();
            }

            if (!$completed && $totalExercises > 0 && $completedExercises !== null) {
                $completed = $completedExercises >= $totalExercises;
            }
        }

        return [
            'id'                 => $this->id,
            'title'              => $this->title,
            'order'              => (int) $this->order,
            'exercises_count'    => $totalExercises,
            'completed'          => $completed,
            'completed_exercises'=> $user ? $completedExercises : null,
            'progress_percent'   => $progressPercent,
        ];
    }
}
