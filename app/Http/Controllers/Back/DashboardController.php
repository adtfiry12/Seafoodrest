<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\DetailTransaksi;
use App\Models\Menu;
use App\Models\Pengguna;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function sidebarCounts(): JsonResponse
    {
        return response()->json([
            'pending_acc' => Transaksi::where('status_pesanan', 'Menunggu ACC')->count(),
            'pending_dapur' => Transaksi::where('status_pesanan', 'Dimasak')->count(),
            'pending_delivery' => Transaksi::whereIn('status_pesanan', ['Siap Diantar', 'Diantar'])->count(),
        ]);
    }

    public function indexadmin(Request $request)
    {
        Transaksi::batalkanSemuaQrisKedaluwarsa();

        $totalPendapatan = Transaksi::where('status_pembayaran', 'Lunas')->sum('total_bayar');
        $pesananBaru = Transaksi::where('status_pesanan', 'Menunggu ACC')->count();
        $totalMenu = Menu::count();
        $totalPelanggan = Pengguna::where('role', 'pelanggan')->count();

        $transaksiTerbaru = Transaksi::with(['pelanggan', 'karyawan'])
            ->latest()
            ->take(5)
            ->get();

        $topMenus = DetailTransaksi::select('menu_id', DB::raw('SUM(qty) as total_terjual'))
            ->whereHas('transaksi', function ($query) {
                $query->whereNotIn('status_pesanan', ['Dibatalkan', 'Batal']);
            })
            ->with('menu')
            ->groupBy('menu_id')
            ->orderByDesc('total_terjual')
            ->take(5)
            ->get();

        $topCustomers = Transaksi::query()
            ->select('transaksi.pengguna_id', DB::raw('SUM(detail_transaksi.qty) as total_dibeli'))
            ->join('detail_transaksi', 'detail_transaksi.transaksi_id', '=', 'transaksi.id')
            ->whereNotNull('transaksi.pengguna_id')
            ->whereNotIn('transaksi.status_pesanan', ['Dibatalkan', 'Batal'])
            ->with('pelanggan')
            ->groupBy('transaksi.pengguna_id')
            ->orderByDesc('total_dibeli')
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
            'transaksiTerbaru', 'topMenus', 'topCustomers', 'chartDates', 'chartTotals'
        ));
    }

    public function indexkaryawan()
    {
        Transaksi::batalkanSemuaQrisKedaluwarsa();

        // Hitung semua metrik operasional realtime
        $pesananBaru = Transaksi::where('status_pesanan', 'Menunggu ACC')->count();
        $pesananDapur = Transaksi::where('status_pesanan', 'Dimasak')->count();
        $pesananDelivery = Transaksi::whereIn('status_pesanan', ['Siap Diantar', 'Diantar'])->count();
        $pesananSelesaiHariIni = Transaksi::where('status_pesanan', 'Selesai')->whereDate('updated_at', Carbon::today())->count();

        // Pesanan aktif yang sedang diproses
        $pesananAktif = Transaksi::with(['pelanggan', 'detail.menu'])
            ->whereIn('status_pesanan', ['Menunggu ACC', 'Dimasak', 'Siap Diantar', 'Diantar'])
            ->latest()
            ->take(6)
            ->get();

        // Cek menu yang stoknya sisa 5 atau kurang (diurutkan dari yang paling kritis)
        $stokMenipis = Menu::where('stok', '<=', 5)->count();
        $menuMenipis = Menu::with('kategori')->where('stok', '<=', 5)->orderBy('stok', 'asc')->take(5)->get();

        return view('back.karyawan.dashboard', compact(
            'pesananBaru', 'pesananDapur', 'pesananDelivery', 'pesananSelesaiHariIni', 'pesananAktif', 'stokMenipis', 'menuMenipis'
        ));
    }
}
