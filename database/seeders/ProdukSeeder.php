<?php

namespace Database\Seeders;

use App\Models\KategoriProduk;
use App\Models\Produk;
use App\Models\Satuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $heading = true;
        $input_file = fopen(base_path("database/data/data_indogrosir.csv"), "r");
        while (($record = fgetcsv($input_file, 1000, ",")) !== FALSE)
        {
            if (!$heading)
            {   $stok = 100;

                // Trim whitespace untuk menghindari masalah spasi
                $kodeSatuan = trim($record['3']);
                $namaKategori = trim($record['2']);

                $satuan = Satuan::select('id')
                    ->where('kode_satuan', $kodeSatuan)
                    ->first();

                // Debugging: tampilkan jika satuan tidak ditemukan
                if (!$satuan) {
                    echo "❌ Satuan tidak ditemukan untuk kode: '{$kodeSatuan}' (Produk: {$record['1']})\n";
                    continue; // Skip produk ini
                }

                $kategori = KategoriProduk::select('id')
                    ->where('nama_kategori', $namaKategori)
                    ->first();

                // Debugging: tampilkan jika kategori tidak ditemukan
                if (!$kategori) {
                    echo "⚠️  Kategori tidak ditemukan untuk: '{$namaKategori}' (Produk: {$record['1']})\n";
                }

                $produk = Produk::create([
                    'satuan_id' => $satuan->id,
                    'kategori_id' => $kategori?->id,
                    'kode_produk' => $record['0'],
                    'nama_produk' => $record['1'],
                    'harga_modal' => $record['4'],
                    'harga' => $record['5'],
                    'stok' => $stok,
                ]);
                $produk->riwayatProdukMasuk()->create([
                    'stok' => $stok,
                    'distributor' => 'Indogrosir',
                    'tanggal_masuk' => now(),
                ]);
            }
            $heading = false;
        }
        fclose($input_file);
    }
}
