<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\KategoriProduk;
use App\Http\Controllers\Controller;
use Dedoc\Scramble\Attributes\BodyParameter;

class KategoriProdukController extends Controller
{
    /**
     * Get all kategori produk.
     */
    public function index()
    {
        $data = KategoriProduk::all();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No kategori produk data found.'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Kategori produk data retrieved successfully.',
            'data' => $data
        ], 200);
    }

    /**
     * Store a new kategori produk.
     */
    #[BodyParameter('nama_kategori', description: 'Nama kategori produk', required: true, example: 'Makanan')]
    #[BodyParameter('deskripsi', description: 'Deskripsi kategori produk', required: false, example: 'Kategori untuk makanan')]
    public function store(Request $request)
    {
        try{
            $validated = $request->validate([
                'nama_kategori' => 'required|string',
                'deskripsi' => 'nullable|string'
            ]);

            $kategori = KategoriProduk::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Kategori produk created successfully.',
                'data' => $kategori
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create kategori produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific kategori produk.
     */
    public function show($kategoriId)
    {
        try{
            $kategori = KategoriProduk::find($kategoriId);
            if (!$kategori) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori produk not found.'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Kategori produk retrieved successfully.',
                'data' => $kategori
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve kategori produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a specific kategoriproduk.
     */
    #[BodyParameter('nama_kategori', description: 'Nama kategori produk', required: true, example: 'Makanan Update')]
    #[BodyParameter('deskripsi', description: 'Deskripsi kategori produk', required: false, example: 'Kategori untuk makanan')]
    public function update(Request $request, $kategoriId)
    {
        $kategori = KategoriProduk::find($kategoriId);
        if (!$kategori) {
            return response()->json([
                'status' => false,
                'message' => 'Kategori produk not found.'
            ], 404);
        }

        try{
            $validated = $request->validate([
                'nama_kategori' => 'required|string',
                'deskripsi' => 'nullable|string'
            ]);

            $kategori->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Kategori produk updated successfully.',
                'data' => $kategori
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update kategori produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific kategori produk.
     */
    public function destroy($kategoriId)
    {
        try{
            $kategori = KategoriProduk::find($kategoriId);
            if (!$kategori) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kategori produk not found.'
                ], 404);
            }

            $kategori->delete();

            return response()->json([
                'status' => true,
                'message' => 'Kategori produk deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete kategori produk.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
