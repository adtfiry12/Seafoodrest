<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        $query = Transaksi::where('status_pesanan', 'Selesai')
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        $total_pendapatan = $query->sum('total_bayar');
        $total_pesanan = $query->count();

        $laporan = $query->with('detail')->latest()->paginate(10)->withQueryString();

        return view('back.admin.laporan.index', compact('laporan', 'total_pendapatan', 'total_pesanan', 'bulan', 'tahun'));
    }
}
