<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

    if ($user->role === 'pelanggan') {
        return view('front.profile', compact('user'));
    }

    return view('back.profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama'          => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:pengguna,email,' . $user->id,
            'no_hp'         => 'nullable|string|max:20',
            'jenis_kelamin' => 'nullable|in:L,P',
            'tanggal_lahir' => 'nullable|date|before:today',
            'alamat'        => 'nullable|string|max:1000',
            'foto'          => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'nama.required'        => 'Nama lengkap wajib diisi.',
            'nama.max'             => 'Nama lengkap maksimal 255 karakter.',
            'email.required'       => 'Alamat email wajib diisi.',
            'email.email'          => 'Format email tidak valid. Gunakan format contoh@domain.com.',
            'email.unique'         => 'Email ini sudah digunakan oleh akun lain.',
            'no_hp.max'            => 'Nomor HP tidak boleh melebihi 20 karakter.',
            'jenis_kelamin.in'     => 'Pilihan jenis kelamin tidak valid.',
            'tanggal_lahir.date'   => 'Format tanggal lahir tidak valid.',
            'tanggal_lahir.before' => 'Tanggal lahir tidak boleh di masa depan.',
            'alamat.max'           => 'Alamat maksimal 1000 karakter.',
            'foto.image'           => 'File yang diunggah harus berupa gambar.',
            'foto.mimes'           => 'Format foto harus berupa JPEG, PNG, JPG, atau WebP.',
            'foto.max'             => 'Ukuran foto maksimal 2 MB.',
        ]);

        if ($request->hasFile('foto')) {
            if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                Storage::disk('public')->delete($user->foto);
            }
            $validated['foto'] = $request->file('foto')->store('pengguna_foto', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.index')->with('success_profile', 'Informasi profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required'         => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.',
            'password.required'                 => 'Kata sandi baru wajib diisi.',
            'password.min'                      => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed'                => 'Konfirmasi kata sandi baru tidak sesuai.',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('profile.index')->with('success_password', 'Kata sandi berhasil diubah.');
    }
}
