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
    
     protected $casts = [
        'is_correct'  => 'boolean',
        'answered_at' => 'datetime',
    ];

    public function exercise()
    {
    return $this->belongsTo(\App\Models\Exercise::class);
    }

    public function user()
    {
    return $this->belongsTo(\App\Models\User::class);
    }

}
