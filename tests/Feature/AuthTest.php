<?php

use App\Models\Pengguna;

it('can register a pengguna with nullable fields and login', function () {
    $response = $this->from('/register')->post('/register', [
        'nama' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'password' => 'Password123',
        'password_confirmation' => 'Password123',
        'no_hp' => null,
        'jenis_kelamin' => null,
        'tanggal_lahir' => null,
        'alamat' => null,
        'foto' => null,
    ]);

    $response->assertRedirect('/karyawan/dashboard');
    $this->assertDatabaseHas('pengguna', [
        'email' => 'budi@example.com',
        'role' => 'pelanggan',
    ]);

    $user = Pengguna::where('email', 'budi@example.com')->first();
    expect($user)->not->toBeNull();
    expect(Auth::check())->toBeTrue();

    Auth::logout();

    $loginResponse = $this->from('/login')->post('/login', [
        'email' => 'budi@example.com',
        'password' => 'Password123',
    ]);

    $loginResponse->assertRedirect('/karyawan/dashboard');
    expect(Auth::check())->toBeTrue();
});
