<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request
     */
    public function __invoke(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'nama' => 'required',
            'password' => 'required|string|min:5|confirmed',
            'level_id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Jika validasi gagal
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Buat user baru
        $image = request()->image;
        $user = User::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'level_id' => $request->level_id,
            'image' => $image -> hashName(),
        ]);

        // Jika user berhasil dibuat
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil didaftarkan',
                'user' => $user,
            ], 201);
        }

        // Jika pembuatan user gagal
        return response()->json([
            'success' => false,
            'message' => 'Gagal mendaftarkan user',
        ], 409);
    }
}
