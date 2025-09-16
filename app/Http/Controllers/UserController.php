<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Get all users
     */
    public function index()
    {
        try {
            $users = User::select('id', 'nama', 'username', 'hak_akses', 'created_at')
                         ->orderBy('created_at', 'desc')
                         ->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data user.'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil diambil.',
                'data' => $users
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified user
     */
    public function show($id)
    {
        try {
            $user = User::select('id', 'nama', 'username', 'hak_akses', 'created_at', 'updated_at')
                        ->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil diambil.',
                'data' => $user
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil data user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'nama' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:tb_pengguna,username,' . $id,
                'hak_akses' => 'required|in:admin,kasir,pemilik'
            ]);

            // Update data user
            $updateData = [
                'nama' => $validated['nama'],
                'username' => $validated['username'],
                'hak_akses' => $validated['hak_akses']
            ];

            $user->update($updateData);

            return response()->json([
                'status' => true,
                'message' => 'User berhasil diperbarui.',
                'data' => [
                    'id' => $user->id,
                    'nama' => $user->nama,
                    'username' => $user->username,
                    'hak_akses' => $user->hak_akses,
                    'updated_at' => $user->updated_at
                ]
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan.'
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
                'message' => 'Gagal memperbarui user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified user from storage
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            $userName = $user->nama;
            $user->delete();

            return response()->json([
                'status' => true,
                'message' => "User {$userName} berhasil dihapus."
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan.'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus user.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search users by name or username
     */
    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'search' => 'required|string|min:1',
                'limit' => 'nullable|integer|min:1|max:100'
            ]);

            $search = $validated['search'];
            $limit = $validated['limit'] ?? 10;

            $users = User::select('id', 'nama', 'username', 'created_at')
                         ->where(function ($q) use ($search) {
                             $q->where('nama', 'LIKE', '%' . $search . '%')
                               ->orWhere('username', 'LIKE', '%' . $search . '%');
                         })
                         ->limit($limit)
                         ->get();

            if ($users->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada user yang ditemukan dengan kata kunci: ' . $search
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Pencarian berhasil.',
                'search_term' => $search,
                'total_found' => $users->count(),
                'data' => $users
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
     * Change user password
     */
    public function changePassword(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'current_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            // Verify current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password lama tidak sesuai.'
                ], 400);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['new_password'])
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Password berhasil diubah.'
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan.'
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
                'message' => 'Gagal mengubah password.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
