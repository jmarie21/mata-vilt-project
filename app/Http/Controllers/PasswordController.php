<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PasswordController extends Controller
{
    public function showChangePasswordForm() {
        return Inertia::render('FirstLoginPassword');
    }

    public function changePassword(Request $request) {
        $request->validate([
            'password' => ['required', 'confirmed'],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->first_login = false;
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Password changed successfully');
    }
}
