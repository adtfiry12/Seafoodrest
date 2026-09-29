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
        'kurir_id',
        'nama_pelanggan',
        'no_hp_pelanggan',
        'tipe_pesanan',
        'alamat',
        'patokan_alamat',
        'metode_pembayaran',
        'bukti_pembayaran',
        'status_pembayaran',
        'dibayar_pada',
        'status_pesanan',
        'waktu_berangkat',
        'waktu_sampai',
        'diterima_oleh',
        'bukti_pengantaran',
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
            'waktu_berangkat' => 'datetime',
            'waktu_sampai' => 'datetime',
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

    public function kurir(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class, 'kurir_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }

    public function getRouteKeyName()
    {
        return 'kode_transaksi';
    }

    public function getDurasiPengantaranAttribute(): ?string
    {
        if ($this->waktu_berangkat && $this->waktu_sampai) {
            $menit = max(1, (int) $this->waktu_berangkat->diffInMinutes($this->waktu_sampai));
            return $menit . ' menit';
        }

        if ($this->waktu_berangkat && $this->status_pesanan === 'Diantar') {
            $menit = max(1, (int) $this->waktu_berangkat->diffInMinutes(now()));
            return $menit . ' menit di perjalanan';
        }

        return null;
    }

    public function getEstimasiSampaiAttribute(): ?string
    {
        if ($this->waktu_sampai) {
            return $this->waktu_sampai->format('H:i') . ' WIB';
        }

        if ($this->waktu_berangkat) {
            $estMulai = $this->waktu_berangkat->copy()->addMinutes(20)->format('H:i');
            $estAkhir = $this->waktu_berangkat->copy()->addMinutes(35)->format('H:i');
            return "{$estMulai} - {$estAkhir} WIB (±25 mnt)";
        }

        return '25 - 35 menit setelah berangkat';
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
