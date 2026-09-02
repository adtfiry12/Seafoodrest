<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Menu;
use App\Models\Pengguna;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function indexadmin(Request $request)
    {
        $totalPendapatan = Transaksi::where('status_pembayaran', 'Lunas')->sum('total_bayar');
        $pesananBaru = Transaksi::where('status_pesanan', 'Menunggu ACC')->count();
        $totalMenu = Menu::count();
        $totalPelanggan = Pengguna::where('role', 'pelanggan')->count();

        $transaksiTerbaru = Transaksi::with(['pelanggan', 'karyawan'])
                                     ->latest()
                                     ->take(5)
                                     ->get();

        $topMenus = DetailTransaksi::select('menu_id', DB::raw('SUM(qty) as total_terjual'))
                                   ->with('menu')
                                   ->groupBy('menu_id')
                                   ->orderByDesc('total_terjual')
                                   ->take(5)
                                   ->get();

        $chartDates = [];
        $chartTotals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartDates[] = $date->translatedFormat('d M');

            $total = Transaksi::where('status_pembayaran', 'Lunas')
                              ->whereDate('created_at', $date->format('Y-m-d'))
                              ->sum('total_bayar');

            $chartTotals[] = $total;
        }

        return view('back.admin.dashboard', compact(
            'totalPendapatan', 'pesananBaru', 'totalMenu', 'totalPelanggan',
            'transaksiTerbaru', 'topMenus', 'chartDates', 'chartTotals'
        ));
    }

    public function indexkaryawan()
    {
        return view('back.karyawan.dashboard');
    }
}
