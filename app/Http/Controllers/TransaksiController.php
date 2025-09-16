<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Dedoc\Scramble\Attributes\BodyParameter;
use Illuminate\Validation\ValidationException;

class TransaksiController extends Controller
{
    /**
     * Get all transactions
     */
    public function index()
    {
        try {
            $data = Transaksi::with(['kasir:id,nama', 'detailTransaksis.produk:id,kode_produk,nama_produk'])
                ->orderBy('created_at', 'desc')
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data transaksi.'
                ], 404);
            }

            $transaksi = collect();

            foreach ($data as $item) {
                $transaksi->push([
                    'id' => $item->id,
                    'no_nota' => $item->no_nota,
                    'tgl_transaksi' => $item->tgl_transaksi,
                    'harga_total' => $item->harga_total,
                    'kasir' => [
                        'id' => $item->kasir->id,
                        'nama' => $item->kasir->nama,
                    ],
                    'total_item' => $item->detailTransaksis->count(),
                    'created_at' => $item->created_at
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data transaksi berhasil diambil.',
                'data' => $transaksi
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new transaction
     */
    #[BodyParameter('kasir_id', required: true, type: 'integer', example: 3, description: 'ID kasir yang melakukan transaksi')]
    #[BodyParameter('dibayar', required: true, type: 'number', example: 150000, description: 'Jumlah uang yang dibayarkan customer')]
    #[BodyParameter('items', required: true, type: 'array', example: [['produk_id' => 1, 'jumlah' => 2], ['produk_id' => 3, 'jumlah' => 1]], description: 'Array berisi daftar produk yang dibeli')]
    #[BodyParameter('items.*.produk_id', required: true, type: 'integer', example: 1, description: 'ID produk yang akan dibeli')]
    #[BodyParameter('items.*.jumlah', required: true, type: 'integer', example: 2, description: 'Jumlah/quantity produk yang dibeli')]
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'kasir_id' => 'required|exists:tb_pengguna,id',
                'items' => 'required|array|min:1',
                'items.*.produk_id' => 'required|exists:tb_produk,id',
                'items.*.jumlah' => 'required|integer|min:1',
                'dibayar' => 'required|numeric|min:0',
            ]);

            // Calculate total price and validate stock
            $hargaTotal = 0;
            $itemsToProcess = [];

            foreach ($validated['items'] as $item) {
                $produk = Produk::find($item['produk_id']);

                if (!$produk) {
                    throw new \Exception("Produk dengan ID {$item['produk_id']} tidak ditemukan.");
                }

                if ($produk->stok < $item['jumlah']) {
                    throw new \Exception("Stok produk {$produk->nama_produk} tidak mencukupi. Stok tersedia: {$produk->stok}");
                }

                $subtotal = $produk->harga * $item['jumlah'];
                $hargaTotal += $subtotal;

                $itemsToProcess[] = [
                    'produk' => $produk,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $subtotal
                ];
            }

            // Calculate kembalian
            $kembalian = $validated['dibayar'] - $hargaTotal;

            // Validate payment amount
            if ($validated['dibayar'] < $hargaTotal) {
                throw new \Exception("Uang yang dibayarkan tidak mencukupi. Total: Rp " . number_format($hargaTotal, 0, ',', '.') . ", Dibayar: Rp " . number_format($validated['dibayar'], 0, ',', '.'));
            }

            // Generate no nota dengan format: NT[YYMMDD][ID_SEQUENCE]
            $lastTransaksi = Transaksi::orderBy('id', 'desc')->first();
            $lastId = $lastTransaksi ? $lastTransaksi->id : 0;

            // Format: NT + YYMMDD + 4 digit sequence
            $dateFormat = now()->format('ymd'); // 250916 untuk 16 Sept 2025
            $sequence = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT); // 0001, 0002, dst
            $newNoNota = 'NT' . $dateFormat . $sequence;

            // Create transaction
            $transaksi = Transaksi::create([
                'no_nota' => $newNoNota,
                'tgl_transaksi' => now(),
                'harga_total' => $hargaTotal,
                'kasir_id' => $validated['kasir_id']
            ]);

            // Create transaction details and update stock
            foreach ($itemsToProcess as $item) {
                // Create detail transaction
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['produk']->id,
                    'jumlah' => $item['jumlah'],
                    'subtotal' => $item['subtotal']
                ]);

                // Update product stock
                $item['produk']->update([
                    'stok' => $item['produk']->stok - $item['jumlah']
                ]);
            }

            DB::commit();

            // Load relationships for response
            $transaksi->load(['kasir:id,nama', 'detailTransaksis.produk:id,kode_produk,nama_produk,harga']);

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil dibuat.',
                'data' => [
                    'id' => $transaksi->id,
                    'no_nota' => $transaksi->no_nota,
                    'tgl_transaksi' => $transaksi->tgl_transaksi,
                    'harga_total' => $transaksi->harga_total,
                    'dibayar' => $validated['dibayar'],
                    'kembalian' => $kembalian,
                    'kasir' => [
                        'id' => $transaksi->kasir->id,
                        'nama' => $transaksi->kasir->nama,
                    ],
                    'items' => $transaksi->detailTransaksis->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'produk' => [
                                'id' => $detail->produk->id,
                                'kode_produk' => $detail->produk->kode_produk,
                                'nama_produk' => $detail->produk->nama_produk,
                                'harga' => $detail->produk->harga,
                            ],
                            'jumlah' => $detail->jumlah,
                            'subtotal' => $detail->subtotal
                        ];
                    })
                ]
            ], 201);

        } catch (ValidationException $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified transaction
     */
    public function show(string $id)
    {
        try {
            $transaksi = Transaksi::with(['kasir:id,nama', 'detailTransaksis.produk:id,kode_produk,nama_produk,harga'])
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Detail transaksi berhasil diambil.',
                'data' => [
                    'id' => $transaksi->id,
                    'no_nota' => $transaksi->no_nota,
                    'tgl_transaksi' => $transaksi->tgl_transaksi,
                    'harga_total' => $transaksi->harga_total,
                    'kasir' => [
                        'id' => $transaksi->kasir->id,
                        'nama' => $transaksi->kasir->nama,
                    ],
                    'items' => $transaksi->detailTransaksis->map(function ($detail) {
                        return [
                            'id' => $detail->id,
                            'produk' => [
                                'id' => $detail->produk->id,
                                'kode_produk' => $detail->produk->kode_produk,
                                'nama_produk' => $detail->produk->nama_produk,
                                'harga' => $detail->produk->harga,
                            ],
                            'jumlah' => $detail->jumlah,
                            'subtotal' => $detail->subtotal
                        ];
                    }),
                    'total_items' => $transaksi->detailTransaksis->count(),
                    'created_at' => $transaksi->created_at,
                    'updated_at' => $transaksi->updated_at
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil detail transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel/Delete transaction and restore stock
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $transaksi = Transaksi::with('detailTransaksis.produk')->findOrFail($id);

            // Restore product stock
            foreach ($transaksi->detailTransaksis as $detail) {
                $detail->produk->update([
                    'stok' => $detail->produk->stok + $detail->jumlah
                ]);
            }

            // Delete transaction details first (foreign key constraint)
            DetailTransaksi::where('transaksi_id', $id)->delete();

            // Delete transaction
            $transaksi->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Transaksi berhasil dibatalkan dan stok telah dikembalikan.'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Transaksi tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => 'Gagal membatalkan transaksi.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get transaction report by date range
     */
    public function report(Request $request)
    {
        try {
            $validated = $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date'
            ]);

            $transaksis = Transaksi::with(['kasir:id,nama', 'detailTransaksis'])
                ->whereBetween('tgl_transaksi', [$validated['start_date'], $validated['end_date']])
                ->get();

            if ($transaksis->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada transaksi dalam rentang tanggal tersebut.'
                ], 404);
            }

            $totalPendapatan = $transaksis->sum('harga_total');
            $totalTransaksi = $transaksis->count();
            $totalItem = $transaksis->sum(function ($transaksi) {
                return $transaksi->detailTransaksis->sum('jumlah');
            });

            return response()->json([
                'status' => true,
                'message' => 'Laporan transaksi berhasil diambil.',
                'period' => [
                    'start_date' => $validated['start_date'],
                    'end_date' => $validated['end_date']
                ],
                'summary' => [
                    'total_pendapatan' => $totalPendapatan,
                    'total_transaksi' => $totalTransaksi,
                    'total_item_terjual' => $totalItem,
                    'rata_rata_per_transaksi' => $totalTransaksi > 0 ? round($totalPendapatan / $totalTransaksi, 2) : 0
                ],
                'data' => $transaksis->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'no_nota' => $item->no_nota,
                        'tgl_transaksi' => $item->tgl_transaksi,
                        'harga_total' => $item->harga_total,
                        'kasir' => $item->kasir->nama,
                        'total_items' => $item->detailTransaksis->sum('jumlah')
                    ];
                })
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil laporan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
