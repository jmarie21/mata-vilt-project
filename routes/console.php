<?php

use App\Jobs\FetchClickupTasks;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\ScheduleClickUpTasks;
use App\Services\TaskSchedulerService;

$taskSchedulerService = new TaskSchedulerService();

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();




// Schedule::job(new FetchClickupTasks('901605068772')) // Pass the List ID or dynamically fetch it
//     ->everyFiveMinutes(); // Use the dynamic schedule method from TaskSchedulerService


Schedule::job(new FetchClickupTasks('901605068772')) // Pass the List ID or dynamically fetch it
->{$taskSchedulerService->getScheduleMethod()}(); // Use the dynamic schedule method from TaskSchedulerService
