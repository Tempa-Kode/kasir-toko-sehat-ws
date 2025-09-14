<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SatuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tb_satuan')->insert([
            // Barang Satuan (Per Item)
            ['kode_satuan' => 'PCS', 'nama_satuan' => 'Pieces', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'UNIT', 'nama_satuan' => 'Unit', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'BTL', 'nama_satuan' => 'Botol', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'KLG', 'nama_satuan' => 'Kaleng', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'SCT', 'nama_satuan' => 'Sachet', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'BKS', 'nama_satuan' => 'Bungkus', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'TAB', 'nama_satuan' => 'Tabung', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'KPS', 'nama_satuan' => 'Kapsul', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'TBL', 'nama_satuan' => 'Tablet/Kapsul', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'ROL', 'nama_satuan' => 'Rol', 'created_at' => now(), 'updated_at' => now()],

            // Barang Paket/Bundling
            ['kode_satuan' => 'PACK', 'nama_satuan' => 'Pack', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'DUS', 'nama_satuan' => 'Karton/Dus', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'BALL', 'nama_satuan' => 'Ball', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'SLOP', 'nama_satuan' => 'Slop', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'RTG', 'nama_satuan' => 'Renteng', 'created_at' => now(), 'updated_at' => now()],

            // Barang Timbangan
            ['kode_satuan' => 'KG', 'nama_satuan' => 'Kilogram', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'GR', 'nama_satuan' => 'Gram', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'LTR', 'nama_satuan' => 'Liter', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'ML', 'nama_satuan' => 'Mililiter', 'created_at' => now(), 'updated_at' => now()],

            // Barang Besar/Curah
            ['kode_satuan' => 'SAK', 'nama_satuan' => 'Sak', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'KRG', 'nama_satuan' => 'Karung', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'GLN', 'nama_satuan' => 'Galon', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'DRM', 'nama_satuan' => 'Drum', 'created_at' => now(), 'updated_at' => now()],

            // Barang Tekstil/Material
            ['kode_satuan' => 'MTR', 'nama_satuan' => 'Meter', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'LMBR', 'nama_satuan' => 'Lembar', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'PKT', 'nama_satuan' => 'Paket', 'created_at' => now(), 'updated_at' => now()],

            // Satuan Lainnya (Spesifik)
            ['kode_satuan' => 'BOX', 'nama_satuan' => 'Box', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'SET', 'nama_satuan' => 'Set', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'PAIR', 'nama_satuan' => 'Pair (Pasang)', 'created_at' => now(), 'updated_at' => now()],
            ['kode_satuan' => 'STRIP', 'nama_satuan' => 'Strip', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
