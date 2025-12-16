<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Models\Branch;



use App\Http\Controllers\QueueHistoryController;




Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

Route::middleware('auth')->group(function () {
    Route::get('/onboarding', [OnboardingController::class, 'create'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');
});

Route::middleware(['auth', 'ensure.onboarding'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');




    // nanti branches, queues, dll masuk sini
});

Route::middleware(['auth'])->group(function () {
    Route::resource('branches', BranchController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::get('/queue-history', [QueueHistoryController::class, 'index'])
        ->name('queue.history');
});

Route::middleware(['auth', 'ensure.onboarding'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

