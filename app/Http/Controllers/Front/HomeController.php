<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Slide;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $slides = Slide::with('menu.kategori')
            ->where('is_active', 1)
            ->latest()
            ->take(5)
            ->get();
        $menus = Menu::with('kategori')
            ->where('is_active', 1)
            ->withSum(['detailTransaksi as total_terjual' => function ($query) {
                $query->whereHas('transaksi', function ($transactionQuery) {
                    $transactionQuery->whereNotIn('status_pesanan', ['Dibatalkan', 'Batal']);
                });
            }], 'qty')
            ->orderByDesc('total_terjual')
            ->latest()
            ->take(4)
            ->get();

        return view('front.home', compact('slides', 'menus'));
    }

    public function tentang()
    {
        return view('front.tentang');
    }

    public function menu(Request $request)
    {
        $kategori = Kategori::all();
        $query = Menu::where('is_active', 1);

        if ($request->has('kategori') && $request->kategori != '') {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('search')) {
            $search = mb_substr(trim((string) $request->search), 0, 100);
            $query->where('nama_menu', 'like', '%'.$search.'%');
        }

        $menus = $query->paginate(12)->withQueryString();

        return view('front.menu.index', compact('menus', 'kategori'));
    }

    public function detailMenu($slug)
    {
        $menu = Menu::with('kategori')->where('slug', $slug)->firstOrFail();

        $relatedMenus = Menu::where('kategori_id', $menu->kategori_id)
            ->where('id', '!=', $menu->id)
            ->where('is_active', 1)
            ->take(4)
            ->get();

        return view('front.menu.detail', compact('menu', 'relatedMenus'));
    }

    public function kontak()
    {
        return view('front.kontak');
    }
}
