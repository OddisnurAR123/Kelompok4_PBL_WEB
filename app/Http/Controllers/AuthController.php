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
            // Ambil jenis pengguna dari pengguna yang login
            $userType = Auth::user()->id_jenis_pengguna;
    
            // Redirect berdasarkan jenis pengguna
            if (in_array($userType, [1, 2])) {
                return redirect('/kegiatan_pimpinan/kegiatan_pimpinan');
            }
    
            // Default redirect untuk pengguna lainnya
            return redirect('/dashboard');
        }
    
        // Jika belum login, tampilkan halaman login
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
                    'redirect' => url('/dashboard')  // Redirect ke halaman utama
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
