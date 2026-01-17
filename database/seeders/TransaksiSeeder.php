<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Carbon;
use App\Models\DetailTransaksi;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransaksiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua produk
        $produks = Produk::all();

        if ($produks->isEmpty()) {
            $this->command->error('Tidak ada produk di database. Jalankan seeder produk terlebih dahulu.');
            return;
        }

        // Ambil user kasir (bisa kasir atau admin)
        $kasirs = User::whereIn('hak_akses', ['kasir', 'admin'])->get();

        if ($kasirs->isEmpty()) {
            $this->command->error('Tidak ada user kasir/admin di database.');
            return;
        }

        // Tanggal mulai dan akhir
        $startDate = Carbon::create(2025, 1, 1);
        $endDate = Carbon::create(2026, 1, 17);

        // Target omset harian rata-rata
        $targetOmsetHarian = 35000000;

        // Counter untuk nomor nota
        $notaCounter = 1;

        $this->command->info('Mulai generate data transaksi...');   
        $progressBar = $this->command->getOutput()->createProgressBar($startDate->diffInDays($endDate) + 1);

        // Loop untuk setiap hari
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            // Variasi omset harian (90% - 110% dari target) - Dipersempit
            $omsetHariIni = $targetOmsetHarian * (rand(90, 110) / 100);

            // Jumlah transaksi per hari bervariasi (50-70 transaksi)
            $jumlahTransaksi = rand(50, 70);

            // Distribusi transaksi sepanjang hari
            $transaksiPerJam = $this->distribusiTransaksi($jumlahTransaksi, $date);

            $totalOmsetHariIni = 0;

            foreach ($transaksiPerJam as $index => $waktuTransaksi) {
                // Pilih kasir secara acak
                $kasir = $kasirs->random();

                // Generate nomor nota
                $noNota = 'NT' . $date->format('ymd') . str_pad($notaCounter, 4, '0', STR_PAD_LEFT);
                $notaCounter++;

                // Hitung target per transaksi secara proporsional
                $sisaOmset = $omsetHariIni - $totalOmsetHariIni;
                $sisaTransaksi = $jumlahTransaksi - $index;

                if ($sisaTransaksi > 0) {
                    $targetTransaksi = $sisaOmset / $sisaTransaksi;
                } else {
                    $targetTransaksi = 500000; // Default jika terjadi edge case
                }

                // Batasi nilai transaksi agar realistis
                $targetTransaksi = max(100000, min($targetTransaksi, 1500000)); // 100rb - 1.5jt

                // Buat transaksi
                $transaksi = Transaksi::create([
                    'no_nota' => $noNota,
                    'tgl_transaksi' => $waktuTransaksi,
                    'harga_total' => 0, // Akan diupdate setelah detail dibuat
                    'kasir_id' => $kasir->id,
                ]);

                // Generate detail transaksi
                $detailResult = $this->generateDetailTransaksi($transaksi, $produks, $targetTransaksi);

                // Update harga total transaksi
                $transaksi->update([
                    'harga_total' => $detailResult['total']
                ]);

                $totalOmsetHariIni += $detailResult['total'];
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info('Seeder transaksi selesai!');

        // Tampilkan statistik
        $totalTransaksi = Transaksi::count();
        $totalOmset = Transaksi::sum('harga_total');
        $rataRataOmsetHarian = $totalOmset / 365;

        $this->command->info("Total Transaksi: " . number_format($totalTransaksi));
        $this->command->info("Total Omset: Rp " . number_format($totalOmset, 0, ',', '.'));
        $this->command->info("Rata-rata Omset Harian: Rp " . number_format($rataRataOmsetHarian, 0, ',', '.'));
    }

    /**
     * Distribusi transaksi sepanjang hari
     */
    private function distribusiTransaksi($jumlah, $tanggal)
    {
        $transaksiList = [];

        // Jam buka toko: 08:00 - 21:00 (13 jam)
        // Jam sibuk: 08:00-10:00 (pagi), 12:00-13:00 (siang), 17:00-20:00 (sore/malam)

        for ($i = 0; $i < $jumlah; $i++) {
            // Tentukan jam berdasarkan probabilitas
            $rand = rand(1, 100);

            if ($rand <= 25) {
                // 25% transaksi di jam 08:00-10:00 (pagi)
                $jam = rand(8, 9);
                $menit = rand(0, 59);
            } elseif ($rand <= 40) {
                // 15% transaksi di jam 12:00-13:00 (siang)
                $jam = 12;
                $menit = rand(0, 59);
            } elseif ($rand <= 75) {
                // 35% transaksi di jam 17:00-20:00 (sore/malam)
                $jam = rand(17, 19);
                $menit = rand(0, 59);
            } else {
                // 25% transaksi di jam lainnya
                $jamLain = [10, 11, 13, 14, 15, 16, 20];
                $jam = $jamLain[array_rand($jamLain)];
                $menit = rand(0, 59);
            }

            $detik = rand(0, 59);

            $waktu = $tanggal->copy()->setTime($jam, $menit, $detik);
            $transaksiList[] = $waktu;
        }

        // Urutkan berdasarkan waktu
        usort($transaksiList, function($a, $b) {
            return $a->timestamp - $b->timestamp;
        });

        return $transaksiList;
    }

    /**
     * Generate detail transaksi
     */
    private function generateDetailTransaksi($transaksi, $produks, $targetNilai)
    {
        $totalHarga = 0;
        $totalModal = 0;

        // Jumlah item per transaksi (2-6 item) - Dikurangi agar lebih realistis
        $jumlahItem = rand(2, 6);

        // Pilih produk secara acak
        $produkTerpilih = $produks->random(min($jumlahItem, $produks->count()));

        // Hitung alokasi per item
        $targetPerItem = $targetNilai / $jumlahItem;

        foreach ($produkTerpilih as $index => $produk) {
            // Tentukan jumlah pembelian berdasarkan harga produk
            $hargaProduk = $produk->harga;

            // Hitung jumlah beli berdasarkan target per item
            if ($index == $produkTerpilih->count() - 1) {
                // Item terakhir: sesuaikan dengan sisa target
                $sisaNilai = $targetNilai - $totalHarga;
                $jumlahBeli = max(1, round($sisaNilai / $hargaProduk));

                // Batasi agar tidak terlalu besar
                if ($hargaProduk < 100000) {
                    $jumlahBeli = min($jumlahBeli, 5); // Produk murah max 5
                } else if ($hargaProduk < 300000) {
                    $jumlahBeli = min($jumlahBeli, 3); // Produk sedang max 3
                } else {
                    $jumlahBeli = min($jumlahBeli, 2); // Produk mahal max 2
                }
            } else {
                // Item biasa: beli 1-3 saja
                if ($hargaProduk < 100000) {
                    $jumlahBeli = rand(1, 3); // Produk murah 1-3
                } else if ($hargaProduk < 300000) {
                    $jumlahBeli = rand(1, 2); // Produk sedang 1-2
                } else {
                    $jumlahBeli = 1; // Produk mahal hanya 1
                }
            }

            $subtotal = $hargaProduk * $jumlahBeli;
            $subtotalModal = $produk->harga_modal * $jumlahBeli;

            // Cek agar tidak melebihi target transaksi
            if ($totalHarga + $subtotal > $targetNilai * 1.3) {
                // Skip item ini jika akan membuat total terlalu besar
                continue;
            }

            DetailTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $produk->id,
                'jumlah' => $jumlahBeli,
                'subtotal' => $subtotal,
                'subtotal_modal' => $subtotalModal,
            ]);

            $totalHarga += $subtotal;
            $totalModal += $subtotalModal;
        }

        return [
            'total' => $totalHarga,
            'modal' => $totalModal,
        ];
    }
}
