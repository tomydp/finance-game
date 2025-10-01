<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = ['name','description','difficulty'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
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
