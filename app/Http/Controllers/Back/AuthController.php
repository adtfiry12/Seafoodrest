<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Pengguna;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'exists:pengguna,email'],
            'password' => ['required', 'string'],
        ], [
            'email.exists' => 'Email belum terdaftar.',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Email atau kata sandi salah.'],
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->getRedirectRouteAfterAuth());
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:pengguna,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'tanggal_lahir' => ['nullable', 'date'],
            'alamat' => ['nullable', 'string'],
            'foto' => ['nullable', 'string'],
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'pelanggan';

        $user = Pengguna::create($validated);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended($this->getRedirectRouteAfterAuth());
    }

    protected function getRedirectRouteAfterAuth(): string
    {
        return match (Auth::user()->role ?? 'pelanggan') {
            'admin' => route('dashboard.admin'),
            'karyawan' => route('dashboard.karyawan'),
            default => route('home'),
        };
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
