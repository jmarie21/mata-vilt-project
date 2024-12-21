<?php

namespace App\Services;

use App\Models\TaskScheduler;

class TaskSchedulerService
{
    public function getScheduleMethod()
    {
        // Retrieve the schedule interval from the database
        $scheduleInterval = TaskScheduler::orderBy('created_at', 'desc')->first()->interval ?? 'everyMinute'; 

        // Map the interval to a valid scheduling method
        $methods = [
            'everyMinute' => 'everyMinute',
            'everyFiveMinutes' => 'everyFiveMinutes',
            'hourly' => 'hourly',
            'daily' => 'daily',
        ];

        return $methods[$scheduleInterval] ?? 'everyMinute'; // Default to 'everyMinute' if no valid method is found
    }
}
