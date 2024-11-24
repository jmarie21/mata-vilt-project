<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessCsvFile;
use App\Models\Timesheet;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class TimeSheetController extends Controller
{
    public function index() {
        $timesheets = Timesheet::latest()->get(); 

        return Inertia::render('TimeSheets', [
            'timesheets' => $timesheets 
        ]);
    }

    public function uploadCsv(Request $request) {
        $request->validate([
            'file' => ['required', 'mimes:csv,txt', 'max:2048'],
        ]);

        $file = $request->file('file');
        $fileName = 'timesheet_' . time() . '.csv';
        $path = Storage::disk('public')->putFileAs('timesheets', $file, $fileName);

        if(!$path) {
            return redirect()->back()->with('error', 'Failed to store file');
        }

        $fullPath = Storage::disk('public')->path($path);

        ProcessCsvFile::dispatch($fullPath);

        $timesheets = Timesheet::latest()->get();

        return Inertia::render('TimeSheets', [
            'timesheets' => $timesheets,
            'message' => 'File uploaded successfully!',
        ]);
    }
}