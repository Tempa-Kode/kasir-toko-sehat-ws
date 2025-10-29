<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Dedoc\Scramble\Attributes\BodyParameter;

class LoginController extends Controller
{
    /**
     * Login a user.
     *
     * @param Request $request
     * @return Response.
     *
     * @unauthenticated
     */
    #[BodyParameter(
        name : 'username', type:  'string',
        description: 'Username of the user', required: true,
        example: 'admin'
    )]
    #[BodyParameter(
        name : 'password', type:  'string',
        description: 'Password of the user', required: true,
        example: 'admin123'
    )]
    public function __invoke(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        try {
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                return response()->json([
                    'status' => true,
                    'message' => 'Login berhasil',
                    'data' => $user,
                    'token' => $user->createToken('api-token')->plainTextToken
                ], 200);
            }

            // Jika kredensial salah
            return response()->json([
                'status' => false,
                'message' => 'Username atau password salah',
            ], 401);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat login',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
