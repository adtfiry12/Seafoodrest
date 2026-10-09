<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Slide;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SlideController extends Controller
{
    public function index()
    {
        $slide = Slide::with('menu')->latest()->paginate(5);

        return view('back.admin.slide.index', compact('slide'));
    }

    public function show(Slide $slide): View
    {
        $slide->load('menu');

        return view('back.admin.slide.show', compact('slide'));
    }

    public function create()
    {
        $menu = Menu::where('is_active', 1)->get();

        return view('back.admin.slide.create', compact('menu'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'sub_judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'menu_id' => 'nullable|exists:menu,id',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
        ], [
            'judul.required' => 'Judul utama banner wajib diisi.',
            'judul.string' => 'Judul utama banner harus berupa teks.',
            'judul.max' => 'Judul utama banner maksimal 255 karakter.',
            'sub_judul.string' => 'Sub judul banner harus berupa teks.',
            'sub_judul.max' => 'Sub judul banner maksimal 255 karakter.',
            'menu_id.exists' => 'Menu yang ditautkan tidak ditemukan di dalam sistem.',
            'gambar.required' => 'Gambar banner promosi wajib diunggah.',
            'gambar.image' => 'Berkas banner harus berupa file gambar.',
            'gambar.mimes' => 'Format gambar banner harus berupa JPEG, PNG, JPG, atau WebP.',
            'gambar.max' => 'Ukuran gambar banner maksimal 2 MB.',
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
            'judul' => 'required|string|max:255',
            'sub_judul' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'menu_id' => 'nullable|exists:menu,id',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
        ], [
            'judul.required' => 'Judul utama banner wajib diisi.',
            'judul.string' => 'Judul utama banner harus berupa teks.',
            'judul.max' => 'Judul utama banner maksimal 255 karakter.',
            'sub_judul.string' => 'Sub judul banner harus berupa teks.',
            'sub_judul.max' => 'Sub judul banner maksimal 255 karakter.',
            'menu_id.exists' => 'Menu yang ditautkan tidak ditemukan di dalam sistem.',
            'gambar.image' => 'Berkas banner harus berupa file gambar.',
            'gambar.mimes' => 'Format gambar banner harus berupa JPEG, PNG, JPG, atau WebP.',
            'gambar.max' => 'Ukuran gambar banner maksimal 2 MB.',
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
