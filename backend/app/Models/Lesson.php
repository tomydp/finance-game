<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Lesson extends Model
{
    public const STATUS_ACTIVO   = 'activo';
    public const STATUS_INACTIVO = 'inactivo';
    public const STATUSES        = [self::STATUS_ACTIVO, self::STATUS_INACTIVO];

    protected $fillable = ['course_id', 'title', 'description', 'order', 'status'];

    protected $casts = [
        'status' => 'string',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVO,
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class)->orderBy('id');
    }

    public function activeExercises()
    {
        return $this->hasMany(Exercise::class)
            ->where('status', Exercise::STATUS_ACTIVO)
            ->orderBy('id');
    }

    // 🔧 relación a resultados vía ejercicios
    public function results()
    {
        return $this->hasManyThrough(
            \App\Models\Result::class,
            \App\Models\Exercise::class,
            'lesson_id',   // FK en exercises
            'exercise_id', // FK en results
            'id',          // PK en lessons
            'id'           // PK en exercises
        );
    }
    

    public function totalExercises(): int
    {
        return $this->exercises()->count();
    }

    public function completedExercises(int $userId): int
    {
        return $this->results()
            ->where('user_id', $userId)
            ->where('is_correct', 1)
            ->count();
    }
    
    

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'lesson_user')
            ->withPivot(['completed_at'])
            ->withTimestamps();
    }
    
    public function markCompletedFor(int $userId): void
    {
        DB::table('lesson_user')->updateOrInsert(
            ['user_id' => $userId, 'lesson_id' => $this->id],
            ['completed_at' => now(), 'updated_at' => now(), 'created_at' => now()]
        );
    }
    

    // “Desbloquear siguiente”: por ahora, solo devolvemos cuál sería
    public function unlockNextFor(int $userId): ?self
    {
        $next = self::where('course_id', $this->course_id)
            ->where('order', '>', $this->order)
            ->orderBy('order')
            ->first();

        // Si tuvieras tabla de “estados” (unlock), acá la registrarías.
        return $next;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVO);
    }
}
