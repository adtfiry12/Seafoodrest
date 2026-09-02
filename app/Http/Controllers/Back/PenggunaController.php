<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = Pengguna::latest()->paginate(5);
        return view('back.admin.pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        return view('back.admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:pengguna,email',
            'password'      => 'required|string|min:8',
            'role'          => 'required|in:admin,karyawan,pelanggan',
            'no_hp'         => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('pengguna_foto', 'public');
        }

        Pengguna::create($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function edit(Pengguna $pengguna)
    {
        return view('back.admin.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, Pengguna $pengguna)
    {
        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:pengguna,email,' . $pengguna->id,
            'password'      => 'nullable|string|min:8',
            'role'          => 'required|in:admin,karyawan,pelanggan',
            'no_hp'         => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat'        => 'nullable|string',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        if ($request->hasFile('foto')) {
            if ($pengguna->foto && Storage::disk('public')->exists($pengguna->foto)) {
                Storage::disk('public')->delete($pengguna->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pengguna_foto', 'public');
        }

        $pengguna->update($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui!');
    }

    public function destroy(Pengguna $pengguna)
    {
        if ($pengguna->foto && Storage::disk('public')->exists($pengguna->foto)) {
            Storage::disk('public')->delete($pengguna->foto);
        }

        $pengguna->delete();
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus!');
    }
}
