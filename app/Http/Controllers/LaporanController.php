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
     * Export laporan transaksi harian ke PDF
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportBulananPdf(Request $request)
    {
        try {
            $validated = $request->validate([
                'tanggal' => 'required|date',
            ]);

            $tanggal = Carbon::parse($validated['tanggal'])->startOfDay();
            $tanggalAkhir = Carbon::parse($validated['tanggal'])->endOfDay();

            // Ambil data transaksi pada tanggal tersebut
            $transaksi = Transaksi::with(['kasir:id,nama', 'detailTransaksis.produk:id,kode_produk,nama_produk,harga'])
                ->whereBetween('tgl_transaksi', [$tanggal, $tanggalAkhir])
                ->orderBy('tgl_transaksi', 'desc')
                ->get();

            if ($transaksi->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada transaksi pada tanggal yang dipilih.'
                ], 404);
            }

            // Hitung ringkasan
            $totalTransaksi = $transaksi->count();
            $totalPendapatan = $transaksi->sum('harga_total');
            $totalModal = $transaksi->sum(function ($t) {
                return $t->detailTransaksis->sum('subtotal_modal');
            });
            $totalKeuntungan = $totalPendapatan - $totalModal;
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
                            'subtotal_modal' => $detail->subtotal_modal,
                        ];
                    }),
                    'total_item' => $item->detailTransaksis->count(),
                ];
            });

            $data = [
                'tanggal' => $tanggal->format('Y-m-d'),
                'tanggal_format' => $tanggal->locale('id')->isoFormat('D MMMM YYYY'),
                'ringkasan' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'total_modal' => $totalModal,
                    'total_keuntungan' => $totalKeuntungan,
                    'total_item_terjual' => $totalItemTerjual,
                ],
                'data' => $dataTransaksi
            ];

            $pdf = Pdf::loadView('laporan.harian-pdf', $data);
            $pdf->setPaper('a4', 'portrait');

            $filename = 'Laporan_Transaksi_Harian_' . $tanggal->format('Ymd') . '.pdf';

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
                'message' => 'Gagal export PDF laporan transaksi harian.',
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

    /**
     * Statistik penjualan tahun ini (per bulan)
     * Data untuk Chart.js - Trend penjualan bulanan tahun berjalan
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistikTahunan(Request $request)
    {
        try {
            $tahun = $request->input('tahun', date('Y'));

            // Validasi tahun
            if ($tahun < 2000 || $tahun > 2100) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tahun tidak valid.'
                ], 422);
            }

            // Ambil data transaksi per bulan dalam tahun ini
            $transaksi = Transaksi::select(
                    DB::raw('MONTH(tgl_transaksi) as bulan'),
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan'),
                    DB::raw('YEAR(tgl_transaksi) as tahun')
                )
                ->whereYear('tgl_transaksi', $tahun)
                ->groupBy('tahun', 'bulan')
                ->orderBy('bulan', 'asc')
                ->get();

            // Inisialisasi array untuk 12 bulan
            $namaBulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
            $labels = [];
            $dataTransaksi = [];
            $dataPendapatan = [];

            // Isi data untuk semua bulan (0 jika tidak ada data)
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = $namaBulan[$i - 1];

                $found = $transaksi->firstWhere('bulan', $i);
                $dataTransaksi[] = $found ? (int)$found->total_transaksi : 0;
                $dataPendapatan[] = $found ? (float)$found->total_pendapatan : 0;
            }

            // Hitung total dan rata-rata
            $totalTransaksi = array_sum($dataTransaksi);
            $totalPendapatan = array_sum($dataPendapatan);
            $rataRataPerBulan = $totalTransaksi > 0 ? $totalPendapatan / count(array_filter($dataTransaksi)) : 0;

            return response()->json([
                'status' => true,
                'message' => 'Statistik penjualan tahunan berhasil diambil.',
                'periode' => [
                    'type' => 'yearly',
                    'tahun' => $tahun,
                ],
                'chart_data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Jumlah Transaksi',
                            'data' => $dataTransaksi,
                            'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                            'borderColor' => 'rgba(54, 162, 235, 1)',
                            'borderWidth' => 2,
                            'tension' => 0.4
                        ],
                        [
                            'label' => 'Total Pendapatan (Rp)',
                            'data' => $dataPendapatan,
                            'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                            'borderColor' => 'rgba(75, 192, 192, 1)',
                            'borderWidth' => 2,
                            'tension' => 0.4,
                            'yAxisID' => 'y1'
                        ]
                    ]
                ],
                'summary' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'rata_rata_per_bulan' => round($rataRataPerBulan, 2),
                    'bulan_terbaik' => [
                        'bulan' => $labels[array_search(max($dataPendapatan), $dataPendapatan)] ?? null,
                        'pendapatan' => max($dataPendapatan)
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil statistik penjualan tahunan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistik penjualan bulan ini (per hari)
     * Data untuk Chart.js - Trend penjualan harian bulan berjalan
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistikBulanan(Request $request)
    {
        try {
            $bulan = $request->input('bulan', date('n'));
            $tahun = $request->input('tahun', date('Y'));

            // Validasi
            if ($bulan < 1 || $bulan > 12 || $tahun < 2000 || $tahun > 2100) {
                return response()->json([
                    'status' => false,
                    'message' => 'Bulan atau tahun tidak valid.'
                ], 422);
            }

            $tanggalAwal = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth();
            $tanggalAkhir = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();
            $jumlahHari = $tanggalAkhir->day;

            // Ambil data transaksi per hari dalam bulan ini
            $transaksi = Transaksi::select(
                    DB::raw('DAY(tgl_transaksi) as hari'),
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->whereBetween('tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->groupBy('hari')
                ->orderBy('hari', 'asc')
                ->get();

            // Inisialisasi array untuk semua hari dalam bulan
            $labels = [];
            $dataTransaksi = [];
            $dataPendapatan = [];

            for ($i = 1; $i <= $jumlahHari; $i++) {
                $labels[] = $i;

                $found = $transaksi->firstWhere('hari', $i);
                $dataTransaksi[] = $found ? (int)$found->total_transaksi : 0;
                $dataPendapatan[] = $found ? (float)$found->total_pendapatan : 0;
            }

            $namaBulan = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            // Hitung total dan rata-rata
            $totalTransaksi = array_sum($dataTransaksi);
            $totalPendapatan = array_sum($dataPendapatan);
            $rataRataPerHari = $totalTransaksi > 0 ? $totalPendapatan / count(array_filter($dataTransaksi)) : 0;

            return response()->json([
                'status' => true,
                'message' => 'Statistik penjualan bulanan berhasil diambil.',
                'periode' => [
                    'type' => 'monthly',
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'nama_bulan' => $namaBulan[$bulan],
                ],
                'chart_data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Jumlah Transaksi',
                            'data' => $dataTransaksi,
                            'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                            'borderColor' => 'rgba(255, 99, 132, 1)',
                            'borderWidth' => 2,
                            'tension' => 0.4
                        ],
                        [
                            'label' => 'Total Pendapatan (Rp)',
                            'data' => $dataPendapatan,
                            'backgroundColor' => 'rgba(153, 102, 255, 0.5)',
                            'borderColor' => 'rgba(153, 102, 255, 1)',
                            'borderWidth' => 2,
                            'tension' => 0.4,
                            'yAxisID' => 'y1'
                        ]
                    ]
                ],
                'summary' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'rata_rata_per_hari' => round($rataRataPerHari, 2),
                    'hari_terbaik' => [
                        'tanggal' => $labels[array_search(max($dataPendapatan), $dataPendapatan)] ?? null,
                        'pendapatan' => max($dataPendapatan)
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil statistik penjualan bulanan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistik penjualan minggu ini (per hari)
     * Data untuk Chart.js - Trend penjualan 7 hari terakhir
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistikMingguan(Request $request)
    {
        try {
            // Ambil tanggal mulai (opsional, default: 7 hari terakhir dari hari ini)
            $tanggalAkhir = $request->input('tanggal_akhir')
                ? Carbon::parse($request->input('tanggal_akhir'))->endOfDay()
                : Carbon::now()->endOfDay();

            $tanggalAwal = $tanggalAkhir->copy()->subDays(6)->startOfDay();

            // Ambil data transaksi per hari dalam 7 hari terakhir
            $transaksi = Transaksi::select(
                    DB::raw('DATE(tgl_transaksi) as tanggal'),
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->whereBetween('tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
                ->get();

            // Inisialisasi array untuk 7 hari
            $labels = [];
            $dataTransaksi = [];
            $dataPendapatan = [];
            $namaHari = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

            $currentDate = $tanggalAwal->copy();
            for ($i = 0; $i < 7; $i++) {
                $dateStr = $currentDate->format('Y-m-d');
                $dayOfWeek = $currentDate->dayOfWeek;

                // Format label: "Sen, 23"
                $labels[] = $namaHari[$dayOfWeek] . ', ' . $currentDate->day;

                $found = $transaksi->firstWhere('tanggal', $dateStr);
                $dataTransaksi[] = $found ? (int)$found->total_transaksi : 0;
                $dataPendapatan[] = $found ? (float)$found->total_pendapatan : 0;

                $currentDate->addDay();
            }

            // Hitung total dan rata-rata
            $totalTransaksi = array_sum($dataTransaksi);
            $totalPendapatan = array_sum($dataPendapatan);
            $rataRataPerHari = $totalTransaksi > 0 ? $totalPendapatan / count(array_filter($dataTransaksi)) : 0;

            return response()->json([
                'status' => true,
                'message' => 'Statistik penjualan mingguan berhasil diambil.',
                'periode' => [
                    'type' => 'weekly',
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                ],
                'chart_data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Jumlah Transaksi',
                            'data' => $dataTransaksi,
                            'backgroundColor' => 'rgba(255, 206, 86, 0.5)',
                            'borderColor' => 'rgba(255, 206, 86, 1)',
                            'borderWidth' => 2,
                            'tension' => 0.4
                        ],
                        [
                            'label' => 'Total Pendapatan (Rp)',
                            'data' => $dataPendapatan,
                            'backgroundColor' => 'rgba(75, 192, 192, 0.5)',
                            'borderColor' => 'rgba(75, 192, 192, 1)',
                            'borderWidth' => 2,
                            'tension' => 0.4,
                            'yAxisID' => 'y1'
                        ]
                    ]
                ],
                'summary' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'rata_rata_per_hari' => round($rataRataPerHari, 2),
                    'hari_terbaik' => [
                        'hari' => $labels[array_search(max($dataPendapatan), $dataPendapatan)] ?? null,
                        'pendapatan' => max($dataPendapatan)
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil statistik penjualan mingguan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard statistik gabungan
     * Mengembalikan ringkasan statistik untuk cards
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dashboardStatistik()
    {
        try {
            $today = Carbon::today();
            $thisWeekStart = Carbon::now()->startOfWeek();
            $thisWeekEnd = Carbon::now()->endOfWeek();
            $thisMonthStart = Carbon::now()->startOfMonth();
            $thisMonthEnd = Carbon::now()->endOfMonth();
            $thisYearStart = Carbon::now()->startOfYear();
            $thisYearEnd = Carbon::now()->endOfYear();

            // Statistik Hari Ini
            $todayStats = Transaksi::whereDate('tgl_transaksi', $today)
                ->select(
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->first();

            // Statistik Minggu Ini
            $weekStats = Transaksi::whereBetween('tgl_transaksi', [$thisWeekStart, $thisWeekEnd])
                ->select(
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->first();

            // Statistik Bulan Ini
            $monthStats = Transaksi::whereBetween('tgl_transaksi', [$thisMonthStart, $thisMonthEnd])
                ->select(
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->first();

            // Statistik Tahun Ini
            $yearStats = Transaksi::whereBetween('tgl_transaksi', [$thisYearStart, $thisYearEnd])
                ->select(
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->first();

            // Total Produk dan Kategori
            $totalProduk = DB::table('tb_produk')->count();
            $totalKategori = DB::table('tb_kategori_produk')->count();

            // Produk stok menipis (< 10)
            $produkStokMenipis = DB::table('tb_produk')
                ->where('stok', '<', 10)
                ->count();

            return response()->json([
                'status' => true,
                'message' => 'Dashboard statistik berhasil diambil.',
                'data' => [
                    'cards' => [
                        'hari_ini' => [
                            'total_transaksi' => (int)($todayStats->total_transaksi ?? 0),
                            'total_pendapatan' => (float)($todayStats->total_pendapatan ?? 0),
                        ],
                        'minggu_ini' => [
                            'total_transaksi' => (int)($weekStats->total_transaksi ?? 0),
                            'total_pendapatan' => (float)($weekStats->total_pendapatan ?? 0),
                        ],
                        'bulan_ini' => [
                            'total_transaksi' => (int)($monthStats->total_transaksi ?? 0),
                            'total_pendapatan' => (float)($monthStats->total_pendapatan ?? 0),
                        ],
                        'tahun_ini' => [
                            'total_transaksi' => (int)($yearStats->total_transaksi ?? 0),
                            'total_pendapatan' => (float)($yearStats->total_pendapatan ?? 0),
                        ],
                    ],
                    'inventory' => [
                        'total_produk' => $totalProduk,
                        'total_kategori' => $totalKategori,
                        'produk_stok_menipis' => $produkStokMenipis,
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil dashboard statistik.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistik untuk comparison chart (Bar Chart)
     * Membandingkan hari ini, minggu ini, bulan ini, tahun ini
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistikComparison()
    {
        try {
            $today = Carbon::today();
            $thisWeekStart = Carbon::now()->startOfWeek();
            $thisWeekEnd = Carbon::now()->endOfWeek();
            $thisMonthStart = Carbon::now()->startOfMonth();
            $thisMonthEnd = Carbon::now()->endOfMonth();
            $thisYearStart = Carbon::now()->startOfYear();
            $thisYearEnd = Carbon::now()->endOfYear();

            // Statistik Hari Ini
            $todayStats = Transaksi::whereDate('tgl_transaksi', $today)
                ->select(
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->first();

            // Statistik Minggu Ini
            $weekStats = Transaksi::whereBetween('tgl_transaksi', [$thisWeekStart, $thisWeekEnd])
                ->select(
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->first();

            // Statistik Bulan Ini
            $monthStats = Transaksi::whereBetween('tgl_transaksi', [$thisMonthStart, $thisMonthEnd])
                ->select(
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->first();

            // Statistik Tahun Ini
            $yearStats = Transaksi::whereBetween('tgl_transaksi', [$thisYearStart, $thisYearEnd])
                ->select(
                    DB::raw('COUNT(*) as total_transaksi'),
                    DB::raw('SUM(harga_total) as total_pendapatan')
                )
                ->first();

            return response()->json([
                'status' => true,
                'message' => 'Statistik comparison berhasil diambil.',
                'chart_data' => [
                    'labels' => ['Hari Ini', 'Minggu Ini', 'Bulan Ini', 'Tahun Ini'],
                    'datasets' => [
                        [
                            'label' => 'Jumlah Transaksi',
                            'data' => [
                                (int)($todayStats->total_transaksi ?? 0),
                                (int)($weekStats->total_transaksi ?? 0),
                                (int)($monthStats->total_transaksi ?? 0),
                                (int)($yearStats->total_transaksi ?? 0),
                            ],
                            'backgroundColor' => [
                                'rgba(255, 99, 132, 0.5)',
                                'rgba(54, 162, 235, 0.5)',
                                'rgba(255, 206, 86, 0.5)',
                                'rgba(75, 192, 192, 0.5)',
                            ],
                            'borderColor' => [
                                'rgba(255, 99, 132, 1)',
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 206, 86, 1)',
                                'rgba(75, 192, 192, 1)',
                            ],
                            'borderWidth' => 2
                        ],
                        [
                            'label' => 'Total Pendapatan (Rp)',
                            'data' => [
                                (float)($todayStats->total_pendapatan ?? 0),
                                (float)($weekStats->total_pendapatan ?? 0),
                                (float)($monthStats->total_pendapatan ?? 0),
                                (float)($yearStats->total_pendapatan ?? 0),
                            ],
                            'backgroundColor' => [
                                'rgba(153, 102, 255, 0.5)',
                                'rgba(255, 159, 64, 0.5)',
                                'rgba(199, 199, 199, 0.5)',
                                'rgba(83, 102, 255, 0.5)',
                            ],
                            'borderColor' => [
                                'rgba(153, 102, 255, 1)',
                                'rgba(255, 159, 64, 1)',
                                'rgba(199, 199, 199, 1)',
                                'rgba(83, 102, 255, 1)',
                            ],
                            'borderWidth' => 2,
                            'yAxisID' => 'y1'
                        ]
                    ]
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil statistik comparison.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Statistik trend penjualan untuk line chart
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function statistikTrendPenjualan(Request $request)
    {
        try {
            // Ambil parameter 'days', default 30 hari.
            $days = $request->input('days', 30);

            // Validasi sederhana untuk parameter days
            if (!is_numeric($days) || $days < 2 || $days > 365) {
                return response()->json([
                    'status' => false,
                    'message' => 'Parameter "days" harus berupa angka antara 2 dan 365.'
                ], 422);
            }

            $tanggalAkhir = Carbon::now()->endOfDay();
            $tanggalAwal = Carbon::now()->subDays($days - 1)->startOfDay();

            // Ambil data transaksi per hari dalam rentang waktu yang ditentukan
            $transaksi = Transaksi::select(
                DB::raw('DATE(tgl_transaksi) as tanggal'),
                DB::raw('COUNT(*) as total_transaksi'),
                DB::raw('SUM(harga_total) as total_pendapatan')
            )
                ->whereBetween('tgl_transaksi', [$tanggalAwal, $tanggalAkhir])
                ->groupBy('tanggal')
                ->orderBy('tanggal', 'asc')
                ->get()
                // Ubah ke collection dengan key tanggal untuk lookup lebih cepat
                ->keyBy('tanggal');

            // Inisialisasi array untuk data chart
            $labels = [];
            $dataTransaksi = [];
            $dataPendapatan = [];

            // Iterasi untuk setiap hari dalam rentang tanggal
            $currentDate = $tanggalAwal->copy();
            for ($i = 0; $i < $days; $i++) {
                $dateStr = $currentDate->format('Y-m-d');
                // Format label: "23 Nov"
                $labels[] = $currentDate->format('d M');

                // Cek apakah ada data transaksi pada tanggal ini
                if (isset($transaksi[$dateStr])) {
                    $dataTransaksi[] = (int)$transaksi[$dateStr]->total_transaksi;
                    $dataPendapatan[] = (float)$transaksi[$dateStr]->total_pendapatan;
                } else {
                    $dataTransaksi[] = 0;
                    $dataPendapatan[] = 0;
                }

                $currentDate->addDay();
            }

            // Hitung ringkasan total
            $totalTransaksi = array_sum($dataTransaksi);
            $totalPendapatan = array_sum($dataPendapatan);
            // Hitung rata-rata hanya pada hari yang ada transaksi
            $daysWithTransactions = count(array_filter($dataTransaksi));
            $rataRataPerHari = $daysWithTransactions > 0 ? $totalPendapatan / $daysWithTransactions : 0;

            return response()->json([
                'status' => true,
                'message' => "Statistik trend penjualan {$days} hari terakhir berhasil diambil.",
                'periode' => [
                    'type' => 'daily_trend',
                    'days' => (int)$days,
                    'tanggal_awal' => $tanggalAwal->format('Y-m-d'),
                    'tanggal_akhir' => $tanggalAkhir->format('Y-m-d'),
                ],
                'chart_data' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Jumlah Transaksi',
                            'data' => $dataTransaksi,
                            'borderColor' => 'rgba(54, 162, 235, 1)',
                            'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                            'tension' => 0.4,
                            'fill' => true,
                        ],
                        [
                            'label' => 'Total Pendapatan (Rp)',
                            'data' => $dataPendapatan,
                            'borderColor' => 'rgba(75, 192, 192, 1)',
                            'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                            'tension' => 0.4,
                            'fill' => true,
                            'yAxisID' => 'y1'
                        ]
                    ]
                ],
                'summary' => [
                    'total_transaksi' => $totalTransaksi,
                    'total_pendapatan' => $totalPendapatan,
                    'rata_rata_pendapatan_per_hari' => round($rataRataPerHari, 2),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil statistik trend penjualan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
