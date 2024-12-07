<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel; // Pastikan model User sesuai dengan nama model Anda
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // Jika sudah login, redirect ke halaman home
            return redirect('/');
        }
        return view('auth.login');
    }

    /**
     * Proses login user.
     */
    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');
            
            // Coba melakukan login dengan credentials yang diberikan
            if (Auth::attempt($credentials)) {
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')
                ]);
            }

            // Jika login gagal
            return response()->json([
                'status' => false,
                'message' => 'Login Gagal'
            ]);
        }

        return redirect('login');
    }
}
