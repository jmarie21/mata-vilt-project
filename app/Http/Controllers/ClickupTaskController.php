<?php

namespace App\Http\Controllers;

use App\Services\ClickUpService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ClickupTaskController extends Controller
{
    protected $clickupService;

    public function __construct(ClickUpService $clickUpService) {
        $this->clickupService = $clickUpService;
    }

    public function index() {
        try{
            $listId = '901605068772';
            $tasks = $this->clickupService->getTasks($listId);

            Log::info('Raw ClickUp response:', ['response' => $tasks]);
            Log::info('Tasks array:', ['tasks' => $tasks['tasks'] ?? null]);

            return Inertia::render('Tasks', [
                'tasks' => $tasks['tasks'] ?? [],
            ]);
            
        } catch (\Exception $e) {
            Log::error('ClickUp error:', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
