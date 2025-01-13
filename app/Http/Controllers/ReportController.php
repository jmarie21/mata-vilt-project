<?php

namespace App\Http\Controllers;

use App\Models\ClickUpTask;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index() {
        $tasks = ClickUpTask::all();

        // Calculate total time spent per assignee
        $assignees = [];
        foreach ($tasks as $task) {
            foreach (explode(', ', $task->assignees) as $assignee) {
                if (!isset($assignees[$assignee])) {
                    $assignees[$assignee] = 0;
                }
                $assignees[$assignee] += $task->time_spent;
            }
        }


        // Convert time spent into formatted hours/minutes
        $formattedAssignees = [];
        foreach ($assignees as $assignee => $totalTimeSpent) {
            $formattedAssignees[$assignee] = $this->formatTimeSpent($totalTimeSpent);
        }

        return inertia('Reports', [
            'assignees' => $formattedAssignees,
        ]);
    }

    private function formatTimeSpent($milliseconds)
    {
        $seconds = $milliseconds / 1000;
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0) {
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ($minutes > 0 ? ' and ' . $minutes . ' minute' . ($minutes > 1 ? 's' : '') : '');
        }

        return $minutes . ' minute' . ($minutes > 1 ? 's' : '');
    }
}
