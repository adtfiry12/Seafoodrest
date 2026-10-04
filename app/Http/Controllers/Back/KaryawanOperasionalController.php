<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class KaryawanOperasionalController extends Controller
{
    public function pesananBaru(Request $request)
    {
        Transaksi::batalkanSemuaQrisKedaluwarsa();

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
        $query = Transaksi::with(['pelanggan', 'kurir', 'detail.menu'])
            ->whereIn('status_pesanan', ['Siap Diantar', 'Diantar']);

        if ($request->filled('status') && in_array($request->status, ['Siap Diantar', 'Diantar'], true)) {
            $query->where('status_pesanan', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_transaksi', 'like', '%'.$request->search.'%')
                  ->orWhere('nama_pelanggan', 'like', '%'.$request->search.'%')
                  ->orWhere('alamat', 'like', '%'.$request->search.'%');
            });
        }

        $transaksi = $query->orderByRaw("CASE WHEN status_pesanan = 'Siap Diantar' THEN 1 ELSE 2 END")
            ->latest()
            ->paginate(6)
            ->withQueryString();

        $countSiap = Transaksi::where('status_pesanan', 'Siap Diantar')->count();
        $countDiantar = Transaksi::where('status_pesanan', 'Diantar')->count();

        return view('back.karyawan.delivery', compact('transaksi', 'countSiap', 'countDiantar'));
    }

    public function detailPesanan(string $kode_transaksi): View
    {
        $transaksi = Transaksi::with(['pelanggan', 'karyawan', 'kurir', 'detail.menu'])
            ->where('kode_transaksi', $kode_transaksi)
            ->firstOrFail();

        $transaksi->batalkanJikaKedaluwarsa();

        return view('back.karyawan.pesanan-detail', compact('transaksi'));
    }

    public function updateStatus(Request $request, $kode_transaksi)
    {
        $transaksi = Transaksi::where('kode_transaksi', $kode_transaksi)->firstOrFail();
        $currentStatus = $transaksi->status_pesanan;

        $rules = [
            'status_pesanan' => ['required', 'in:Dimasak,Siap Diantar,Diantar,Selesai,Dibatalkan'],
            'status_pembayaran' => ['nullable', 'in:Belum Lunas,Menunggu Konfirmasi,Lunas,Batal'],
            'diterima_oleh' => ['nullable', 'string', 'max:100'],
            'bukti_pengantaran' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:3072'],
        ];

        if ($request->input('status_pesanan') === 'Selesai' && $transaksi->tipe_pesanan === 'Delivery') {
            $rules['diterima_oleh'] = ['required', 'string', 'max:100'];
        }

        $validated = $request->validate($rules);
        $nextStatus = $validated['status_pesanan'];

        $allowedNextStatuses = match ($currentStatus) {
            'Menunggu ACC' => ['Dimasak', 'Dibatalkan'],
            'Dimasak' => $transaksi->tipe_pesanan === 'Delivery' ? ['Siap Diantar'] : ['Selesai'],
            'Siap Diantar' => ['Diantar', 'Dibatalkan'],
            'Diantar' => ['Selesai'],
            default => [],
        };

        $isPaymentSettlement = ($nextStatus === $currentStatus) &&
            $transaksi->status_pembayaran !== 'Lunas' &&
            ($validated['status_pembayaran'] ?? null) === 'Lunas';

        abort_unless($isPaymentSettlement || in_array($nextStatus, $allowedNextStatuses, true), 422, 'Perubahan status pesanan tidak diizinkan.');

        $nextPaymentStatus = $validated['status_pembayaran'] ?? $transaksi->status_pembayaran;

        if ($nextStatus === 'Dimasak' && $transaksi->metode_pembayaran === 'QRIS') {
            abort_unless($transaksi->status_pembayaran === 'Menunggu Konfirmasi' && $nextPaymentStatus === 'Lunas', 422, 'Bukti pembayaran QRIS harus dikonfirmasi terlebih dahulu.');
        }

        if ($nextStatus === 'Selesai' && $transaksi->metode_pembayaran === 'COD') {
            abort_unless($nextPaymentStatus === 'Lunas', 422, 'Pembayaran COD harus lunas saat pesanan selesai diserahkan.');
        }

        DB::transaction(function () use ($transaksi, $nextStatus, $nextPaymentStatus, $request) {
            $statusPembayaran = $nextStatus === 'Dibatalkan' ? 'Batal' : $nextPaymentStatus;

            $updateData = [
                'status_pesanan' => $nextStatus,
                'status_pembayaran' => $statusPembayaran,
                'dibayar_pada' => $statusPembayaran === 'Lunas' ? ($transaksi->dibayar_pada ?? now()) : null,
            ];

            if (! $transaksi->karyawan_id) {
                $updateData['karyawan_id'] = Auth::id();
            }

            if ($nextStatus === 'Diantar') {
                $updateData['kurir_id'] = $transaksi->kurir_id ?? Auth::id();
                $updateData['waktu_berangkat'] = $transaksi->waktu_berangkat ?? now();
            }

            if ($nextStatus === 'Selesai' && $transaksi->tipe_pesanan === 'Delivery') {
                $updateData['kurir_id'] = $transaksi->kurir_id ?? Auth::id();
                $updateData['waktu_berangkat'] = $transaksi->waktu_berangkat ?? ($transaksi->created_at ?? now());
                $updateData['waktu_sampai'] = now();
                $updateData['diterima_oleh'] = $request->input('diterima_oleh') ?: ($transaksi->diterima_oleh ?? ($transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan));

                if ($request->hasFile('bukti_pengantaran')) {
                    $updateData['bukti_pengantaran'] = $request->file('bukti_pengantaran')->store('bukti_pengantaran', 'public');
                }
            }

            $transaksi->update($updateData);

            if ($nextStatus === 'Dibatalkan') {
                $transaksi->restoreStockIfNeeded();
            }
        });

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

    public function detailMenu(Menu $menu): View
    {
        $menu->load('kategori');

        $transaksi = Transaksi::with('pelanggan')
            ->whereHas('detail', fn ($query) => $query->where('menu_id', $menu->id))
            ->latest()
            ->paginate(8);

        $transaksi->getCollection()->load([
            'detail' => fn ($query) => $query->where('menu_id', $menu->id),
        ]);

        return view('back.karyawan.menu-detail', compact('menu', 'transaksi'));
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
