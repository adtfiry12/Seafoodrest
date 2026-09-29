<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Pengguna;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaksi::with(['pelanggan', 'karyawan'])->latest();

        if ($request->has('status') && $request->status !== 'Semua') {
            $query->where('status_pesanan', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('kode_transaksi', 'like', '%'.$request->search.'%');
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
        $validated = $request->validate([
            'pengguna_id' => ['nullable', 'exists:pengguna,id'],
            'nama_pelanggan' => ['nullable', 'string', 'max:255'],
            'no_hp_pelanggan' => ['nullable', 'string', 'max:20'],
            'tipe_pesanan' => 'required|in:Dine-In,Takeaway,Delivery',
            'metode_pembayaran' => 'required|in:Tunai,QRIS,COD',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|distinct|exists:menu,id',
            'items.*.qty' => 'required|integer|min:1',
            'alamat' => ['nullable', 'string', 'max:1000'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ]);

        $transaksi = DB::transaction(function () use ($validated) {
            $kode = 'INV-'.date('Ymd').'-'.strtoupper(Str::random(5));
            $subtotalBayar = 0;
            $detailData = [];
            $menuIds = collect($validated['items'])->pluck('menu_id');
            $menus = Menu::whereIn('id', $menuIds)->where('is_active', true)->lockForUpdate()->get()->keyBy('id');

            foreach ($validated['items'] as $item) {
                $menu = $menus->get($item['menu_id']);
                if (! $menu || $menu->stok < $item['qty']) {
                    throw ValidationException::withMessages(['items' => 'Stok menu tidak mencukupi atau menu tidak aktif.']);
                }

                $subtotal = $menu->harga * $item['qty'];
                $subtotalBayar += $subtotal;

                $detailData[] = [
                    'menu_id' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'qty' => $item['qty'],
                    'harga_satuan' => $menu->harga,
                    'subtotal' => $subtotal,
                ];

                $menu->decrement('stok', $item['qty']);
            }

            $namaPelanggan = $validated['nama_pelanggan'] ?? null;
            $pelanggan = ! empty($validated['pengguna_id']) ? Pengguna::find($validated['pengguna_id']) : null;
            $namaPelanggan ??= $pelanggan?->nama;
            $statusPembayaran = $validated['metode_pembayaran'] === 'Tunai' ? 'Lunas' : 'Belum Lunas';

            $transaksi = Transaksi::create([
                'kode_transaksi' => $kode,
                'pengguna_id' => $validated['pengguna_id'] ?? null,
                'karyawan_id' => Auth::id(),
                'nama_pelanggan' => $namaPelanggan,
                'no_hp_pelanggan' => $validated['no_hp_pelanggan'] ?? $pelanggan?->no_hp,
                'tipe_pesanan' => $validated['tipe_pesanan'],
                'alamat' => $validated['alamat'] ?? null,
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'status_pembayaran' => $statusPembayaran,
                'dibayar_pada' => $statusPembayaran === 'Lunas' ? now() : null,
                'status_pesanan' => 'Menunggu ACC',
                'subtotal_bayar' => $subtotalBayar,
                'ongkos_kirim' => 0,
                'total_bayar' => $subtotalBayar,
                'catatan' => $validated['catatan'] ?? null,
            ]);

            foreach ($detailData as $detail) {
                $transaksi->detail()->create($detail);
            }

            return $transaksi;
        });

        return redirect()->route('admin.transaksi.show', $transaksi->kode_transaksi)->with('success', 'Transaksi berhasil dibuat!');
    }

    public function show(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'karyawan', 'detail.menu', 'kurir']);

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
            'status_pesanan' => 'required|in:Menunggu ACC,Dimasak,Siap Diantar,Diantar,Selesai,Dibatalkan',
        ]);

        if ($validated['status_pesanan'] === 'Dimasak' && $transaksi->metode_pembayaran === 'QRIS' && $validated['status_pembayaran'] !== 'Lunas') {
            throw ValidationException::withMessages(['status_pembayaran' => 'Pembayaran QRIS harus lunas sebelum pesanan dimasak.']);
        }

        if ($validated['status_pesanan'] === 'Selesai' && $transaksi->metode_pembayaran === 'COD' && $validated['status_pembayaran'] !== 'Lunas') {
            throw ValidationException::withMessages(['status_pembayaran' => 'Pembayaran COD harus lunas sebelum pesanan selesai.']);
        }

        $statusPembayaran = $validated['status_pesanan'] === 'Dibatalkan'
            ? 'Batal'
            : $validated['status_pembayaran'];

        DB::transaction(function () use ($validated, $statusPembayaran, $transaksi) {
            $updateData = [
                ...$validated,
                'status_pembayaran' => $statusPembayaran,
                'karyawan_id' => Auth::id(),
                'dibayar_pada' => $statusPembayaran === 'Lunas' ? ($transaksi->dibayar_pada ?? now()) : null,
            ];

            if ($transaksi->tipe_pesanan === 'Delivery') {
                if ($validated['status_pesanan'] === 'Diantar') {
                    $updateData['waktu_berangkat'] = $transaksi->waktu_berangkat ?? now();
                    $updateData['kurir_id'] = $transaksi->kurir_id ?? Auth::id();
                }

                if ($validated['status_pesanan'] === 'Selesai') {
                    $updateData['waktu_berangkat'] = $transaksi->waktu_berangkat ?? ($transaksi->created_at ?? now());
                    $updateData['waktu_sampai'] = $transaksi->waktu_sampai ?? now();
                    $updateData['kurir_id'] = $transaksi->kurir_id ?? Auth::id();
                    $updateData['diterima_oleh'] = $transaksi->diterima_oleh ?? ($transaksi->pelanggan->nama ?? $transaksi->nama_pelanggan ?? 'Pelanggan');
                }
            }

            $transaksi->update($updateData);

            if ($validated['status_pesanan'] === 'Dibatalkan') {
                $transaksi->restoreStockIfNeeded();
            }
        });

        return redirect()->route('admin.transaksi.show', $transaksi->kode_transaksi)->with('success', 'Status transaksi berhasil diperbarui!');
    }

    public function destroy(Transaksi $transaksi)
    {
        DB::transaction(function () use ($transaksi) {
            $transaksi->restoreStockIfNeeded();
            $transaksi->delete();
        });

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi dibatalkan dan dihapus.');
    }

    public function cetak(Transaksi $transaksi)
    {
        $transaksi->load(['pelanggan', 'karyawan', 'detail.menu']);

        return view('back.admin.transaksi.cetak', compact('transaksi'));
    }

    public function buktiPembayaran(Transaksi $transaksi): BinaryFileResponse
    {
        abort_unless($transaksi->bukti_pembayaran && Storage::disk('local')->exists($transaksi->bukti_pembayaran), 404);

        $path = Storage::disk('local')->path($transaksi->bukti_pembayaran);

        return response()->file($path, [
            'Content-Type' => mime_content_type($path) ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="bukti-pembayaran-'.$transaksi->kode_transaksi.'"',
        ]);
    }
}
