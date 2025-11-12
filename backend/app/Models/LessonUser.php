<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonUser extends Model
{
    protected $table = 'lesson_user';        // nombre de la tabla pivot
    public $timestamps = true;               // tu tabla tiene created_at/updated_at

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];
}
