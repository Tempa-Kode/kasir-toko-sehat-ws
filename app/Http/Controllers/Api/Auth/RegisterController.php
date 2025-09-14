<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    /**
     * Register a new user.
     *
     * @param Request $request
     */
    public function __invoke(Request $request)
    {
        try{
            $data = $request->validate([
                'nama' => 'required|string',
                'username' => 'required|unique:tb_pengguna,username',
                'password' => 'required|string',
                'hak_akses' => 'required|in:admin,kasir,pemilik'
            ]);

            if(Auth::user()->hak_akses != 'admin'){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only admin can register new user'
                ], 403);
            }

            $savedData = User::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Registration successful',
                'data' => $savedData
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
