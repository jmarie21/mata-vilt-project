<?php

namespace App\Http\Controllers;

use App\Models\TaskScheduler;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskSchedulerController extends Controller
{
    public function show()
    {
        // Retrieve the current interval from the database (or use a default)
        $scheduleInterval = TaskScheduler::first()->interval ?? 'everyMinute'; // Default to 'everyMinute'

        return Inertia::render('Config', [
            'scheduleInterval' => $scheduleInterval,
            'flash' => session('success'), 
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'interval' => 'required|string|in:everyMinute,everyFiveMinutes,hourly,daily',
        ]);

        // Update the task scheduler configuration in the database
        TaskScheduler::updateOrCreate(
            ['id' => 1], // Assuming only one record for configuration
            ['interval' => $validated['interval']]
        );

        return redirect()->route('config.show')->with('success', 'Scheduler interval updated successfully!');
    }

    private function scheduleJob()
    {
        // Get the user-defined schedule method
        $scheduleMethod = $this->taskSchedulerService->getScheduleMethod();

        // Schedule the job
        $schedule = app(\Illuminate\Console\Scheduling\Schedule::class);
        $schedule->job(new \App\Jobs\FetchClickupTasks('901605068772'))->{$scheduleMethod}();
    }
}
