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
            if (! Schema::hasColumn('transaksi', 'snap_token')) {
                $table->string('snap_token', 255)->nullable()->after('bukti_pembayaran');
            }
            if (! Schema::hasColumn('transaksi', 'payment_type')) {
                $table->string('payment_type', 100)->nullable()->after('snap_token');
            }
            if (! Schema::hasColumn('transaksi', 'payment_reference')) {
                $table->string('payment_reference', 150)->nullable()->after('payment_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $columns = array_filter(
                ['snap_token', 'payment_type', 'payment_reference'],
                fn ($col) => Schema::hasColumn('transaksi', $col)
            );
            if (! empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};

