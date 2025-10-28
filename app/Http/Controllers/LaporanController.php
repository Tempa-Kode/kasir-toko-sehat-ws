<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Laporan transaksi berdasarkan rentang periode
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function laporanPeriode(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            ]);

            $tanggalAwal = Carbon::parse($validated['tanggal_awal'])->startOfDay();
            $tanggalAkhir = Carbon::parse($validated['tanggal_akhir'])->endOfDay();

            // Ambil data transaksi dalam rentang periode
            $transaksi = Transaksi::with(['kasir:id,nama', 'detailTransaksis.produk:id,kode_produk,nama_produk,harga'])
                ->whereBetween('tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tgl_transaksi', 'desc')
                ->get();

            if ($transaksi->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada transaksi pada periode yang dipilih.'
                ], 404);
            }

            // Hitung ringkasan
            $totalTransaksi = $transaksi->count();
            $totalPendapatan = $transaksi->sum('harga_total');
            $totalItemTerjual = $transaksi->sum(function ($t) {
                return $t->detailTransaksis->sum('jumlah');
            });

            // Format data transaksi
            $dataTransaksi = $transaksi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'no_nota' => $item->no_nota,
                    'tgl_transaksi' => $item->tgl_transaksi,
                    'harga_total' => $item->harga_total,
                    'kasir' => [
                        'id' => $item->kasir->id,
                        'nama' => $item->kasir->nama,
                    ],
                    'detail_items' => $item->detailTransaksis->map(function ($detail) {
                        return [
                            'produk_id' => $detail->produk->id,
                            'kode_produk' => $detail->produk->kode_produk,
                            'nama_produk' => $detail->produk->nama_produk,
                            'harga_satuan' => $detail->produk->harga,
                            'jumlah' => $detail->jumlah,
                            'subtotal' => $detail->subtotal,
                        ];
                    }),
                    'total_item' => $item->detailTransaksis->count(),
                ];
            });

            return response()->json([
                'status' => true,
                'message' => 'Laporan transaksi periode berhasil diambil.',
                'periode' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                ],
                'ringkasan' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'total_item_terjual' => $totalItemTerjual,
                ],
                'data' => $dataTransaksi
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil laporan transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export laporan transaksi periode ke PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportPeriodePdf(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
            ]);

            $tanggalAwal = Carbon::parse($validated['tanggal_awal'])->startOfDay();
            $tanggalAkhir = Carbon::parse($validated['tanggal_akhir'])->endOfDay();

            // Ambil data transaksi dalam rentang periode
            $transaksi = Transaksi::with(['kasir:id,nama', 'detailTransaksis.produk:id,kode_produk,nama_produk,harga'])
                ->whereBetween('tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tgl_transaksi', 'desc')
                ->get();

            if ($transaksi->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada transaksi pada periode yang dipilih.'
                ], 404);
            }

            // Hitung ringkasan
            $totalTransaksi = $transaksi->count();
            $totalPendapatan = $transaksi->sum('harga_total');
            $totalItemTerjual = $transaksi->sum(function ($t) {
                return $t->detailTransaksis->sum('jumlah');
            });

            // Format data transaksi
            $dataTransaksi = $transaksi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'no_nota' => $item->no_nota,
                    'tgl_transaksi' => $item->tgl_transaksi,
                    'harga_total' => $item->harga_total,
                    'kasir' => [
                        'id' => $item->kasir->id,
                        'nama' => $item->kasir->nama,
                    ],
                    'detail_items' => $item->detailTransaksis->map(function ($detail) {
                        return [
                            'produk_id' => $detail->produk->id,
                            'kode_produk' => $detail->produk->kode_produk,
                            'nama_produk' => $detail->produk->nama_produk,
                            'harga_satuan' => $detail->produk->harga,
                            'jumlah' => $detail->jumlah,
                            'subtotal' => $detail->subtotal,
                        ];
                    }),
                    'total_item' => $item->detailTransaksis->count(),
                ];
            });

            $data = [
                'periode' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                ],
                'ringkasan' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'total_item_terjual' => $totalItemTerjual,
                ],
                'data' => $dataTransaksi
            ];

            $pdf = Pdf::loadView('laporan.periode-pdf', $data);
            $pdf->setPaper('a4', 'portrait');

            $filename = 'Laporan_Transaksi_Periode_' . $tanggalAwal->format('Ymd') . '_' . $tanggalAkhir->format('Ymd') . '.pdf';

            return $pdf->download($filename);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal export PDF laporan transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Laporan transaksi per bulan
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function laporanBulanan(Request $request)
    {
        try {
            $validated = $request->validate([
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2000|max:2100',
            ]);

            $bulan = $validated['bulan'];
            $tahun = $validated['tahun'];

            // Buat tanggal awal dan akhir bulan
            $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $tanggalAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

            // Ambil data transaksi dalam bulan tersebut
            $transaksi = Transaksi::with(['kasir:id,nama', 'detailTransaksis.produk:id,kode_produk,nama_produk,harga'])
                ->whereBetween('tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tgl_transaksi', 'desc')
                ->get();

            if ($transaksi->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada transaksi pada bulan yang dipilih.'
                ], 404);
            }

            // Hitung ringkasan
            $totalTransaksi = $transaksi->count();
            $totalPendapatan = $transaksi->sum('harga_total');
            $totalItemTerjual = $transaksi->sum(function ($t) {
                return $t->detailTransaksis->sum('jumlah');
            });

            // Grouping transaksi per tanggal
            $transaksiPerTanggal = $transaksi->groupBy(function ($item) {
                return Carbon::parse($item->tgl_transaksi)->format('Y-m-d');
            })->map(function ($group, $tanggal) {
                return [
                    'tanggal' => $tanggal,
                    'total_transaksi' => $group->count(),
                    'total_pendapatan' => $group->sum('harga_total'),
                    'transaksi' => $group->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'no_nota' => $item->no_nota,
                            'tgl_transaksi' => $item->tgl_transaksi,
                            'harga_total' => $item->harga_total,
                            'kasir' => [
                                'id' => $item->kasir->id,
                                'nama' => $item->kasir->nama,
                            ],
                            'detail_items' => $item->detailTransaksis->map(function ($detail) {
                                return [
                                    'produk_id' => $detail->produk->id,
                                    'kode_produk' => $detail->produk->kode_produk,
                                    'nama_produk' => $detail->produk->nama_produk,
                                    'harga_satuan' => $detail->produk->harga,
                                    'jumlah' => $detail->jumlah,
                                    'subtotal' => $detail->subtotal,
                                ];
                            }),
                            'total_item' => $item->detailTransaksis->count(),
                        ];
                    })->values()
                ];
            })->values();

            // Nama bulan dalam bahasa Indonesia
            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            return response()->json([
                'status' => true,
                'message' => 'Laporan transaksi bulanan berhasil diambil.',
                'periode' => [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nama_bulan' => $namaBulan[$bulan],
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                ],
                'ringkasan' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'total_item_terjual' => $totalItemTerjual,
                ],
                'data_per_tanggal' => $transaksiPerTanggal
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil laporan transaksi bulanan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export laporan transaksi bulanan ke PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportBulananPdf(Request $request)
    {
        try {
            $validated = $request->validate([
                'bulan' => 'required|integer|min:1|max:12',
                'tahun' => 'required|integer|min:2000|max:2100',
            ]);

            $bulan = $validated['bulan'];
            $tahun = $validated['tahun'];

            // Buat tanggal awal dan akhir bulan
            $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $tanggalAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

            // Ambil data transaksi dalam bulan tersebut
            $transaksi = Transaksi::with(['kasir:id,nama', 'detailTransaksis.produk:id,kode_produk,nama_produk,harga'])
                ->whereBetween('tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->orderBy('tgl_transaksi', 'desc')
                ->get();

            if ($transaksi->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada transaksi pada bulan yang dipilih.'
                ], 404);
            }

            // Hitung ringkasan
            $totalTransaksi = $transaksi->count();
            $totalPendapatan = $transaksi->sum('harga_total');
            $totalItemTerjual = $transaksi->sum(function ($t) {
                return $t->detailTransaksis->sum('jumlah');
            });

            // Grouping transaksi per tanggal
            $transaksiPerTanggal = $transaksi->groupBy(function ($item) {
                return Carbon::parse($item->tgl_transaksi)->format('Y-m-d');
            })->map(function ($group, $tanggal) {
                return [
                    'tanggal' => $tanggal,
                    'total_transaksi' => $group->count(),
                    'total_pendapatan' => $group->sum('harga_total'),
                    'transaksi' => $group->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'no_nota' => $item->no_nota,
                            'tgl_transaksi' => $item->tgl_transaksi,
                            'harga_total' => $item->harga_total,
                            'kasir' => [
                                'id' => $item->kasir->id,
                                'nama' => $item->kasir->nama,
                            ],
                            'detail_items' => $item->detailTransaksis->map(function ($detail) {
                                return [
                                    'produk_id' => $detail->produk->id,
                                    'kode_produk' => $detail->produk->kode_produk,
                                    'nama_produk' => $detail->produk->nama_produk,
                                    'harga_satuan' => $detail->produk->harga,
                                    'jumlah' => $detail->jumlah,
                                    'subtotal' => $detail->subtotal,
                                ];
                            }),
                            'total_item' => $item->detailTransaksis->count(),
                        ];
                    })->values()
                ];
            })->values();

            // Nama bulan dalam bahasa Indonesia
            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $data = [
                'periode' => [
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nama_bulan' => $namaBulan[$bulan],
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                ],
                'ringkasan' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'total_item_terjual' => $totalItemTerjual,
                ],
                'data_per_tanggal' => $transaksiPerTanggal
            ];

            $pdf = Pdf::loadView('laporan.bulanan-pdf', $data);
            $pdf->setPaper('a4', 'portrait');

            $filename = 'Laporan_Transaksi_Bulanan_' . $namaBulan[$bulan] . '_' . $tahun . '.pdf';

            return $pdf->download($filename);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal export PDF laporan transaksi bulanan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Laporan produk terlaris dalam periode
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function produkTerlaris(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
                'limit' => 'nullable|integer|min:1|max:100',
            ]);

            $tanggalAwal = Carbon::parse($validated['tanggal_awal'])->startOfDay();
            $tanggalAkhir = Carbon::parse($validated['tanggal_akhir'])->endOfDay();
            $limit = $validated['limit'] ?? 10;

            // Query produk terlaris
            $produkTerlaris = DB::table('tb_detail_transaksi')
                ->join('tb_transaksi', 'tb_detail_transaksi.transaksi_id', '=', 'tb_transaksi.id')
                ->join('tb_produk', 'tb_detail_transaksi.produk_id', '=', 'tb_produk.id')
                ->whereBetween('tb_transaksi.tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->select(
                    'tb_produk.id',
                    'tb_produk.kode_produk',
                    'tb_produk.nama_produk',
                    'tb_produk.harga',
                    DB::raw('SUM(tb_detail_transaksi.jumlah) as total_terjual'),
                    DB::raw('SUM(tb_detail_transaksi.subtotal) as total_pendapatan'),
                    DB::raw('COUNT(DISTINCT tb_detail_transaksi.transaksi_id) as total_transaksi')
                )
                ->groupBy('tb_produk.id', 'tb_produk.kode_produk', 'tb_produk.nama_produk', 'tb_produk.harga')
                ->orderBy('total_terjual', 'desc')
                ->limit($limit)
                ->get();

            if ($produkTerlaris->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data produk terlaris pada periode yang dipilih.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Laporan produk terlaris berhasil diambil.',
                'periode' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                ],
                'data' => $produkTerlaris
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil laporan produk terlaris.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export laporan produk terlaris ke PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportProdukTerlarisPdf(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal_awal' => 'required|date',
                'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
                'limit' => 'nullable|integer|min:1|max:100',
            ]);

            $tanggalAwal = Carbon::parse($validated['tanggal_awal'])->startOfDay();
            $tanggalAkhir = Carbon::parse($validated['tanggal_akhir'])->endOfDay();
            $limit = $validated['limit'] ?? 10;

            // Query produk terlaris
            $produkTerlaris = DB::table('tb_detail_transaksi')
                ->join('tb_transaksi', 'tb_detail_transaksi.transaksi_id', '=', 'tb_transaksi.id')
                ->join('tb_produk', 'tb_detail_transaksi.produk_id', '=', 'tb_produk.id')
                ->whereBetween('tb_transaksi.tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->select(
                    'tb_produk.id',
                    'tb_produk.kode_produk',
                    'tb_produk.nama_produk',
                    'tb_produk.harga',
                    DB::raw('SUM(tb_detail_transaksi.jumlah) as total_terjual'),
                    DB::raw('SUM(tb_detail_transaksi.subtotal) as total_pendapatan'),
                    DB::raw('COUNT(DISTINCT tb_detail_transaksi.transaksi_id) as total_transaksi')
                )
                ->groupBy('tb_produk.id', 'tb_produk.kode_produk', 'tb_produk.nama_produk', 'tb_produk.harga')
                ->orderBy('total_terjual', 'desc')
                ->limit($limit)
                ->get();

            if ($produkTerlaris->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data produk terlaris pada periode yang dipilih.'
                ], 404);
            }

            $data = [
                'periode' => [
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                ],
                'data' => $produkTerlaris
            ];

            $pdf = Pdf::loadView('laporan.produk-terlaris-pdf', $data);
            $pdf->setPaper('a4', 'landscape');

            $filename = 'Laporan_Produk_Terlaris_' . $tanggalAwal->format('Ymd') . '_' . $tanggalAkhir->format('Ymd') . '.pdf';

            return $pdf->download($filename);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal export PDF laporan produk terlaris.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
