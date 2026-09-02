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
use App\Http\Controllers\Front\CheckoutController;
use App\Http\Controllers\Front\HomeController;
use Illuminate\Support\Facades\Route;

// Pengguna & No Login
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [HomeController::class, 'tentang'])->name('tentang');
Route::get('/menu', [HomeController::class, 'menu'])->name('menu');
Route::get('/menu/{slug}', [HomeController::class, 'detailMenu'])->name('menu.detail');
Route::get('/kontak', [HomeController::class, 'kontak'])->name('kontak');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout/proses', [CheckoutController::class, 'proses'])->name('checkout.proses');
Route::get('/checkout/qris/{kode_transaksi}', [CheckoutController::class, 'qris'])->name('checkout.qris');
Route::post('/checkout/qris/{kode_transaksi}/upload', [CheckoutController::class, 'uploadBukti'])->name('checkout.qris.upload');
Route::get('/checkout/sukses/{kode_transaksi}', [CheckoutController::class, 'sukses'])->name('checkout.sukses');
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

    // Pesanan
    Route::get('/pesanan', [CheckoutController::class, 'pesanan'])->name('front.pesanan');
    Route::get('/pesanan/struk/{kode_transaksi}', [CheckoutController::class, 'struk'])->name('front.struk');

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
