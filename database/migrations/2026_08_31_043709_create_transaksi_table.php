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
            $table->foreignId('pengguna_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->foreignId('karyawan_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->string('nama_pelanggan')->nullable();
            $table->string('no_hp_pelanggan', 20)->nullable();
            $table->string('tipe_pesanan', 30);
            $table->text('alamat')->nullable();
            $table->string('metode_pembayaran', 30);
            $table->string('bukti_pembayaran')->nullable();
            $table->string('status_pembayaran', 30)->default('Belum Lunas')->index();
            $table->timestamp('dibayar_pada')->nullable();
            $table->string('status_pesanan', 30)->default('Menunggu ACC')->index();
            $table->unsignedInteger('subtotal_bayar')->default(0);
            $table->unsignedInteger('ongkos_kirim')->default(0);
            $table->unsignedInteger('total_bayar')->default(0);
            $table->boolean('stok_dikembalikan')->default(false);
            $table->text('catatan')->nullable();
            $table->timestamps();
            $table->index('created_at');
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
