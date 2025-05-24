<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Result extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'exercise_id',
        'is_correct',
        'answered_at',
    ];
}
