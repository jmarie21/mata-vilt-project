<?php

use App\Http\Controllers\ClickupTaskController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\SignupController;
use App\Http\Controllers\TaskFetchConfigController;
use App\Http\Controllers\TaskSchedulerController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AdminCheck;
use App\Http\Middleware\FirstLoginCheck;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', AdminCheck::class])->group(function() {
    // Admin Exclusive Routes
    // Route::get('/reports', function() {
    //     return inertia('Reports');  
    // })->name('reports');
    Route::get('/invoice', function() {
        return inertia('Invoice');
    })->name('invoice');
    Route::get('/manage_users', function() {
        return inertia('ManageUsers');
    })->name('manage_users');
    Route::get('/time_sheets', function() {
        return inertia('TimeSheets');
    })->name('time_sheets');
    // Route::get('/task', function() {
    //     return inertia('Tasks');
    // })->name('task');


    // Managing users routes
    Route::get('/manage_users', [ManageUserController::class, 'manageUsers'])->name('manage_users');
    // Route::get('/manage_users/{id}', [ManageUserController::class, 'showUserInfo'])->name('users.show');
    Route::put('/manage_users/{id}', [ManageUserController::class, 'saveEditedUser'])->name('users.update');
    Route::delete('/manage_users/{id}', [ManageUserController::class, 'deleteUser'])->name('users.delete');
    Route::post('/manage_users', [ManageUserController::class, 'addNewUser'])->name('users.add');

    //Invoice routes
    Route::get('/invoice', [InvoiceController::class, 'showInvoice'])->name('invoice');
    Route::post('/invoice', [InvoiceController::class, 'createInvoice'])->name('invoice.create');
    Route::get('/invoice/download/${id}', [InvoiceController::class, 'downloadPDF'])->name('invoice.download');
    Route::post('/invoice/send/${id}', [InvoiceController::class, 'sendInvoice'])->name('invoice.send');
    Route::put('/invoice/{id}', [InvoiceController::class, 'editInvoice'])->name('invoice.edit');
    Route::delete('/invoice/{id}', [InvoiceController::class, 'deleteInvoice'])->name('invoice.delete');

    //Timesheets routes
    Route::get('/timesheets', [TimeSheetController::class, 'index'])->name('timesheets.index');
    Route::post('/timesheets/upload', [TimeSheetController::class, 'uploadCsv'])->name('timesheets.upload');

    // Clickup tasks routes
    Route::get('/tasks', [ClickupTaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/fetch-tasks', [ClickupTaskController::class, 'fetchTasksDirectly'])->name('tasks.fetch');

    Route::get('/config', [TaskSchedulerController::class, 'show'])->name('config.show');
    Route::post('/config', [TaskSchedulerController::class, 'update'])->name('config.update');


    Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    

});

Route::middleware(['auth'])->group(function() {
    Route::middleware([])->group(function() {
        Route::get('/change-password', [PasswordController::class, 'showChangePasswordForm'])->name('password.change');
        Route::put('/change-password', [PasswordController::class, 'changePassword'])->name('changePassword.update');
    });

    // Admin and Users routes
    Route::get('/dashboard', function() {
        return inertia('Dashboard');
    })->name('dashboard');
    Route::get('/projects', function() {
        return inertia('Projects');
    })->name('projects');

    // Profile routes
    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile');
    Route::put('/profile/update', [UserController::class, 'updatePersonalInformation'])->name('personalInfo.update');
    Route::put('/profile/update_password', [UserController::class, 'updatePassword'])->name('update.password');

    //Logout route
    Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

});



Route::middleware('guest')->group(function() {
    // Home Route
    Route::inertia('/', 'Home')->name('home');

    // Forgot Password routes
    Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Signup routes
    Route::inertia('/signup', 'Auth/Signup')->name('signup');
    Route::post('/signup', [SignupController::class, 'signup']);

    // Login routes
    Route::inertia('/login', 'Auth/Login')->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});