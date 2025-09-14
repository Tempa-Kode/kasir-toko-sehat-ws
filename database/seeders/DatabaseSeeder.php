<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SatuanSeeder::class);

        // Admin
        User::create([
            'nama' => 'admin',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
            'hak_akses' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Pemilik
        User::create([
            'nama' => 'pemilik',
            'username' => 'pemilik',
            'password' => bcrypt('pemilik123'),
            'hak_akses' => 'pemilik',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Kasir
        User::create([
            'nama' => 'kasir',
            'username' => 'kasir',
            'password' => bcrypt('kasir123'),
            'hak_akses' => 'kasir',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
