<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $menu = Menu::with('kategori')->latest()->paginate(5);
        return view('back.admin.menu.index', compact('menu'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('back.admin.menu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_menu'   => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|numeric|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active'   => 'boolean'
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('menu_images', 'public');
            $validated['gambar'] = $path;
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        Menu::create($validated);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        $kategori = Kategori::all();
        return view('back.admin.menu.edit', compact('menu', 'kategori'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori,id',
            'nama_menu'   => 'required|string|max:255',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric|min:0',
            'stok'        => 'required|numeric|min:0',
            'gambar'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active'   => 'boolean'
        ]);

        if ($request->hasFile('gambar')) {
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $path = $request->file('gambar')->store('menu_images', 'public');
            $validated['gambar'] = $path;
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $menu->update($validated);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();
        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus!');
    }
}
