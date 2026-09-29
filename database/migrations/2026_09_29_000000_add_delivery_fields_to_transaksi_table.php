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
        Schema::table('transaksi', function (Blueprint $table) {
            $table->foreignId('kurir_id')->nullable()->after('karyawan_id')->constrained('pengguna')->nullOnDelete();
            $table->string('patokan_alamat')->nullable()->after('alamat');
            $table->timestamp('waktu_berangkat')->nullable()->after('status_pesanan');
            $table->timestamp('waktu_sampai')->nullable()->after('waktu_berangkat');
            $table->string('diterima_oleh', 100)->nullable()->after('waktu_sampai');
            $table->string('bukti_pengantaran')->nullable()->after('diterima_oleh');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['kurir_id']);
            $table->dropColumn([
                'kurir_id',
                'patokan_alamat',
                'waktu_berangkat',
                'waktu_sampai',
                'diterima_oleh',
                'bukti_pengantaran',
            ]);
        });
    }
};
