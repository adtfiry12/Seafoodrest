<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
        'snap_token',
        'payment_type',
        'payment_reference',
        'refund_status',
        'refund_reference',
        'status_pembayaran',
        'dibayar_pada',
        'refunded_at',
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
            'refunded_at' => 'datetime',
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

            return $menit.' menit';
        }

        if ($this->waktu_berangkat && $this->status_pesanan === 'Diantar') {
            $menit = max(1, (int) $this->waktu_berangkat->diffInMinutes(now()));

            return $menit.' menit di perjalanan';
        }

        return null;
    }

    public function getEstimasiSampaiAttribute(): ?string
    {
        if ($this->waktu_sampai) {
            return $this->waktu_sampai->format('H:i').' WIB';
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

    public function isQrisExpired(): bool
    {
        if ($this->metode_pembayaran !== 'QRIS') {
            return false;
        }

        if ($this->status_pembayaran !== 'Belum Lunas') {
            return false;
        }

        if (in_array($this->status_pesanan, ['Dibatalkan', 'Batal', 'Selesai'], true)) {
            return false;
        }

        return $this->created_at ? $this->created_at->addHour()->isPast() : false;
    }

    public function getQrisBatasWaktuAttribute(): ?Carbon
    {
        if ($this->metode_pembayaran !== 'QRIS' || ! $this->created_at) {
            return null;
        }

        return $this->created_at->copy()->addHour();
    }

    public function getSisaDetikQrisAttribute(): int
    {
        if (! $this->qris_batas_waktu) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($this->qris_batas_waktu, false));
    }

    public function batalkanJikaKedaluwarsa(): bool
    {
        if ($this->isQrisExpired()) {
            DB::transaction(function () {
                $this->update([
                    'status_pesanan' => 'Dibatalkan',
                    'status_pembayaran' => 'Batal',
                ]);
                $this->restoreStockIfNeeded();
            });
            $this->refresh();

            return true;
        }

        return false;
    }

    public static function batalkanSemuaQrisKedaluwarsa(): int
    {
        $expiredTransactions = static::where('metode_pembayaran', 'QRIS')
            ->where('status_pembayaran', 'Belum Lunas')
            ->whereNotIn('status_pesanan', ['Dibatalkan', 'Batal', 'Selesai'])
            ->where('created_at', '<=', now()->subHour())
            ->get();

        $count = 0;
        foreach ($expiredTransactions as $trx) {
            DB::transaction(function () use ($trx) {
                $trx->update([
                    'status_pesanan' => 'Dibatalkan',
                    'status_pembayaran' => 'Batal',
                ]);
                $trx->restoreStockIfNeeded();
            });
            $count++;
        }

        return $count;
    }

    public function getPaymentChannelLabelAttribute(): string
    {
        if (! $this->payment_type) {
            return $this->metode_pembayaran === 'QRIS' ? 'QRIS' : ($this->metode_pembayaran ?? '-');
        }

        return match (strtolower($this->payment_type)) {
            'qris' => 'QRIS',
            'dana' => 'DANA',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'ovo' => 'OVO',
            'linkaja' => 'LinkAja',
            'bca_va' => 'BCA Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'echannel', 'mandiri_va' => 'Mandiri Virtual Account',
            'permata_va' => 'Permata Virtual Account',
            'bsi_va' => 'BSI Virtual Account',
            'cimb_va' => 'CIMB Virtual Account',
            'bank_transfer' => 'Transfer Bank (VA)',
            'cstore' => 'Gerai Retail',
            'alfamart' => 'Alfamart',
            'indomaret' => 'Indomaret',
            'credit_card' => 'Kartu Kredit / Debit',
            default => strtoupper(str_replace('_', ' ', $this->payment_type)),
        };
    }
}
