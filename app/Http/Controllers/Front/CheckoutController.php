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
use Illuminate\Validation\ValidationException;

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
            'opsi_dinein' => ['required_if:tipe_pesanan,Makan di Tempat', 'in:sekarang,reservasi'],
            'nomor_meja' => ['nullable', 'string', 'max:50'],
            'jam_kedatangan' => ['nullable', 'date_format:H:i'],
            'jumlah_orang' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];

        if (! Auth::check()) {
            $rules['nama'] = ['required', 'string', 'max:255'];
        }

        $validated = $request->validate($rules);

        if ($validated['tipe_pesanan'] === 'Delivery' && $validated['metode_pembayaran'] === 'Kasir') {
            throw ValidationException::withMessages(['metode_pembayaran' => 'Pesanan delivery tidak dapat dibayar di kasir.']);
        }

        if ($validated['tipe_pesanan'] === 'Makan di Tempat' && $validated['metode_pembayaran'] === 'COD') {
            throw ValidationException::withMessages(['metode_pembayaran' => 'COD hanya tersedia untuk pesanan delivery.']);
        }

        if ($validated['tipe_pesanan'] === 'Delivery' && $validated['metode_pembayaran'] === 'COD' && ! Auth::check()) {
            abort(403, 'Login diperlukan untuk pembayaran COD.');
        }

        $enumTipePesanan = $validated['tipe_pesanan'] === 'Delivery' ? 'Delivery' : 'Dine-In';
        $enumMetodePembayaran = $validated['metode_pembayaran'] === 'Kasir' ? 'Tunai' : $validated['metode_pembayaran'];

        $infoDineIn = '';
        if ($validated['tipe_pesanan'] === 'Makan di Tempat') {
            if ($request->input('opsi_dinein') === 'sekarang') {
                $infoDineIn = '[LOKASI] Nomor Meja: '.($request->input('nomor_meja') ?: 'Belum diisi')."\n";
            } else {
                $infoDineIn = '[RESERVASI] Jam: '.$request->input('jam_kedatangan').' | '.$request->input('jumlah_orang')." Orang\n";
            }
        }

        $catatanAsli = $request->filled('catatan') ? "Catatan Dapur: {$validated['catatan']}" : '';
        $finalCatatan = $infoDineIn.$catatanAsli;
        $kodeTransaksi = 'TRX-'.date('Ymd').'-'.strtoupper(Str::random(5));
        $namaPelanggan = Auth::user()?->nama ?? ($validated['nama'] ?? null);

        $transaksi = DB::transaction(function () use ($validated, $enumTipePesanan, $enumMetodePembayaran, $kodeTransaksi, $finalCatatan, $namaPelanggan) {
            $menuIds = collect($validated['items'])->pluck('menu_id')->sort()->values();
            $menus = Menu::whereIn('id', $menuIds)
                ->where('is_active', true)
                ->lockForUpdate()
                ->get()
                ->keyBy('id');
            $totalBayar = 0;
            $ongkosKirim = $enumTipePesanan === 'Delivery' ? 15000 : 0;
            $details = [];

            foreach ($validated['items'] as $item) {
                $menu = $menus->get($item['menu_id']);
                abort_unless($menu && $menu->stok >= $item['qty'], 422, 'Stok menu tidak mencukupi.');

                $subtotal = $menu->harga * $item['qty'];
                $totalBayar += $subtotal;
                $details[] = [$menu->id, $menu->nama_menu, $item['qty'], $menu->harga, $subtotal];
                $menu->decrement('stok', $item['qty']);
            }

            $totalBayar += $ongkosKirim;

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kodeTransaksi,
                'pengguna_id' => Auth::id(),
                'karyawan_id' => null,
                'nama_pelanggan' => $namaPelanggan,
                'no_hp_pelanggan' => $validated['no_hp'],
                'tipe_pesanan' => $enumTipePesanan,
                'alamat' => $enumTipePesanan === 'Delivery' ? $validated['alamat'] : null,
                'metode_pembayaran' => $enumMetodePembayaran,
                'status_pembayaran' => 'Belum Lunas',
                'status_pesanan' => 'Menunggu ACC',
                'subtotal_bayar' => $totalBayar - $ongkosKirim,
                'ongkos_kirim' => $ongkosKirim,
                'total_bayar' => $totalBayar,
                'catatan' => trim($finalCatatan),
            ]);

            foreach ($details as [$menuId, $namaMenu, $qty, $harga, $subtotal]) {
                $transaksi->detail()->create([
                    'menu_id' => $menuId,
                    'nama_menu' => $namaMenu,
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
        $transaksi = Transaksi::with('detail.menu')->where('kode_transaksi', $kode_transaksi)->firstOrFail();

        return view('front.checkout.qris', compact('transaksi'));
    }

    public function sukses($kode_transaksi)
    {
        $transaksi = Transaksi::with('detail.menu')->where('kode_transaksi', $kode_transaksi)->firstOrFail();

        return view('front.checkout.success', compact('transaksi'));
    }

    public function pesanan()
    {
        $transaksi = Transaksi::with('detail')
            ->where('pengguna_id', Auth::id())
            ->latest()
            ->paginate(6);

        return view('front.pesanan', compact('transaksi'));
    }

    public function detailPesanan(string $kode_transaksi)
    {
        $transaksi = Transaksi::with(['pelanggan', 'detail.menu'])
            ->where('pengguna_id', Auth::id())
            ->where('kode_transaksi', $kode_transaksi)
            ->firstOrFail();

        return view('front.pesanan-detail', compact('transaksi'));
    }

    public function struk($kode_transaksi)
    {
        $transaksi = Transaksi::with(['pelanggan', 'karyawan', 'detail.menu'])
            ->where('kode_transaksi', $kode_transaksi)
            ->firstOrFail();

        return view('front.struk', compact('transaksi'));
    }

    public function uploadBukti(Request $request, $kode_transaksi)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();

        abort_if($transaksi->metode_pembayaran !== 'QRIS' || in_array($transaksi->status_pembayaran, ['Lunas', 'Batal'], true), 422, 'Bukti pembayaran tidak dapat diunggah untuk transaksi ini.');

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
