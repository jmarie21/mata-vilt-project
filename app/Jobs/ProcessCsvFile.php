<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Timesheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ProcessCsvFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $filePath;
    /**
     * Create a new job instance.
     */
    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(($handle = fopen($this->filePath, 'r')) !== false) {
            $isHeader = true;

            while(($row = fgetcsv($handle, 1000, ',')) !== false) {
                if($isHeader) {
                    $isHeader = false;
                    continue;
                }

                try {
                    $start_time = $row[11];
                    $start_time_formatted = Carbon::parse($start_time)->format('Y-m-d');

                    $duration = $row[15];
                    list($hours, $minutes, $seconds) = explode(':', $duration);
                    $total_minutes = ($hours * 60) + $minutes + ($seconds / 60);

                    Timesheet::create([
                        'description' => $row[2],
                        'email' => $row[6],
                        'duration' => (int) $total_minutes,
                        'start_time' => $start_time_formatted,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error processing row' . json_encode($row));
                    Log::error($e->getMessage());
                    continue;
                }
            }
            fclose($handle);
        } else {
            Log::error('Failed to open file:' . $this->filePath);
        }
    }
}
