<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VerificationController;

// Database test route
Route::get('/db', function () {
    try {
        DB::connection()->getPdo();
        return " Database connected successfully.";
    } catch (\Exception $e) {
        return " Could not connect to the database. Error: " . $e->getMessage();
    }
});

// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', function () {
    return redirect()->route('login');
});
Route::get('/register', [AuthController::class, 'showRegistration'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.admindashboard');
    })->name('dashboard');
});

// Citizen Routes
Route::middleware(['auth', 'citizen'])->prefix('citizen')->name('citizen.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.normalcitizenn');
    })->name('dashboard');
    
    // Additional citizen routes
    Route::get('/profile', function () {
        return view('dashboard.citizenprofile');
    })->name('profile');

    Route::get('/verification/certificate', [VerificationController::class, 'downloadCertificate'])
        ->name('verification.certificate');
});

// Verification Routes
Route::middleware(['auth'])->group(function () {
    // Citizen verification routes
    Route::get('/verification/apply', [VerificationController::class, 'create'])->name('verification.create')->middleware('citizen');
    Route::post('/verification/prefill', [VerificationController::class, 'prefill'])->name('verification.prefill')->middleware('citizen');
    Route::post('/verification/apply', [VerificationController::class, 'store'])->name('verification.store')->middleware('citizen');
    
    // Admin verification routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/verification', [VerificationController::class, 'adminIndex'])->name('verification.index');
        Route::get('/verification/{user}', [VerificationController::class, 'show'])->name('verification.show');
        Route::post('/verification/{user}/approve', [VerificationController::class, 'approve'])->name('verification.approve');
        Route::post('/verification/{user}/reject', [VerificationController::class, 'reject'])->name('verification.reject');
    });
});

// Public verification status endpoint for QR scans
Route::get('/verification/status/{user}', [VerificationController::class, 'publicStatus'])
    ->name('verification.status')
    ->middleware('signed');
