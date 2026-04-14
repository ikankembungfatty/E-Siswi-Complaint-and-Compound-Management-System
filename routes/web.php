<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\CompoundController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;

use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// 2FA Challenge Routes (no auth required, guarded by session)
Route::get('/two-factor-challenge', [TwoFactorController::class, 'showChallenge'])
    ->name('two-factor.challenge');
Route::post('/two-factor-challenge', [TwoFactorController::class, 'verify'])
    ->name('two-factor.verify');

// Dashboard - Role-based
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 2FA Setup Routes
    Route::get('/profile/two-factor', [TwoFactorController::class, 'showSetup'])->name('two-factor.setup');
    Route::post('/profile/two-factor/enable', [TwoFactorController::class, 'enable'])->name('two-factor.enable');
    Route::post('/profile/two-factor/disable', [TwoFactorController::class, 'disable'])->name('two-factor.disable');

    // Session Management Routes
    Route::delete('/profile/sessions', [SessionController::class, 'destroy'])->name('sessions.destroy');
    Route::delete('/profile/sessions/{sessionId}', [SessionController::class, 'destroySingle'])->name('sessions.destroy-single');

    Route::resource('complaints', ComplaintController::class);
    Route::get('/complaints/{complaint}/pdf', [ComplaintController::class, 'downloadPdf'])->name('complaints.pdf');

    // Compounds Routes
    Route::resource('compounds', CompoundController::class);

    // Payments Routes
    Route::resource('payments', PaymentController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('/payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/students', [UserManagementController::class, 'students'])->name('students');
        Route::get('/wardens', [UserManagementController::class, 'wardens'])->name('wardens');
        Route::get('/users/create', [UserManagementController::class, 'create'])->name('users.create');
        Route::post('/users', [UserManagementController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserManagementController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserManagementController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');
    });


});

require __DIR__ . '/auth.php';
