<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    public const STATUS_ACTIVO   = 'activo';
    public const STATUS_INACTIVO = 'inactivo';
    public const STATUSES        = [self::STATUS_ACTIVO, self::STATUS_INACTIVO];

    protected $fillable = ['name','description','difficulty','status'];

    protected $casts = [
        'status' => 'string',
    ];

    protected $attributes = [
        'status' => self::STATUS_ACTIVO,
    ];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function activeLessons()
    {
        return $this->hasMany(Lesson::class)
            ->where('status', Lesson::STATUS_ACTIVO)
            ->orderBy('order');
    }

    public function users()
    {
        return $this->belongsToMany(\App\Models\User::class, 'course_user')
            ->withPivot(['completed_at'])
            ->withTimestamps();
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVO);
    }

    // Opcionales (si querés testear o reutilizar)
    public function scopeDifficulty($q, ?string $difficulty)
    {
        return $difficulty ? $q->where('difficulty', $difficulty) : $q;
    }

    public function scopeSearch($q, ?string $search)
    {
        if (!$search) return $q;
        $term = "%".mb_strtolower($search)."%";
        return $q->where(function ($w) use ($term) {
            $w->whereRaw('LOWER(name) LIKE ?', [$term])
              ->orWhereRaw('LOWER(description) LIKE ?', [$term]);
        });
    }

    public function markCompletedFor(int $userId): void
    {
        DB::table('course_user')->updateOrInsert(
            ['user_id' => $userId, 'course_id' => $this->id],
            [
                'completed_at' => now(),
                'updated_at'   => now(),
                'created_at'   => now(),
            ]
        );
    }

    public function isCompletedByUser(int $userId): bool
    {
        if ($this->relationLoaded('users')) {
            return (bool) $this->users->first(fn ($user) => $user->id === $userId);
        }

        return DB::table('course_user')
            ->where('course_id', $this->id)
            ->where('user_id', $userId)
            ->exists();
    }

    public function completedAtFor(int $userId): ?Carbon
    {
        if ($this->relationLoaded('users')) {
            $user = $this->users->first(fn ($u) => $u->id === $userId);
            if ($user && $user->pivot) {
                $completed = $user->pivot->completed_at;
                return $completed ? Carbon::parse($completed) : null;
            }
        }

        $timestamp = DB::table('course_user')
            ->where('course_id', $this->id)
            ->where('user_id', $userId)
            ->value('completed_at');

        return $timestamp ? Carbon::parse($timestamp) : null;
    }
}
