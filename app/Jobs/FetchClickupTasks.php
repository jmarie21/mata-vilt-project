<?php

namespace App\Jobs;

use App\Events\TasksFetched;
use App\Models\ClickUpTask;
use App\Services\ClickUpService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FetchClickupTasks implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $listId;
    /**
     * Create a new job instance.
     */
    public function __construct($listId)
    {
        $this->listId = $listId;
    }

    /**
     * Execute the job.
     */
    public function handle(ClickUpService $clickUpService)
    {
        try {
            Log::info('Fetching tasks from ClickUp', ['list_id' => $this->listId]);
            $tasks = $clickUpService->getTasks($this->listId);

            Log::info('ClickUp API response', ['response' => $tasks]);
    
            foreach ($tasks['tasks'] as $task) {

               // Log assignees to verify
                Log::info('Assignees for task', [
                    'task_id' => $task['id'],
                    'assignees' => $task['assignees'] ?? 'No assignees found'
                ]);

                // Safely process assignees
                $assignees = isset($task['assignees']) && is_array($task['assignees'])
                    ? implode(', ', array_map(fn($assignee) => $assignee['username'] ?? 'Unknown', $task['assignees']))
                    : 'No assignees';

                // Process and convert the time spent (milliseconds to minutes or hours)
                $timeSpent = $task['time_spent'] ?? 0; // Default to 0 if not provided
                $formattedTimeSpent = $this->formatTimeSpent($timeSpent);

                Log::info('Formatted Time:', ['formatted_time' => $formattedTimeSpent]);

               
                ClickUpTask::updateOrCreate(
                    ['task_id' => $task['id']],
                    [
                        'name' => $task['name'],
                        'description' => $task['description'] ?? '',
                        'assignees' => $assignees,
                        'time_spent' => $timeSpent, // Save the formatted time
                        'formatted_time_spent' => $formattedTimeSpent, // Save the formatted time spent
                        'status' => $task['status']['status'],  
                        'creator' => $task['creator']['username'] ?? '',
                    ]
                );
            }

            // Fetch the latest tasks from the database after the update
            $updatedTasks = ClickUpTask::all()->map(function ($task) {
                $task->formatted_time_spent = $this->formatTimeSpent($task->time_spent);
                return $task;
            });

            // Broadcast the updated tasks to the frontend
            broadcast(new TasksFetched($updatedTasks));
    
            Log::info('Fetched and stored ClickUp tasks successfully');
        } catch (\Exception $e) {
            Log::error('Failed to fetch ClickUp tasks', ['error' => $e->getMessage()]);
        }
    }

    

    private function formatTimeSpent($milliseconds)
    {
        // Convert to seconds
        $seconds = $milliseconds / 1000;
    
        // Calculate hours and minutes
        $hours = floor($seconds / 3600); // Calculate full hours
        $minutes = floor(($seconds % 3600) / 60); // Calculate remaining minutes
    
        Log::info('Formatting Time', ['milliseconds' => $milliseconds, 'seconds' => $seconds, 'hours' => $hours, 'minutes' => $minutes]);
    
        // If hours are present, format accordingly
        if ($hours > 0) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ($minutes > 0 ? ' and ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') : '');
        }
    
        // If no hours, just show minutes
        return $minutes . ' minute' . ($minutes > 1 ? 's' : '');
    }
    


}
