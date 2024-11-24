<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Timesheet extends Model
{
    use HasFactory;
    protected $fillable = [
        'description',
        'email',
        'duration',
        'start_time'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'duration' => 'float'
    ];
    
}
