<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        try{
            if (auth()->attempt($credentials)) {
                $user = auth()->user();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Login successful',
                    'data' => $user,
                    'token' => $user->createToken('api-token')->plainTextToken
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }
}
