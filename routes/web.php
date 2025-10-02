<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AuthController;

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
Route::get('/', [AuthController::class, 'showRegistration'])->name('register');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
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
});
