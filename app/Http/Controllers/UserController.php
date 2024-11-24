<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class UserController extends Controller
{

    public function showprofile() {
        sleep(1);
        $user = Auth::user();

        return Inertia::render('Profile', [
            'user' => $user
        ]);
    }

    public function updatePersonalInformation(Request $request) {
        sleep(1);
        $this->validatePersonalInfo($request);

        $user = Auth::user();

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully');
    }

    public function updatePassword(Request $request) {
        $user = Auth::user();
        $this->validatePassword($request);

        if(!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->back()->with('success', 'Password updated successfully');
    }

    private function validatePersonalInfo(Request $request) {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255']
        ]);
    } 

    private function validatePassword(Request $request) {
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required', 'confirmed'],
        ]);
    }
}
