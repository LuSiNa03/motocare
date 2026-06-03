<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::middleware(['auth', 'verified'])->group(function () {
    // Booking Routes
    Route::get('/booking', [\App\Http\Controllers\Customer\BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [\App\Http\Controllers\Customer\BookingController::class, 'store'])->name('booking.store');

    // Invoice Routes
    Route::get('/invoice/{id}', [\App\Http\Controllers\Customer\InvoiceController::class, 'show'])->name('invoice.show');
    Route::post('/invoice/{id}/pay', [\App\Http\Controllers\Customer\InvoiceController::class, 'pay'])->name('invoice.pay');
    Route::post('/invoice/{id}/review', [\App\Http\Controllers\Customer\InvoiceController::class, 'submitReview'])->name('invoice.review');

    // Reward Routes
    Route::get('/rewards', [\App\Http\Controllers\Customer\RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/redeem', [\App\Http\Controllers\Customer\RewardController::class, 'redeem'])->name('rewards.redeem');
});

require __DIR__.'/auth.php';
