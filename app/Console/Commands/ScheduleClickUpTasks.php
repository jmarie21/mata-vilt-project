<?php

namespace App\Console\Commands;

use App\Models\TaskScheduler;
use App\Jobs\FetchClickUpTasks;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleClickUpTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:fetch-clickup-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Schedule the FetchClickUpTasks job based on the user-configured interval';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $scheduleInterval = $this->getScheduleMethod();

        // Call the function to schedule the job dynamically
        $this->scheduleTask($scheduleInterval);
    }

    // Get the schedule method based on the user-configured interval
    public function getScheduleMethod()
    {
        $scheduleInterval = TaskScheduler::first()->interval ?? 'everyMinute'; // Default to every minute

        // Map the interval to a valid scheduling method
        $methods = [
            'everyMinute' => 'everyMinute',
            'everyFiveMinutes' => 'everyFiveMinutes',
            'hourly' => 'hourly',
            'daily' => 'daily',
        ];

        return $methods[$scheduleInterval] ?? 'everyMinute'; // Fallback to 'everyMinute' if no valid method
    }

    // Schedule the job using the selected interval
    public function scheduleTask($scheduleInterval)
    {
        // Schedule the task to be dispatched at the user-defined interval
        $listId = '901605068772'; // Or get dynamically
        \Illuminate\Support\Facades\Artisan::call('schedule:run', [
            function () use ($listId) {
                try {
                    // Dispatch the FetchClickUpTasks job
                    FetchClickUpTasks::dispatch($listId);
                } catch (\Exception $e) {
                    Log::error('Failed to dispatch FetchClickUpTasks job', ['error' => $e->getMessage()]);
                }
            }
        ])->$scheduleInterval(); // Schedule it at the dynamic interval
    }
}
