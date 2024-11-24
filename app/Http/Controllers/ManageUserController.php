<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\NewUserCredentialsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ManageUserController extends Controller
{
    public function manageUsers() {
        $user = User::all();

        return Inertia::render('ManageUsers', [
            'user' => $user
        ]);
    }

    public function saveEditedUser(Request $request, $id) {
        sleep(1);
        $this->validateSaveEditedUser($request);

        $user = User::findOrFail($id);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role')
        ]);

        return redirect()->route('manage_users')->with('success', 'User updated successfully!');
    }

    public function deleteUser($id) {
        sleep(1);
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }

    public function addNewUser(Request $request) {
        sleep(1);
        $fields = $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['max:255'],  
            'role' => ['required'],
        ]);

        $plainPassword = Str::random(10);

        $fields['password'] = Hash::make($plainPassword);

        $user = User::create($fields);

        $emailData = [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $plainPassword,
        ];

        $adminEmail = Auth::user()->email;

        Mail::to($user->email)->send(new NewUserCredentialsMail($emailData, $adminEmail));

        return redirect()->route('manage_users')->with('success', 'User added successfully');
    }

    private function validateSaveEditedUser(Request $request) {
        $request->validate([
            'name' => ['required', 'max:255'],
            'email' => ['required', 'max:255', 'email'],
            'role' => ['max:255'],
        ]);
    }
}
