<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClickUpTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'name',
        'description',
        'assignees',
        'time_spent',
        'formatted_time_spent',
        'status',
        'creator',
    ];

    public static function getTotalTimeSpentByAssignee($assignee)
    {
        return self::where('assignees', 'LIKE', '%' . $assignee . '%')
            ->sum('time_spent');
    }

}
