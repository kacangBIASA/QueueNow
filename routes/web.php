<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\OnboardingController;

use App\Http\Controllers\PublicQueueController;
use App\Http\Controllers\OwnerQueueController;

use App\Http\Controllers\PricingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Models\Branch;
use App\Http\Controllers\QueueHistoryController;

Route::get('/', fn () => view('landing'))->name('home');

/**
 * PUBLIC (tanpa auth)
 */
Route::get('/p/{public_code}', [PublicQueueController::class, 'show'])->name('public.queue.show');
Route::post('/p/{public_code}/take', [PublicQueueController::class, 'take'])->name('public.queue.take');

Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

require __DIR__ . '/auth.php';

/**
 * AUTH (login saja)
 */
Route::middleware('auth')->group(function () {
    // onboarding
    Route::get('/onboarding', [OnboardingController::class, 'create'])->name('onboarding');
    Route::post('/onboarding', [OnboardingController::class, 'store'])->name('onboarding.store');

    // profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * PROTECTED (wajib onboarding)
 */
Route::middleware(['auth', 'ensure.onboarding'])->group(function () {
    // dashboard (hanya 1x, jangan duplikat)
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // branches (kalau tetap mau di sini)
    Route::resource('branches', BranchController::class);

    // owner queues
    Route::get('/owner/queues', [OwnerQueueController::class, 'index'])->name('owner.queues.index');
    Route::post('/owner/queues/{branch}/call-next', [OwnerQueueController::class, 'callNext'])->name('owner.queues.callNext');
    Route::post('/owner/queues/{branch}/{queue}/skip', [OwnerQueueController::class, 'skip'])->name('owner.queues.skip');
    Route::post('/owner/queues/{branch}/{queue}/finish', [OwnerQueueController::class, 'finish'])->name('owner.queues.finish');
    Route::post('/owner/queues/{branch}/reset', [OwnerQueueController::class, 'resetDaily'])->name('owner.queues.resetDaily');

    // midtrans upgrade pro
    Route::post('/pricing/upgrade', [PaymentController::class, 'createProPayment'])->name('pricing.upgrade');
    Route::get('/payment/pro', [PaymentController::class, 'showProPayment'])->name('payment.pro.show');
});

/**
 * MIDTRANS WEBHOOK (tanpa auth)
 */
Route::post('/midtrans/callback', [PaymentController::class, 'midtransCallback'])->name('midtrans.callback');
Route::middleware(['auth'])->group(function () {
    Route::get('/queue-history', [QueueHistoryController::class, 'index'])
        ->name('queue.history');
});

Route::middleware(['auth', 'ensure.onboarding'])
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

