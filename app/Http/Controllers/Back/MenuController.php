<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with('kategori');

        if ($request->filled('search')) {
            $search = mb_substr(trim((string) $request->search), 0, 100);
            $query->where('nama_menu', 'like', '%' . $search . '%');
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $menu = $query
            ->orderByDesc('is_active')
            ->latest()
            ->paginate(10)
            ->withQueryString();
        $kategoris = Kategori::all();

        return view('back.admin.menu.index', compact('menu', 'kategoris'));
    }

    public function show(Menu $menu): View
    {
        $menu->load('kategori');

        return view('back.admin.menu.show', compact('menu'));
    }

    public function create()
    {
        $kategori = Kategori::all();

        return view('back.admin.menu.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_menu.required' => 'Nama hidangan menu wajib diisi.',
            'nama_menu.string' => 'Nama hidangan menu harus berupa teks.',
            'nama_menu.max' => 'Nama hidangan menu maksimal 255 karakter.',
            'kategori_id.required' => 'Silakan pilih kategori hidangan.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid atau tidak ditemukan.',
            'harga.required' => 'Harga hidangan wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka nominal yang valid.',
            'harga.min' => 'Harga hidangan tidak boleh kurang dari 0.',
            'stok.required' => 'Jumlah stok awal wajib diisi.',
            'stok.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'stok.min' => 'Jumlah stok tidak boleh kurang dari 0.',
            'gambar.image' => 'Berkas foto menu harus berupa file gambar.',
            'gambar.mimes' => 'Format foto menu harus berupa JPEG, PNG, JPG, GIF, atau WebP.',
            'gambar.max' => 'Ukuran foto menu maksimal 2 MB.',
            'deskripsi.string' => 'Deskripsi hidangan harus berupa teks.',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        $data['slug'] = Str::slug($request->nama_menu);

        $originalSlug = $data['slug'];
        $count = 1;
        while (Menu::where('slug', $data['slug'])->exists()) {
            $data['slug'] = $originalSlug.'-'.$count++;
        }

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        Menu::create($data);

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
            'nama_menu' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'harga' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'deskripsi' => 'nullable|string',
        ], [
            'nama_menu.required' => 'Nama hidangan menu wajib diisi.',
            'nama_menu.string' => 'Nama hidangan menu harus berupa teks.',
            'nama_menu.max' => 'Nama hidangan menu maksimal 255 karakter.',
            'kategori_id.required' => 'Silakan pilih kategori hidangan.',
            'kategori_id.exists' => 'Kategori yang dipilih tidak valid atau tidak ditemukan.',
            'harga.required' => 'Harga hidangan wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka nominal yang valid.',
            'harga.min' => 'Harga hidangan tidak boleh kurang dari 0.',
            'stok.required' => 'Jumlah stok awal wajib diisi.',
            'stok.integer' => 'Jumlah stok harus berupa bilangan bulat.',
            'stok.min' => 'Jumlah stok tidak boleh kurang dari 0.',
            'gambar.image' => 'Berkas foto menu harus berupa file gambar.',
            'gambar.mimes' => 'Format foto menu harus berupa JPEG, PNG, JPG, GIF, atau WebP.',
            'gambar.max' => 'Ukuran foto menu maksimal 2 MB.',
            'deskripsi.string' => 'Deskripsi hidangan harus berupa teks.',
        ]);

        $data = $request->all();
        $data['is_active'] = $request->has('is_active') ? 1 : 0;

        if ($request->nama_menu !== $menu->nama_menu) {
            $data['slug'] = Str::slug($request->nama_menu);

            $originalSlug = $data['slug'];
            $count = 1;
            while (Menu::where('slug', $data['slug'])->where('id', '!=', $menu->id)->exists()) {
                $data['slug'] = $originalSlug.'-'.$count++;
            }
        }

        if ($request->hasFile('gambar')) {
            if ($menu->gambar) {
                Storage::disk('public')->delete($menu->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('menu', 'public');
        }

        $menu->update($data);

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->gambar) {
            Storage::disk('public')->delete($menu->gambar);
        }

        // Hapus data dari database
        $menu->delete();

        return redirect()->route('admin.menu.index')->with('success', 'Menu berhasil dihapus!');
    }
}
