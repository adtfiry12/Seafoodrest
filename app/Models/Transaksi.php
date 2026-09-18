<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';

    protected $fillable = [
        'kode_transaksi',
        'pengguna_id',
        'karyawan_id',
        'nama_pelanggan',
        'no_hp_pelanggan',
        'tipe_pesanan',
        'alamat',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'dibayar_pada',
        'status_pesanan',
        'subtotal_bayar',
        'ongkos_kirim',
        'total_bayar',
        'stok_dikembalikan',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'dibayar_pada' => 'datetime',
            'stok_dikembalikan' => 'boolean',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'pengguna_id');
    }

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'karyawan_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function getRouteKeyName()
    {
        return 'kode_transaksi';
    }

    public function restoreStockIfNeeded(): void
    {
        if ($this->stok_dikembalikan) {
            return;
        }

        $this->loadMissing('detail');

        foreach ($this->detail as $detail) {
            if ($detail->menu_id) {
                Menu::whereKey($detail->menu_id)->lockForUpdate()->increment('stok', $detail->qty);
            }
        }

        $this->update(['stok_dikembalikan' => true]);
    }
}
