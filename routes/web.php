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
use Illuminate\Http\Request;
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
    Route::post('/proses', 'proses')->middleware('throttle:checkout')->name('proses');
    Route::get('/finish', 'midtransFinish')->name('midtrans.finish');
    Route::get('/qris/{kode_transaksi}', 'qris')->middleware('signed')->name('qris');
    Route::get('/qris/{kode_transaksi}/status', 'checkPaymentStatus')->name('qris.status');
    Route::post('/qris/{kode_transaksi}/upload', 'uploadBukti')->middleware(['signed', 'throttle:uploads'])->name('qris.upload');
    Route::get('/sukses/{kode_transaksi}', 'sukses')->middleware('signed')->name('sukses');
});

Route::post('/payment/midtrans/callback', [CheckoutController::class, 'midtransCallback'])
    ->name('payment.midtrans.callback');

Route::get('/pesanan/struk/{kode_transaksi}', [CheckoutController::class, 'struk'])
    ->middleware('signed')
    ->name('front.struk');

// Guest (no login)
Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->middleware('throttle:login')->name('login.submit');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register')->middleware('throttle:register')->name('register.submit');
});

// Auth
Route::middleware('auth')->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Sidebar badge counts & bukti pembayaran (Admin, Karyawan, Kurir)
    Route::get('/sidebar-counts', [DashboardController::class, 'sidebarCounts'])
        ->middleware('role:admin,karyawan,kurir')
        ->name('sidebar.counts');

    // Pesanan
    Route::controller(CheckoutController::class)->prefix('pesanan')->name('front.')->group(function () {
        Route::get('/', 'pesanan')->name('pesanan');
        Route::get('/{kode_transaksi}', 'detailPesanan')->name('pesanan.detail');
        Route::post('/{kode_transaksi}/batal', 'batalkanPesanan')->name('pesanan.batal');
    });

    Route::get('/transaksi/{transaksi}/bukti-pembayaran', [TransaksiController::class, 'buktiPembayaran'])
        ->middleware('role:admin,karyawan,kurir')
        ->name('transaksi.bukti');

    // Profile
    Route::controller(ProfileController::class)->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->middleware('throttle:password_update')->name('password');
    });

    // Operasional Backoffice Karyawan & Kurir
    Route::prefix('karyawan')->group(function () {
        // Akses Bersama (Karyawan & Kurir & Admin)
        Route::middleware('role:karyawan,kurir')->group(function () {
            Route::get('/dashboard', [DashboardController::class, 'indexkaryawan'])->name('dashboard.karyawan');
            Route::get('/pesanan/{kode_transaksi}', [KaryawanOperasionalController::class, 'detailPesanan'])->name('karyawan.pesanan.detail');
            Route::post('/update-status/{kode_transaksi}', [KaryawanOperasionalController::class, 'updateStatus'])->name('karyawan.update_status');
        });

        // Khusus Kurir (& Admin): Pengantaran Delivery
        Route::middleware('role:kurir')->group(function () {
            Route::get('/delivery', [KaryawanOperasionalController::class, 'delivery'])->name('karyawan.delivery');
        });

        // Khusus Karyawan Resto (& Admin): Kasir POS, Pesanan Masuk, Stasiun Dapur, Stok
        Route::middleware('role:karyawan')->group(function () {
            Route::controller(KaryawanOperasionalController::class)->name('karyawan.')->group(function () {
                Route::get('/keranjang', 'posCreate')->name('keranjang');
                Route::get('/pos', 'posCreate')->name('pos');
                Route::post('/keranjang', 'posStore')->name('keranjang.store');
                Route::post('/pos', 'posStore')->name('pos.store');
                Route::get('/pesanan-baru', 'pesananBaru')->name('pesanan_baru');
                Route::get('/dapur', 'dapur')->name('dapur');
                Route::get('/stok', 'stokIndex')->name('stok');
                Route::get('/stok/menu/{menu}', 'detailMenu')->name('stok.detail');
                Route::post('/stok/{id}', 'updateStok')->name('stok.update');
            });
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
        Route::get('laporan/cetak', [LaporanController::class, 'cetak'])->name('laporan.cetak');
    });

});

Route::fallback(function (Request $request) {
    if ($request->expectsJson()) {
        return response()->json(['message' => 'Halaman tidak ditemukan.'], 404);
    }

    return response()->view('errors.404', [], 404);
});
