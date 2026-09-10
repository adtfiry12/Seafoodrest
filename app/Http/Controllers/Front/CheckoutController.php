<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('front.checkout.index');
    }

    public function proses(Request $request)
    {
        $rules = [
            'tipe_pesanan' => ['required', 'in:Makan di Tempat,Delivery'],
            'metode_pembayaran' => ['required', 'in:Kasir,QRIS,COD'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_id' => ['required', 'integer', 'distinct', 'exists:menu,id'],
            'items.*.qty' => ['required', 'integer', 'min:1', 'max:100'],
            'no_hp' => ['required', 'string', 'max:20'],
            'alamat' => ['required_if:tipe_pesanan,Delivery', 'nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];

        if (! Auth::check()) {
            $rules['nama'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        if ($request->input('tipe_pesanan') === 'Delivery' && $request->input('metode_pembayaran') === 'COD' && ! Auth::check()) {
            abort(403, 'Login diperlukan untuk pembayaran COD.');
        }

        $enumTipePesanan = $request->input('tipe_pesanan') === 'Delivery' ? 'Delivery' : 'Dine-In';
        $enumMetodePembayaran = $request->input('metode_pembayaran') === 'Kasir' ? 'Tunai' : $request->input('metode_pembayaran');

        $infoPelanggan = '';
        if (! Auth::check()) {
            $infoPelanggan = "[GUEST] Nama: {$validated['nama']} | HP: {$validated['no_hp']}\n";
        }

        $infoDineIn = '';
        if ($request->input('tipe_pesanan') === 'Makan di Tempat') {
            if ($request->input('opsi_dinein') === 'sekarang') {
                $infoDineIn = '[LOKASI] Nomor Meja: '.($request->input('nomor_meja') ?: 'Belum diisi')."\n";
            } else {
                $infoDineIn = '[RESERVASI] Jam: '.$request->input('jam_kedatangan').' | '.$request->input('jumlah_orang')." Orang\n";
            }
        }

        $catatanAsli = $request->filled('catatan') ? "Catatan Dapur: {$validated['catatan']}" : '';
        $finalCatatan = $infoPelanggan.$infoDineIn.$catatanAsli;
        $kodeTransaksi = 'TRX-'.date('Ymd').'-'.strtoupper(Str::random(5));

        $transaksi = DB::transaction(function () use ($validated, $enumTipePesanan, $enumMetodePembayaran, $kodeTransaksi, $finalCatatan) {
            $menuIds = collect($validated['items'])->pluck('menu_id')->sort()->values();
            $menus = Menu::whereIn('id', $menuIds)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $totalBayar = 0;
            $details = [];

            foreach ($validated['items'] as $item) {
                $menu = $menus->get($item['menu_id']);
                abort_unless($menu && $menu->stok >= $item['qty'], 422, 'Stok menu tidak mencukupi.');

                $subtotal = $menu->harga * $item['qty'];
                $totalBayar += $subtotal;
                $details[] = [$menu->id, $item['qty'], $menu->harga, $subtotal];
                $menu->decrement('stok', $item['qty']);
            }

            if ($enumTipePesanan === 'Delivery') {
                $totalBayar += 15000;
            }

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'pengguna_id' => Auth::id(),
                'karyawan_id' => null,
                'tipe_pesanan' => $enumTipePesanan,
                'alamat' => $enumTipePesanan === 'Delivery' ? $validated['alamat'] : null,
                'metode_pembayaran' => $enumMetodePembayaran,
                'status_pembayaran' => 'Belum Lunas',
                'status_pesanan' => 'Menunggu ACC',
                'total_bayar' => $totalBayar,
                'catatan' => trim($finalCatatan),
            ]);

            foreach ($details as [$menuId, $qty, $harga, $subtotal]) {
                $transaksi->detail()->create([
                    'menu_id' => $menuId,
                    'qty' => $qty,
                    'harga_satuan' => $harga,
                    'subtotal' => $subtotal,
                ]);
            }

            return $transaksi;
        });

        if ($enumMetodePembayaran == 'QRIS') {
            return redirect()->to(URL::temporarySignedRoute('checkout.qris', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]));
        }

        return redirect()->to(URL::temporarySignedRoute('checkout.sukses', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]));
    }

    public function qris($kode_transaksi)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();

        return view('front.checkout.qris', compact('transaksi'));
    }

    public function sukses($kode_transaksi)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();

        return view('front.checkout.success', compact('transaksi'));
    }

    public function pesanan()
    {
        $transaksi = Transaksi::where('pengguna_id', Auth::id())->latest()->paginate(2);

        return view('front.pesanan', compact('transaksi'));
    }

    public function struk($kode_transaksi)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();

        return view('front.struk', compact('transaksi'));
    }

    public function uploadBukti(Request $request, $kode_transaksi)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();

        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_bayar', 'local');

            $transaksi->update([
                'bukti_pembayaran' => $path,
                'status_pembayaran' => 'Menunggu Konfirmasi',
            ]);
        }

        return redirect()->to(URL::temporarySignedRoute('checkout.sukses', now()->addHours(2), ['kode_transaksi' => $transaksi->kode_transaksi]));
    }
}
