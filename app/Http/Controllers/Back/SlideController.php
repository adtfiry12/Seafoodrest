<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Slide;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function index()
    {
        $slide = Slide::with('menu')->latest()->paginate(5);
        return view('back.admin.slide.index', compact('slide'));
    }

    public function create()
    {
        // Hanya ambil menu yang aktif untuk pilihan link slide
        $menu = Menu::where('is_active', 1)->get();
        return view('back.admin.slide.create', compact('menu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'sub_judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'menu_id'   => 'nullable|exists:menu,id',
            'gambar'    => 'required|image|mimes:jpeg,png,jpg,webp|max:2048', // Wajib saat create
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('slide_images', 'public');
            $validated['gambar'] = $path;
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        Slide::create($validated);

        return redirect()->route('admin.slide.index')->with('success', 'Slide banner berhasil ditambahkan!');
    }

    public function edit(Slide $slide)
    {
        $menu = Menu::where('is_active', 1)->get();
        return view('back.admin.slide.edit', compact('slide', 'menu'));
    }

    public function update(Request $request, Slide $slide)
    {
        $validated = $request->validate([
            'judul'     => 'required|string|max:255',
            'sub_judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'menu_id'   => 'nullable|exists:menu,id',
            'gambar'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('gambar')) {
            if ($slide->gambar && Storage::disk('public')->exists($slide->gambar)) {
                Storage::disk('public')->delete($slide->gambar);
            }
            $path = $request->file('gambar')->store('slide_images', 'public');
            $validated['gambar'] = $path;
        }

        $validated['is_active'] = $request->has('is_active') ? 1 : 0;

        $slide->update($validated);

        return redirect()->route('admin.slide.index')->with('success', 'Slide banner berhasil diperbarui!');
    }

    public function destroy(Slide $slide)
    {
        if ($slide->gambar && Storage::disk('public')->exists($slide->gambar)) {
            Storage::disk('public')->delete($slide->gambar);
        }

        $slide->delete();
        return redirect()->route('admin.slide.index')->with('success', 'Slide banner berhasil dihapus!');
    }
}
