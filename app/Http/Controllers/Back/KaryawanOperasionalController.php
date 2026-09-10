<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KaryawanOperasionalController extends Controller
{
    public function pesananBaru(Request $request)
    {
        $query = Transaksi::with('pelanggan', 'detail.menu')
            ->whereIn('status_pesanan', ['Menunggu ACC']);

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_transaksi', 'like', '%'.$request->search.'%');
        }

        $transaksi = $query->latest()->paginate(6)->withQueryString();

        return view('back.karyawan.pesanan_baru', compact('transaksi'));
    }

    public function dapur(Request $request)
    {
        $query = Transaksi::with('pelanggan', 'detail.menu')
            ->where('status_pesanan', 'Dimasak');

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_transaksi', 'like', '%'.$request->search.'%');
        }

        $transaksi = $query->oldest()->paginate(6)->withQueryString();

        return view('back.karyawan.dapur', compact('transaksi'));
    }

    public function delivery(Request $request)
    {
        $query = Transaksi::with('pelanggan', 'detail.menu')
            ->where('status_pesanan', 'Diantar');

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_transaksi', 'like', '%'.$request->search.'%');
        }

        $transaksi = $query->latest()->paginate(6)->withQueryString();

        return view('back.karyawan.delivery', compact('transaksi'));
    }

    public function updateStatus(Request $request, $kode_transaksi)
    {
        $validated = $request->validate([
            'status_pesanan' => ['required', 'in:Dimasak,Diantar,Selesai,Dibatalkan'],
            'status_pembayaran' => ['nullable', 'in:Belum Lunas,Menunggu Konfirmasi,Lunas,Batal'],
        ]);

        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();
        $currentStatus = $transaksi->status_pesanan;
        $nextStatus = $validated['status_pesanan'];
        $allowedNextStatuses = match ($currentStatus) {
            'Menunggu ACC' => ['Dimasak', 'Dibatalkan'],
            'Dimasak' => $transaksi->tipe_pesanan === 'Delivery' ? ['Diantar'] : ['Selesai'],
            'Diantar' => ['Selesai'],
            default => [],
        };

        abort_unless(in_array($nextStatus, $allowedNextStatuses, true), 422, 'Perubahan status pesanan tidak diizinkan.');

        $nextPaymentStatus = $validated['status_pembayaran'] ?? $transaksi->status_pembayaran;

        if ($nextStatus === 'Dimasak' && $transaksi->metode_pembayaran === 'QRIS') {
            abort_unless($transaksi->status_pembayaran === 'Menunggu Konfirmasi' && $nextPaymentStatus === 'Lunas', 422, 'Bukti pembayaran QRIS harus dikonfirmasi terlebih dahulu.');
        }

        if ($nextStatus === 'Selesai' && $transaksi->metode_pembayaran === 'COD') {
            abort_unless($nextPaymentStatus === 'Lunas', 422, 'Pembayaran harus lunas sebelum pesanan selesai.');
        }

        $transaksi->update([
            'status_pesanan' => $nextStatus,
            'status_pembayaran' => $nextStatus === 'Dibatalkan' ? 'Batal' : $nextPaymentStatus,
            'karyawan_id' => Auth::id(),
        ]);

        return back()->with('success', 'Status pesanan '.$kode_transaksi.' berhasil diperbarui!');
    }

    public function stokIndex(Request $request)
    {
        $query = Menu::with('kategori');

        if ($request->has('search') && $request->search != '') {
            $query->where('nama_menu', 'like', '%'.$request->search.'%');
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
            'stok' => 'required|integer|min:0',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'stok' => $request->stok,
        ]);

        return back()->with('success', 'Stok '.$menu->nama_menu.' berhasil diperbarui jadi '.$request->stok);
    }
}
