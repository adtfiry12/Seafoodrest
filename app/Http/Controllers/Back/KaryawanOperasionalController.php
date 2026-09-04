<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;
use App\Models\Kategori;

class KaryawanOperasionalController extends Controller
{
    public function pesananBaru(Request $request)
    {
        $query = Transaksi::with('pelanggan', 'detail.menu')
            ->whereIn('status_pesanan', ['Menunggu ACC']);

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_transaksi', 'like', '%' . $request->search . '%');
        }

        $transaksi = $query->latest()->paginate(6)->withQueryString();

        return view('back.karyawan.pesanan_baru', compact('transaksi'));
    }

    public function dapur(Request $request)
    {
        $query = Transaksi::with('pelanggan', 'detail.menu')
            ->where('status_pesanan', 'Dimasak');

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_transaksi', 'like', '%' . $request->search . '%');
        }

        $transaksi = $query->oldest()->paginate(6)->withQueryString();

        return view('back.karyawan.dapur', compact('transaksi'));
    }

    public function delivery(Request $request)
    {
        $query = Transaksi::with('pelanggan', 'detail.menu')
            ->where('status_pesanan', 'Diantar');

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_transaksi', 'like', '%' . $request->search . '%');
        }

        $transaksi = $query->latest()->paginate(6)->withQueryString();

        return view('back.karyawan.delivery', compact('transaksi'));
    }

    public function updateStatus(Request $request, $kode_transaksi)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();

        $transaksi->update([
            'status_pesanan' => $request->status_pesanan,
            'status_pembayaran' => $request->status_pembayaran ?? $transaksi->status_pembayaran,
            'karyawan_id' => Auth::id() // Catat siapa karyawan yang memproses
        ]);

        return back()->with('success', 'Status pesanan ' . $kode_transaksi . ' berhasil diperbarui!');
    }

    public function stokIndex(Request $request)
    {
        $query = Menu::with('kategori');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_menu', 'like', '%' . $request->search . '%');
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        $menus = $query->paginate(12)->withQueryString();
        $kategoris = Kategori::all();

        return view('back.karyawan.stok', compact('menus', 'kategoris'));
    }

    public function updateStok(Request $request, $id)
    {
        $request->validate([
            'stok' => 'required|integer|min:0'
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'stok' => $request->stok
        ]);

        return back()->with('success', 'Stok ' . $menu->nama_menu . ' berhasil diperbarui jadi ' . $request->stok);
    }
}
