<?php

namespace App\Http\Controllers;

use App\Services\ClickUpService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Jobs\FetchClickUpTasks;
use App\Models\ClickUpTask;

class ClickupTaskController extends Controller
{
    protected $clickupService;

    public function __construct(ClickUpService $clickUpService) {
        $this->clickupService = $clickUpService;
    }

    public function index() {
        $tasks = ClickUpTask::all();

        return Inertia::render('Tasks', [
            'tasks' => $tasks
        ]);
    }

    public function fetchTasksDirectly() {
        $listId = '901605068772';
        FetchClickupTasks::dispatch($listId);
        Log::info('Clickup tasks fetch job dispatched directly successfully');
        return back()->with('success', 'Clickup tasks fetch job dispatched successfully');
    }
}
