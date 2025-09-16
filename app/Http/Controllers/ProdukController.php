<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Dedoc\Scramble\Attributes\BodyParameter;
use Dedoc\Scramble\Attributes\PathParameter;
use Illuminate\Validation\ValidationException;

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
                'harga' => 'required|numeric|min:0',
                'stok' => 'required|integer|min:0',
            ]);

            $produk = Produk::create($validated);
            $produk->load('satuan', 'kategori');

            return response()->json([
                'status' => true,
                'message' => 'Produk berhasil dibuat.',
                'data' => [
                    'id' => $produk->id,
                    'kode_produk' => $produk->kode_produk,
                    'nama_produk' => $produk->nama_produk,
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
    #[BodyParameter('operation', required: false, type: 'string', example: 'add', description: 'Operation type: add (default) or subtract')]
    public function updateStock(Request $request, $id)
    {
        try {
            $produk = Produk::findOrFail($id);

            $validated = $request->validate([
                'quantity' => 'required|integer|min:1',
                'operation' => 'nullable|string|in:add,subtract'
            ]);

            $quantity = $validated['quantity'];
            $operation = $validated['operation'] ?? 'add';

            // Calculate new stock
            if ($operation === 'add') {
                $newStock = $produk->stok + $quantity;
            } else {
                $newStock = $produk->stok - $quantity;

                // Prevent negative stock
                if ($newStock < 0) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Stok tidak mencukupi. Stok saat ini: ' . $produk->stok
                    ], 400);
                }
            }

            // Update stock
            $produk->update(['stok' => $newStock]);
            $produk->load('satuan', 'kategori');

            return response()->json([
                'status' => true,
                'message' => $operation === 'add'
                    ? "Stok berhasil ditambahkan sebanyak {$quantity}. Stok sekarang: {$newStock}"
                    : "Stok berhasil dikurangi sebanyak {$quantity}. Stok sekarang: {$newStock}",
                'data' => [
                    'id' => $produk->id,
                    'kode_produk' => $produk->kode_produk,
                    'nama_produk' => $produk->nama_produk,
                    'harga' => $produk->harga,
                    'stok_sebelumnya' => $produk->stok - ($operation === 'add' ? $quantity : -$quantity),
                    'stok_sekarang' => $produk->stok,
                    'perubahan' => $operation === 'add' ? "+{$quantity}" : "-{$quantity}",
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
}
