<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Testing\Fluent\Concerns\Has;

class Simulation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'investment_type',
        'amount',
        'simulated_return',
    ];
}
