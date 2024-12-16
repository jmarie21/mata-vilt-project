<?php

use App\Jobs\FetchClickupTasks;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('clickup:fetch-tasks', function() {
    $listId = '901605068772';
    FetchClickupTasks::dispatch($listId);
    Log::info('Clickup tasks fetch job dispatched successfully');
    $this->info('Clickup tasks fetch job dispatched successfully');
})->purpose('Fetch clickup tasks and store them')->everyMinute();