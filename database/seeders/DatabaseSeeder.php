<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Pengguna::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'nama' => 'admin',
                'password' => bcrypt('admin123'),
                'role' => 'admin',
                'no_hp' => null,
                'jenis_kelamin' => null,
                'tanggal_lahir' => null,
                'alamat' => null,
                'foto' => null,
            ]
        );

        $this->call(MenuDanSlideSeeder::class);
    }
}
