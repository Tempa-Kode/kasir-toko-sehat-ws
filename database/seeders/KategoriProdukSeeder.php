<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class KategoriProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_kategori_produk')->insert([
            ['nama_kategori' => 'Sembako', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Makanan Ringan & Snack', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Rokok & Produk Tembakau', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Produk Kebersihan & Rumah Tangga', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Kebutuhan Dapur & Masak', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Perawatan Diri & Kosmetik', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Bahan Segar', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Alat Tulis Kantor (ATK)', 'created_at' => now(), 'updated_at' => now()],
            ['nama_kategori' => 'Lain-lain', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
