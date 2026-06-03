<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified', 'customer'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth', 'customer'])
    ->name('profile');

Route::middleware(['auth', 'verified', 'customer'])->group(function () {
    // Vehicle Routes
    Route::post('/vehicle', [\App\Http\Controllers\Customer\VehicleController::class, 'store'])->name('vehicle.store');
    Route::delete('/vehicle/{id}', [\App\Http\Controllers\Customer\VehicleController::class, 'destroy'])->name('vehicle.destroy');

    // Booking Routes
    Route::get('/booking', [\App\Http\Controllers\Customer\BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [\App\Http\Controllers\Customer\BookingController::class, 'store'])->name('booking.store');

    // Invoice Routes
    Route::get('/invoice/{id}', [\App\Http\Controllers\Customer\InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/{id}/pdf', [\App\Http\Controllers\Customer\InvoiceController::class, 'downloadPDF'])->name('invoice.pdf');
    Route::post('/invoice/{id}/pay', [\App\Http\Controllers\Customer\InvoiceController::class, 'pay'])->name('invoice.pay');
    Route::post('/invoice/{id}/review', [\App\Http\Controllers\Customer\InvoiceController::class, 'submitReview'])->name('invoice.review');

    // Reward Routes
    Route::get('/rewards', [\App\Http\Controllers\Customer\RewardController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/redeem', [\App\Http\Controllers\Customer\RewardController::class, 'redeem'])->name('rewards.redeem');

    // Branch locations map Route
    Route::get('/cabang', function () {
        $branches = \App\Models\Branch::all();
        return view('customer.branches', compact('branches'));
    })->name('branches.index');
});

Route::post('/logout', function (\App\Livewire\Actions\Logout $logout) {
    $logout();
    return redirect('/');
})->middleware('auth')->name('logout');

require __DIR__.'/auth.php';
