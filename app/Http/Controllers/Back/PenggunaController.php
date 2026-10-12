<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    public function index(Request $request)
    {
        $query = Pengguna::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $pengguna = $query->latest()->paginate(10)->withQueryString();

        return view('back.admin.pengguna.index', compact('pengguna'));
    }

    public function show(Pengguna $pengguna): View
    {
        return view('back.admin.pengguna.show', compact('pengguna'));
    }

    public function create()
    {
        return view('back.admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,karyawan,kurir,pelanggan',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.string' => 'Nama lengkap harus berupa teks.',
            'nama.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid. Gunakan format contoh@domain.com.',
            'email.unique' => 'Alamat email ini sudah terdaftar. Silakan gunakan email lain.',
            'password.required' => 'Kata sandi akun wajib diisi.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'role.required' => 'Silakan pilih hak akses (role) pengguna.',
            'role.in' => 'Pilihan hak akses tidak valid.',
            'no_hp.max' => 'Nomor HP tidak boleh melebihi 20 karakter.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'foto.image' => 'Berkas foto profil harus berupa file gambar.',
            'foto.mimes' => 'Format foto profil harus berupa JPEG, PNG, JPG, atau WebP.',
            'foto.max' => 'Ukuran foto profil maksimal 2 MB.',
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
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna,email,'.$pengguna->id,
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,karyawan,kurir,pelanggan',
            'no_hp' => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'nama.string' => 'Nama lengkap harus berupa teks.',
            'nama.max' => 'Nama lengkap maksimal 255 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid. Gunakan format contoh@domain.com.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal terdiri dari 8 karakter.',
            'role.required' => 'Silakan pilih hak akses (role) pengguna.',
            'role.in' => 'Pilihan hak akses tidak valid.',
            'no_hp.max' => 'Nomor HP tidak boleh melebihi 20 karakter.',
            'jenis_kelamin.in' => 'Pilihan jenis kelamin tidak valid.',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid.',
            'foto.image' => 'Berkas foto profil harus berupa file gambar.',
            'foto.mimes' => 'Format foto profil harus berupa JPEG, PNG, JPG, atau WebP.',
            'foto.max' => 'Ukuran foto profil maksimal 2 MB.',
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
