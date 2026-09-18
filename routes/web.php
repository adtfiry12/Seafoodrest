<?php

use App\Http\Controllers\Back\AuthController;
use App\Http\Controllers\Back\DashboardController;
use App\Http\Controllers\Back\KaryawanOperasionalController;
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
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/tentang', 'tentang')->name('tentang');
    Route::get('/menu', 'menu')->name('menu');
    Route::get('/menu/{slug}', 'detailMenu')->name('menu.detail');
    Route::get('/kontak', 'kontak')->name('kontak');
});

Route::controller(CheckoutController::class)->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/proses', 'proses')->name('proses');
    Route::get('/qris/{kode_transaksi}', 'qris')->middleware('signed')->name('qris');
    Route::post('/qris/{kode_transaksi}/upload', 'uploadBukti')->middleware('signed')->name('qris.upload');
    Route::get('/sukses/{kode_transaksi}', 'sukses')->middleware('signed')->name('sukses');
});

Route::get('/pesanan/struk/{kode_transaksi}', [CheckoutController::class, 'struk'])
    ->middleware('signed')
    ->name('front.struk');

// Guest (no login)
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->middleware('throttle:login')->name('login.submit');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->name('register.submit');
});

// Auth
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Pesanan
    Route::controller(CheckoutController::class)->prefix('pesanan')->name('front.')->group(function () {
        Route::get('/', 'pesanan')->name('pesanan');
        Route::get('/{kode_transaksi}', 'detailPesanan')->name('pesanan.detail');
    });

    Route::get('/transaksi/{transaksi}/bukti-pembayaran', [TransaksiController::class, 'buktiPembayaran'])
        ->middleware('role:admin,karyawan')
        ->name('transaksi.bukti');

    // profile
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password');
    });

    // Karyawan
    Route::middleware('role:karyawan')->prefix('karyawan')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexkaryawan'])->name('dashboard.karyawan');

        Route::controller(KaryawanOperasionalController::class)->name('karyawan.')->group(function () {
            Route::get('/pesanan-baru', 'pesananBaru')->name('pesanan_baru');
            Route::get('/dapur', 'dapur')->name('dapur');
            Route::get('/delivery', 'delivery')->name('delivery');
            Route::get('/pesanan/{kode_transaksi}', 'detailPesanan')->name('pesanan.detail');
            Route::post('/update-status/{kode_transaksi}', 'updateStatus')->name('update_status');
            Route::get('/stok', 'stokIndex')->name('stok');
            Route::get('/stok/menu/{menu}', 'detailMenu')->name('stok.detail');
            Route::post('/stok/{id}', 'updateStok')->name('stok.update');
        });
    });

    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'indexadmin'])->name('dashboard');

        // All CRUD
        Route::resources([
            'kategori' => KategoriController::class,
            'menu' => MenuController::class,
            'slide' => SlideController::class,
            'pengguna' => PenggunaController::class,
            'transaksi' => TransaksiController::class,
        ]);

        Route::get('transaksi/{transaksi}/cetak', [TransaksiController::class, 'cetak'])->name('transaksi.cetak');
        Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    });

});
