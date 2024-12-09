<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    // Menampilkan halaman profil
    public function showProfil()
    {
        $user = PenggunaModel::where('id_pengguna', Auth::id())->first();
    
        if (!$user) {
            return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
        }
    
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => [
                'Home',
                (object) ['url' => route('profil.profil'), 'label' => 'Profil'],
                'Profil'
            ]
        ];
    
        // Pastikan $breadcrumb dikirim ke view
        return view('profil.profil', compact('user', 'breadcrumb'));
    }
    // Mengupdate profil pengguna
    public function updateProfil(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'nama_pengguna' => 'required|string|max:255',
            'nip' => 'required|string|max:100',
            'password' => 'nullable|min:6',
            'foto_profil' => 'nullable|image|max:2048'
        ]);

        $user = PenggunaModel::find(Auth::id());

        if ($user) {
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->nama_pengguna = $request->input('nama_pengguna');
            $user->nip = $request->input('nip');

            if ($request->filled('password')) {
                $user->password = Hash::make($request->input('password'));
            }

            if ($request->hasFile('foto_profil')) {
                $foto_profil = $request->file('foto_profil');
                $path = $foto_profil->store('public/fotoprofil');
                $user->foto_profil = $path;
            }

            $user->save();

            return redirect()->route('profil.profil')->with('success', 'Profil berhasil diperbarui.');
        }

        return redirect()->route('profil.profil')->withErrors('Terjadi kesalahan saat mengupdate profil.');
    }

    // Menampilkan halaman edit profil
    public function edit()
    {
        $user = PenggunaModel::find(Auth::id());

        if (!$user) {
            return redirect()->route('login')->withErrors('Pengguna tidak ditemukan.');
        }

        return view('profil.edit', compact('user'));
    }

    // Mengubah password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = PenggunaModel::find(Auth::id());

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profil.profil')->with('success', 'Password berhasil diubah.');
    }
}
