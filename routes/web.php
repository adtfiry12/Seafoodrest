<?php
use App\Http\Controllers\Back\AuthController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\KategoriController;
use App\Http\Controllers\Back\LaporanController;
use App\Http\Controllers\Back\MenuController;
use App\Http\Controllers\Back\PenggunaController;
use App\Http\Controllers\Back\ProfileController;
use App\Http\Controllers\Back\SlideController;
use App\Http\Controllers\Back\TransaksiController;
use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

// Pengguna & No Login
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang-kami', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/menu-kami', [HomeController::class, 'menu'])->name('menu');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');

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

    // profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Karyawan
    Route::middleware('role:karyawan')->prefix('karyawan')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexkaryawan'])->name('dashboard.karyawan');
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexadmin'])->name('admin.dashboard');
        Route::resource('kategori', KategoriController::class)->names('admin.kategori');
        Route::resource('menu', MenuController::class)->names('admin.menu');
        Route::resource('slide', SlideController::class)->names('admin.slide');
        Route::resource('pengguna', PenggunaController::class)->names('admin.pengguna');
        Route::resource('transaksi', TransaksiController::class)->names('admin.transaksi');
        Route::get('transaksi/{transaksi}/cetak', [TransaksiController::class, 'cetak'])->name('admin.transaksi.cetak');
        Route::get('laporan', [LaporanController::class, 'index'])->name('admin.laporan.index');
    });

});
