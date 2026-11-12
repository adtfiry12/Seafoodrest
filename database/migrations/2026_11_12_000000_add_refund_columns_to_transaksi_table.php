<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (! Schema::hasColumn('transaksi', 'refund_status')) {
                $table->string('refund_status', 30)->nullable()->index()->after('payment_reference');
            }
            if (! Schema::hasColumn('transaksi', 'refund_reference')) {
                $table->string('refund_reference', 150)->nullable()->after('refund_status');
            }
            if (! Schema::hasColumn('transaksi', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable()->after('dibayar_pada');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $columns = array_filter(
                ['refund_status', 'refund_reference', 'refunded_at'],
                fn (string $column): bool => Schema::hasColumn('transaksi', $column),
            );

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
