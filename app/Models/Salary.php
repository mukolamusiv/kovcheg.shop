<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'salary_date',
        'notes',
        'status',
        'productions',
    ];

    protected $casts = [
        'productions' => 'array',
        'salary_date' => 'date',
    ];
}
