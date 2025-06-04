<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Login user dengan email dan password.
     */
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = Auth::guard('api')->attempt($credentials)) {
                return response()->json([
                    'status_code' => 401,
                    'message' => 'Email atau password salah.',
                ], 401);
            }

            $user = Auth::guard('api')->user();

            return response()->json([
                'status_code' => 200,
                'message' => 'Login berhasil.',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'is_admin' => $user->is_admin ?? false,
                    ],
                    'token' => $token,
                ],
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Gagal membuat token.',
                'error' => $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Terjadi kesalahan.',
                'error' => $e->getMessage(), // untuk debugging
            ], 500);
        }
    }

    /**
     * Logout user dari sistem.
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('api')->logout();
            return response()->json([
                'message' => 'Logout berhasil.',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Gagal logout.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
