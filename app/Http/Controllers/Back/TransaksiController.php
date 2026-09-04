<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'karyawan'])->latest();

        if ($request->has('status') && $request->status !== 'Semua') {
            $query->where('status_pesanan', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_transaksi', 'like', '%' . $request->search . '%');
        }

        $transaksi = $query->paginate(5)->withQueryString();

        return view('back.admin.transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $menu = Menu::where('is_active', 1)->where('stok', '>', 0)->get();
        $pelanggan = Pengguna::where('role', 'pelanggan')->get();

        return view('back.admin.transaksi.create', compact('menu', 'pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipe_pesanan'      => 'required|in:Dine-In,Takeaway,Delivery',
            'metode_pembayaran' => 'required|in:Tunai,QRIS,COD',
            'items'             => 'required|array|min:1',
            'items.*.menu_id'   => 'required|exists:menu,id',
            'items.*.qty'       => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $kode = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5));

            $total_bayar = 0;
            $detail_data = [];

            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                $subtotal = $menu->harga * $item['qty'];
                $total_bayar += $subtotal;

                $detail_data[] = [
                    'menu_id'      => $menu->id,
                    'qty'          => $item['qty'],
                    'harga_satuan' => $menu->harga,
                    'subtotal'     => $subtotal,
                ];

                $menu->decrement('stok', $item['qty']);
            }

            $transaksi = Transaksi::create([
                'kode_transaksi'    => $kode,
                'pengguna_id'       => $request->pengguna_id,
                'karyawan_id'       => Auth::id(),
                'tipe_pesanan'      => $request->tipe_pesanan,
                'alamat'            => $request->alamat,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => $request->metode_pembayaran == 'Tunai' ? 'Lunas' : 'Belum Lunas',
                'status_pesanan'    => 'Menunggu ACC',
                'total_bayar'       => $total_bayar,
                'catatan'           => $request->catatan,
            ]);

            foreach ($detail_data as $detail) {
                $detail['transaksi_id'] = $transaksi->id;
                DetailTransaksi::create($detail);
            }

            DB::commit();
            return redirect()->route('admin.transaksi.show', $transaksi->kode_transaksi)->with('success', 'Transaksi berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'karyawan', 'detail.menu']);
        return view('back.admin.transaksi.show', compact('transaksi'));
    }

    public function edit(Transaksi $transaksi)
    {
        return view('back.admin.transaksi.edit', compact('transaksi'));
    }

    public function update(Request $request, Transaksi $transaksi)
    {
        $validated = $request->validate([
            'status_pembayaran' => 'required|in:Belum Lunas,Menunggu Konfirmasi,Lunas,Batal',
            'status_pesanan'    => 'required|in:Menunggu ACC,Dimasak,Diantar,Selesai,Dibatalkan',
        ]);

        $validated['karyawan_id'] = Auth::id();

        $transaksi->update($validated);

        return redirect()->route('admin.transaksi.show', $transaksi->kode_transaksi)->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function destroy(Transaksi $transaksi)
    {
        foreach ($transaksi->detail as $item) {
            $menu = Menu::find($item->menu_id);
            if($menu) {
                $menu->increment('stok', $item->qty);
            }
        }

        $transaksi->delete();
        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi dibatalkan dan dihapus.');
    }

    public function cetak(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'karyawan', 'detail.menu']);
        return view('back.admin.transaksi.cetak', compact('transaksi'));
    }
}
