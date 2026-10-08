<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /** Akun demo (password semuanya: "password") untuk menguji 3 role. */
    public function run(): void
    {
        User::factory()->admin()->create(['name' => 'Admin Lapak', 'email' => 'admin@example.com']);
        User::factory()->editor()->create(['name' => 'Editor Lapak', 'email' => 'editor@example.com']);
        User::factory()->create(['name' => 'Budi Santoso', 'email' => 'user@example.com']);

        // 12 pengguna acak (role: user) sebagai penjual & pembeli tambahan
        User::factory()->count(12)->create();
    }
}
