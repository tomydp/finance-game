<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

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
}
