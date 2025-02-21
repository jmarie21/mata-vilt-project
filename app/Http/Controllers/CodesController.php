<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use Inertia\Inertia;

class CodesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $codes = Code::select('code', 'created_at')->orderBy('created_at', 'desc')->get()->map(function ($code) {
            $code->created_at_formatted = $code->created_at->format('Y-m-d');
            return $code;
        });

        return Inertia::render('Codes', [
            'codes' => $codes,
        ]);
    }

    public function generateCode()
    {
        $code = $this->generateUniqueCode();

        // Save the code to the database
        Code::create([
            'code' => $code,
        ]);

        // Fetch the updated list of codes
        $codes = Code::select('code', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($code) {
                $code->created_at_formatted = $code->created_at->format('Y-m-d');
                return $code;
        });

        // Return the updated data to the frontend
        return Inertia::render('Codes', [
            'codes' => $codes,
        ]);
    }

    public function generateUniqueCode() {
        do {
            $code = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
        } while (Code::where('code', $code)->exists());

        return $code;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
