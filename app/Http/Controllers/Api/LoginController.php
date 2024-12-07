<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __invoke(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil kredensial dari request
        $credentials = $request->only('username', 'password');

        // Coba autentikasi menggunakan JWT
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau Password salah',
            ], 401);
        }

        // Login berhasil
        return response()->json([
            'success' => true,
            'user' => auth('api')->user(),
            'token' => $token,
        ], 200);
    }
}
