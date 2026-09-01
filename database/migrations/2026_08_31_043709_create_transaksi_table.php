<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
        $table->string('kode_transaksi')->unique();
        $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->onDelete('set null');
        $table->foreignId('karyawan_id')->nullable()->constrained('pengguna')->onDelete('set null');

        $table->enum('tipe_pesanan', ['Dine-In', 'Takeaway', 'Delivery']);
        $table->text('alamat')->nullable();

        $table->enum('metode_pembayaran', ['Tunai', 'QRIS', 'COD']);
        $table->string('bukti_pembayaran')->nullable();
        $table->enum('status_pembayaran', ['Belum Lunas', 'Menunggu Konfirmasi', 'Lunas', 'Batal'])->default('Belum Lunas');
        $table->enum('status_pesanan', ['Menunggu ACC', 'Dimasak', 'Diantar', 'Selesai', 'Dibatalkan'])->default('Menunggu ACC');

        $table->integer('total_bayar')->default(0);
        $table->text('catatan')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
