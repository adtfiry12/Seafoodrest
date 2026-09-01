<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Back\AuthController;
use App\Http\Controllers\Back\DashboardController;

// Pengguna & No Login
Route::get('/', [HomeController::class, 'index'])->name('home');

// Guest (no login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Auth
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Karyawan
    Route::middleware('role:karyawan')->prefix('karyawan')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexkaryawan'])->name('dashboard.karyawan');
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexadmin'])->name('dashboard.admin');
    });

});
