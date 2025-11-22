<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use App\Models\RiwayatProdukMasuk;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\PathParameter;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class ProdukController extends Controller
{
    /**
     * Get all produk.
     */
    public function index()
    {
        try {
            $data = Produk::with('satuan', 'kategori')->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data produk.'
                ], 404);
            }

            $produk = collect();

            foreach ($data as $item) {
                $produk->push([
                    'id' => $item->id,
                    'kode_produk' => $item->kode_produk,
                    'nama_produk' => $item->nama_produk,
                    'harga_modal' => $item->harga_modal,
                    'harga' => $item->harga,
                    'stok' => $item->stok,
                    'satuan' => $item->satuan ? [
                        'id' => $item->satuan->id,
                        'kode_satuan' => $item->satuan->kode_satuan,
                        'nama_satuan' => $item->satuan->nama_satuan,
                    ] : null,
                    'kategori' => $item->kategori ? [
                        'id' => $item->kategori->id,
                        'nama_kategori' => $item->kategori->nama_kategori,
                    ] : null,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data produk berhasil diambil.',
                'data' => $produk
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data produk.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Store a new produk
     */
    #[BodyParameter('satuan_id', required: true, type: 'integer', example: 1)]
    #[BodyParameter('kategori_id', required: true, type: 'integer', example: 1)]
    #[BodyParameter('kode_produk', required: true, type: 'integer', example: 'PRD001')]
    #[BodyParameter('nama_produk', required: true, type: 'integer', example: 'Produk 1')]
    #[BodyParameter('harga_modal', required: true, type: 'integer', example: 8000)]
    #[BodyParameter('harga', required: true, type: 'integer',example: 10000)]
    #[BodyParameter('stok', required: true,  type: 'integer', example: 10)]
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'satuan_id' => 'required|exists:tb_satuan,id',
                'kategori_id' => 'required|exists:tb_kategori_produk,id',
                'kode_produk' => 'required|string|unique:tb_produk,kode_produk',
                'nama_produk' => 'required|string',
                'harga_modal' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

            $produk = Produk::create($validated);

            RiwayatProdukMasuk::create([
                'produk_id' => $produk->id,
                'stok' => $produk->stok,
                'tanggal_masuk' => now(),
            ]);

            $produk->load('satuan', 'kategori');

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil dibuat.',
                'data' => [
                    'id' => $produk->id,
                    'kode_produk' => $produk->kode_produk,
                    'nama_produk' => $produk->nama_produk,
                    'harga_modal' => $produk->harga_modal,
                    'harga' => $produk->harga,
                    'stok' => $produk->stok,
                    'satuan' => [
                        'id' => $produk->satuan->id,
                        'kode_satuan' => $produk->satuan->kode_satuan,
                        'nama_satuan' => $produk->satuan->nama_satuan,
                    ],
                    'kategori' => [
                        'id' => $produk->kategori->id,
                        'nama_kategori' => $produk->kategori->nama_kategori,
                    ],
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a spesific produk data
     */
    public function show($id)
    {
        try {
            $produk = Produk::with('satuan', 'kategori')->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Data produk berhasil diambil.',
                'data' => [
                    'id' => $produk->id,
                    'kode_produk' => $produk->kode_produk,
                    'nama_produk' => $produk->nama_produk,
                    'harga_modal' => $produk->harga_modal,
                    'harga' => $produk->harga,
                    'stok' => $produk->stok,
                    'satuan' => [
                        'id' => $produk->satuan->id,
                        'kode_satuan' => $produk->satuan->kode_satuan,
                        'nama_satuan' => $produk->satuan->nama_satuan,
                    ],
                    'kategori' => [
                        'id' => $produk->kategori->id,
                        'nama_kategori' => $produk->kategori->nama_kategori,
                    ],
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a spesific produk data
     */
    #[PathParameter('id', description: 'ID of the produk', required: true, example: 1)]
    #[BodyParameter('satuan_id', required: true, type: 'integer', example: 1)]
    #[BodyParameter('kategori_id', required: true, type: 'integer', example: 1)]
    #[BodyParameter('kode_produk', required: true, type: 'integer', example: 'PRD001')]
    #[BodyParameter('nama_produk', required: true, type: 'integer', example: 'Produk 1 Update')]
    #[BodyParameter('harga_modal', required: true, type: 'integer', example: 9000)]
    #[BodyParameter('harga', required: true, type: 'integer',example: 10000)]
    #[BodyParameter('stok', required: true,  type: 'integer', example: 100)]
    public function update(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);

            $validated = $request->validate([
                'satuan_id' => 'required|exists:tb_satuan,id',
                'kategori_id' => 'required|exists:tb_kategori_produk,id',
                'kode_produk' => 'required|string|unique:tb_produk,kode_produk,' . $id,
                'nama_produk' => 'required|string',
                'harga_modal' => 'required|numeric|min:0',
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

            $produk->update($validated);
            $produk->load('satuan', 'kategori');

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil diperbarui.',
                'data' => [
                    'id' => $produk->id,
                    'kode_produk' => $produk->kode_produk,
                    'nama_produk' => $produk->nama_produk,
                    'harga_modal' => $produk->harga_modal,
                    'harga' => $produk->harga,
                    'stok' => $produk->stok,
                    'satuan' => [
                        'id' => $produk->satuan->id,
                        'kode_satuan' => $produk->satuan->kode_satuan,
                        'nama_satuan' => $produk->satuan->nama_satuan,
                    ],
                    'kategori' => [
                        'id' => $produk->kategori->id,
                        'nama_kategori' => $produk->kategori->nama_kategori,
                    ],
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a spesific produk data
     */
    #[PathParameter('id', description: 'ID of the produk', required: true, example: 1)]
    public function destroy($id)
    {
        try {
            $produk = Produk::findOrFail($id);
            $produk->delete();

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil dihapus.'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update stock quantity for a specific product
     */
    #[PathParameter('id', description: 'ID of the produk', required: true, example: 1)]
    #[BodyParameter('quantity', required: true, type: 'integer', example: 50, description: 'Jumlah stok yang akan ditambahkan')]
    #[BodyParameter('distributor', required: false, type: 'string', example: 'PT. Mencari Cinta Sejati', description: 'Nama distributor (opsional)')]
    public function updateStock(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
                'distributor' => 'nullable|string|max:50',
            ]);

            $quantity = $validated['quantity'];
            $distributor = $validated['distributor'] ?? null;

            $newStock = $produk->stok + $quantity;

            // Update stock
            $produk->update(['stok' => $newStock]);

            // Record stock history
            RiwayatProdukMasuk::create([
                'produk_id' => $produk->id,
                'stok' => $quantity,
                'distributor' => $distributor,
                'tanggal_masuk' => now(),
            ]);

            $produk->load('satuan', 'kategori', 'riwayatProdukMasuk');

            return response()->json([
                'status' => true,
                'message' => "Stok berhasil diperbarui sebanyak {$quantity}. Stok sekarang: {$newStock}",
                'data' => [
                    'id' => $produk->id,
                    'kode_produk' => $produk->kode_produk,
                    'nama_produk' => $produk->nama_produk,
                    'harga' => $produk->harga,
                    'stok_sebelumnya' => $produk->stok - $quantity,
                    'stok_sekarang' => $produk->stok,
                    'distributor' => $distributor ?? 'N/A',
                    'perubahan' => "+{$quantity}",
                    'satuan' => [
                        'id' => $produk->satuan->id,
                        'kode_satuan' => $produk->satuan->kode_satuan,
                        'nama_satuan' => $produk->satuan->nama_satuan,
                    ],
                    'kategori' => [
                        'id' => $produk->kategori->id,
                        'nama_kategori' => $produk->kategori->nama_kategori,
                    ],
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan.'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengupdate stok produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search products by code or name
     */
    #[BodyParameter('search', required: true, type: 'string', example: 'PRD001', description: 'Search by product code or name')]
    #[BodyParameter('limit', required: false, type: 'integer', example: 10, description: 'Limit results (default: 10)')]
    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'search' => 'required|string|min:1',
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            $search = $validated['search'];
            $limit = $validated['limit'] ?? 100;

            $data = Produk::with('satuan', 'kategori')
                ->where(function ($query) use ($search) {
                    $query->where('kode_produk', 'LIKE', '%' . $search . '%')
                        ->orWhere('nama_produk', 'LIKE', '%' . $search . '%');
                })
                ->limit($limit)
                ->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada produk yang ditemukan dengan kata kunci: ' . $search
                ], 404);
            }

            $produk = collect();

            foreach ($data as $item) {
                $produk->push([
                    'id' => $item->id,
                    'kode_produk' => $item->kode_produk,
                    'nama_produk' => $item->nama_produk,
                    'harga_modal' => $item->harga_modal,
                    'harga' => $item->harga,
                    'stok' => $item->stok,
                    'satuan' => $item->satuan ? [
                        'id' => $item->satuan->id,
                        'kode_satuan' => $item->satuan->kode_satuan,
                        'nama_satuan' => $item->satuan->nama_satuan,
                    ] : null,
                    'kategori' => $item->kategori ? [
                        'id' => $item->kategori->id,
                        'nama_kategori' => $item->kategori->nama_kategori,
                    ] : null,
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Pencarian ditemukan',
                'search_term' => $search,
                'total_found' => $data->count(),
                'data' => $produk
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
                'message' => 'Gagal melakukan pencarian.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stock history for a specific product
     */
    #[PathParameter('productId', description: 'ID of the produk', required: true, example: 1)]
    public function stockHistory($productId)
    {
        try {
            $produk = Produk::with('riwayatProdukMasuk')->findOrFail($productId);
            $history = $produk->riwayatProdukMasuk()
                        ->latest()
                        ->get()
                        ->map(function ($item) {
                            return [
                                'id' => $item->id,
                                'stok' => $item->stok,
                                'distributor' => $item->distributor,
                                'tanggal_masuk' => $item->tanggal_masuk,
                            ];
                        });
            return response()->json([
                'status' => true,
                'message' => 'Riwayat stok produk berhasil diambil.',
                'data' => [
                    'produk_id' => $produk->id,
                    'kode_produk' => $produk->kode_produk,
                    'nama_produk' => $produk->nama_produk,
                    'history' => $history,
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil riwayat stok produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get stok history all products
     */
    public function allStockHistory()
    {
        try {
            $data = RiwayatProdukMasuk::with('produk', 'produk.satuan')->latest()->get();
            $history = $data->map(function ($item) {
                return [
                    'id' => $item->id,
                    'produk_id' => $item->produk->id,
                    'kode_produk' => $item->produk->kode_produk,
                    'nama_produk' => $item->produk->nama_produk,
                    'satuan' => $item->produk->satuan ? [
                        'id' => $item->produk->satuan->id,
                        'kode_satuan' => $item->produk->satuan->kode_satuan,
                        'nama_satuan' => $item->produk->satuan->nama_satuan,
                    ] : null,
                    'stok' => $item->stok,
                    'distributor' => $item->distributor,
                    'tanggal_masuk' => $item->tanggal_masuk,
                ];
            });
            return response()->json([
                'status' => true,
                'message' => 'Riwayat stok semua produk berhasil diambil.',
                'data' => $history,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil riwayat stok semua produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
