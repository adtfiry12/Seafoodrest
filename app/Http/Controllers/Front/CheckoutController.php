<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('front.checkout.index');
    }

    public function proses(Request $request)
    {
        // 1. Validasi Data Dasar
        $request->validate([
            'tipe_pesanan' => 'required',
            'metode_pembayaran' => 'required',
            'items' => 'required|array',
            'total_bayar' => 'required|numeric'
        ]);

        // 2. Mapping Tipe Pesanan & Metode Pembayaran ke Enum Database
        $enumTipePesanan = 'Dine-In'; // Default
        if ($request->tipe_pesanan == 'Delivery') {
            $enumTipePesanan = 'Delivery';
        }

        // INI TAMBAHANNYA: Ubah "Kasir" jadi "Tunai" agar diterima Database
        $enumMetodePembayaran = $request->metode_pembayaran;
        if ($request->metode_pembayaran == 'Kasir') {
            $enumMetodePembayaran = 'Tunai';
        }

        // 3. TRIK NINJA: Rangkai semua info ke dalam Catatan
        $infoPelanggan = "";
        if (!Auth::check()) {
            $infoPelanggan = "[GUEST] Nama: " . $request->nama . " | HP: " . $request->no_hp . "\n";
        }

        $infoDineIn = "";
        if ($request->tipe_pesanan == 'Makan di Tempat') {
            if ($request->opsi_dinein == 'sekarang') {
                $infoDineIn = "[LOKASI] Nomor Meja: " . ($request->nomor_meja ?? 'Belum diisi') . "\n";
            } else {
                $infoDineIn = "[RESERVASI] Jam: " . $request->jam_kedatangan . " | " . $request->jumlah_orang . " Orang\n";
            }
        }

        $catatanAsli = $request->catatan ? "Catatan Dapur: " . $request->catatan : "";
        $finalCatatan = $infoPelanggan . $infoDineIn . $catatanAsli;

        // 4. Generate Kode Transaksi (Contoh: TRX-20260902-XXXX)
        $tanggal = date('Ymd');
        $urutan = Transaksi::whereDate('created_at', date('Y-m-d'))->count() + 1;
        $kodeTransaksi = 'TRX-' . $tanggal . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

        // 5. Simpan Data ke Tabel Transaksi
        $transaksi = Transaksi::create([
            'kode_transaksi' => $kodeTransaksi,
            'pengguna_id' => Auth::id(), // Akan bernilai null jika guest
            'karyawan_id' => null,
            'tipe_pesanan' => $enumTipePesanan,
            'alamat' => $request->tipe_pesanan == 'Delivery' ? $request->alamat : null,
            'metode_pembayaran' => $enumMetodePembayaran, // Gunakan variabel mapping
            'status_pembayaran' => 'Belum Lunas',
            'status_pesanan' => 'Menunggu ACC',
            'total_bayar' => $request->total_bayar,
            'catatan' => trim($finalCatatan),
        ]);

        // 6. Simpan Item Keranjang ke Detail Transaksi
        foreach ($request->items as $item) {
            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'menu_id' => $item['menu_id'],
                'qty' => $item['qty'],
                'harga_satuan' => $item['harga'],
                'subtotal' => $item['qty'] * $item['harga'],
            ]);
        }

        // 7. Percabangan Arah Halaman (Routing)
        if ($enumMetodePembayaran == 'QRIS') {
            return redirect()->route('checkout.qris', $transaksi->kode_transaksi);
        }

        return redirect()->route('checkout.sukses', $transaksi->kode_transaksi);
    }

    public function qris($kode_transaksi)
    {
        // Cari data transaksi berdasarkan kode
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();
        return view('front.checkout.qris', compact('transaksi'));
    }

    public function sukses($kode_transaksi)
    {
        // Cari data transaksi berdasarkan kode
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();
        return view('front.checkout.success', compact('transaksi'));
    }

    public function pesanan()
    {
        $transaksi = Transaksi::where('pengguna_id', Auth::id())->latest()->get();
        return view('front.pesanan', compact('transaksi'));
    }

    public function struk($kode_transaksi)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();

        // Simpan kodingan struk yang kamu kasih tadi di file: resources/views/front/struk.blade.php
        return view('front.struk', compact('transaksi'));
    }

    public function uploadBukti(Request $request, $kode_transaksi)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();

            $path = $file->storeAs('bukti_bayar', $filename, 'public');

            $transaksi->update([
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'Menunggu Konfirmasi'
            ]);
        }

        return redirect()->route('checkout.sukses', $transaksi->kode_transaksi);
    }
}
