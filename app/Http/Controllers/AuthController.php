<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return redirect('/'); // Jika sudah login, redirect ke halaman utama
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        try {
            // Validasi input
            $request->validate([
                'username' => 'required|min:4|max:20',
                'password' => 'required|min:5|max:20',
            ]);

            // Mencari user berdasarkan username
            $user = PenggunaModel::where('username', $request->username)->first();

            // Cek apakah user ditemukan dan password valid
            if ($user && Hash::check($request->password, $user->password)) {
                Auth::login($user);  // Login pengguna
                $request->session()->regenerate();  // Regenerasi session

                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url('/')  // Redirect ke halaman utama
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Login Gagal. Username atau Password salah.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Logout user.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Logout user

        // Invalidasi session dan generate ulang token CSRF
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman login
        return redirect('login');
    }
    
}
