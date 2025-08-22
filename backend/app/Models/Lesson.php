<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'order',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class)->orderBy('id');
    }
    
    public function completedExercises(int $userId): int
{
    return $this->results()
                ->where('user_id', $userId)
                ->where('is_correct', 1)
                ->count();
}

}
