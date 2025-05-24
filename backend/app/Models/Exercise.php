<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Exercise extends Model
{
    use HasFactory;
    protected $fillable = [
        'lesson_id',
        'type',
        'question',
        'options',
        'correct_answer',
    ];
}
