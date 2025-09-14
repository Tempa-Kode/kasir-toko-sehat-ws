<?php

namespace App\Http\Controllers\Api;

use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Dedoc\Scramble\Attributes\BodyParameter;
use App\Http\Controllers\Controller;

class SatuanController extends Controller
{
    /**
     * Get all satuan data.
     */
    public function index()
    {
        $data = Satuan::all();
        if ($data->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => 'No satuan data found.'
            ], 404);
        }
        return response()->json([
            'status' => true,
            'message' => 'Satuan data retrieved successfully.',
            'data' => $data
        ], 200);
    }

    /**
     * Store a new satuan data.
     */
    #[BodyParameter('kode_satuan', description: 'Kode satuan data', required: true, example: 'KTK')]
    #[BodyParameter('nama_satuan', description: 'Satuan data', required: true, example: 'Kotak')]
    #[BodyParameter('deskripsi', description: 'Deskripsi satuan data', required: false)]
    public function store(Request $request)
    {
        Log::info('Request data:', $request->all());
        try{
            $validated = $request->validate([
                'kode_satuan' => 'required|string|unique:tb_satuan,kode_satuan',
                'nama_satuan' => 'required|string',
                'deskripsi' => 'nullable|string'
            ]);

            $satuan = Satuan::create($validated);

            return response()->json([
                'status' => true,
                'message' => 'Satuan created successfully.',
                'data' => $satuan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create satuan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific satuan data.
     */
    public function show($satuanId)
    {
        try{
            $satuan = Satuan::find($satuanId);
            if (!$satuan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Satuan not found.'
                ], 404);
            }
            return response()->json([
                'status' => true,
                'message' => 'Satuan data retrieved successfully.',
                'data' => $satuan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve satuan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update a specific satuan data.
     */
    #[BodyParameter('kode_satuan', description: 'Kode satuan data', required: true, example: 'KOT')]
    #[BodyParameter('nama_satuan', description: 'Satuan data', required: true, example: 'Kotak')]
    #[BodyParameter('deskripsi', description: 'Deskripsi satuan data', required: false)]
    public function update(Request $request, $satuanId)
    {
        $satuan = Satuan::find($satuanId);
        if (!$satuan) {
            return response()->json([
                'status' => false,
                'message' => 'Satuan not found.'
            ], 404);
        }

        try{
            $validated = $request->validate([
                'kode_satuan' => 'sometimes|required|string|unique:tb_satuan,kode_satuan,'.$satuanId,
                'nama_satuan' => 'sometimes|required|string',
                'deskripsi' => 'nullable|string'
            ]);

            $satuan->update($validated);

            return response()->json([
                'status' => true,
                'message' => 'Satuan updated successfully.',
                'data' => $satuan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to update satuan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a specific satuan data.
     */
    public function destroy($satuanId)
    {
        try{
            $satuan = Satuan::find($satuanId);
            if (!$satuan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Satuan not found.'
                ], 404);
            }

            $satuan->delete();

            return response()->json([
                'status' => true,
                'message' => 'Satuan deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to delete satuan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
