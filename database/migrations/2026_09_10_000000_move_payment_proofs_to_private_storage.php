<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('transaksi')) {
            return;
        }

        DB::table('transaksi')
            ->whereNotNull('bukti_pembayaran')
            ->orderBy('id')
            ->each(function (object $transaksi): void {
                $path = $transaksi->bukti_pembayaran;

                if (! Storage::disk('public')->exists($path)) {
                    return;
                }

                Storage::disk('local')->put($path, Storage::disk('public')->get($path));
                DB::table('transaksi')->where('id', $transaksi->id)->update([
                    'bukti_pembayaran' => $path,
                ]);
                Storage::disk('public')->delete($path);
            });
    }

    public function down(): void {}
};
