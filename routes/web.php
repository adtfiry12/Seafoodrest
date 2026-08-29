<?php

// use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Pengguna & No Login
Route::get('/', function () {
    return view('welcome');
});

// Guest (no login)
// Route::middleware('guest')->group(function () {
//     Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
//     Route::post('/login', [AuthController::class, 'login']);

//     Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
//     Route::post('/register', [AuthController::class, 'register']);
// });

// Auth
Route::middleware('auth')->group(function () {

    // Logout
    // Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Karyawan
    Route::middleware('role:karyawan')->prefix('karyawan')->group(function () {
        Route::get('/dashboard', function () {
            return 'Halaman Dashboard Karyawan';
        });
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return 'Halaman Dashboard Admin';
        });
    });

});
