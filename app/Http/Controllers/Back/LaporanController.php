<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'dari' => ['nullable', 'date_format:Y-m-d'],
            'sampai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:dari'],
        ]);

        [$dari, $sampai] = $this->resolvePeriod($validated['dari'] ?? null, $validated['sampai'] ?? null);

        $query = Transaksi::where('status_pesanan', 'Selesai')
            ->whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()]);

        $total_pendapatan = $query->sum('total_bayar');
        $total_pesanan = $query->count();

        $laporan = $query->with('detail')->latest()->paginate(10)->withQueryString();

        return view('back.admin.laporan.index', compact('laporan', 'total_pendapatan', 'total_pesanan', 'dari', 'sampai'));
    }

    public function cetak(Request $request): View
    {
        $validated = $request->validate([
            'dari' => ['nullable', 'date_format:Y-m-d'],
            'sampai' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:dari'],
        ]);

        [$dari, $sampai] = $this->resolvePeriod($validated['dari'] ?? null, $validated['sampai'] ?? null);
        $query = Transaksi::where('status_pesanan', 'Selesai')
            ->whereBetween('created_at', [$dari->copy()->startOfDay(), $sampai->copy()->endOfDay()]);

        $transaksi = $query->with(['pelanggan', 'detail.menu'])->latest()->get();
        $total_pendapatan = $transaksi->sum('total_bayar');
        $total_pesanan = $transaksi->count();
        $periode = $dari->isSameDay($sampai)
            ? $dari->translatedFormat('d F Y')
            : $dari->translatedFormat('d F Y').' - '.$sampai->translatedFormat('d F Y');

        return view('back.admin.laporan.cetak', compact('transaksi', 'total_pendapatan', 'total_pesanan', 'dari', 'sampai', 'periode'));
    }

    private function resolvePeriod(?string $dari, ?string $sampai): array
    {
        if ($dari || $sampai) {
            $start = Carbon::parse($dari ?: $sampai);
            $end = Carbon::parse($sampai ?: $dari);

            return [$start, $end];
        }

        return [now()->startOfMonth(), now()->endOfMonth()];
    }
}
