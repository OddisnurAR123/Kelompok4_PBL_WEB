<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel; // Model untuk m_pengguna
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

        return view('profil.profil', compact('user', 'breadcrumb'));
    }

    // Mengupdate profil pengguna
    public function updateProfil(Request $request)
    {
        $request->validate([
            'id_jenis_pengguna' => 'required|integer',
            'username' => 'required|string|max:255',
            'email' => 'required|email',
            'nama_pengguna' => 'required|string|max:255',
            'password' => 'nullable|min:6',
            'nip' => 'required|string|max:100',
            'foto_profil' => 'nullable|image|max:2048'
        ]);

        $user = PenggunaModel::find(Auth::id());

        if ($user) {
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->nama_pengguna = $request->input('nama_pengguna');

            if ($request->filled('password')) {
                $user->password = bcrypt($request->input('password'));
            }

            if ($request->hasFile('fotoprofil')) {
                $fotoprofil = $request->file('fotoprofil');
                $path = $fotoprofil->store('public/fotoprofil');
                $user->fotoprofil = $path;
            }

            $user->save();

            return redirect()->route('profil.profil')->with('success', 'Profil berhasil diperbarui.');
        }

        return redirect()->route('profil.profil')->withErrors('Terjadi kesalahan saat mengupdate profil.');
    }
    // Menampilkan halaman edit profil
    public function edit()
    {
        // Ambil data pengguna yang sedang login
        $user = PenggunaModel::where('id_pengguna', Auth::id())->first();

        $breadcrumb = (object) [
            'title' => 'Edit Profile',
            'list' => [
                (object) ['label' => 'Profile', 'url' => route('profil.edit')],
                'Edit'
            ]
        ];

        return view('profil.edit', compact('user', 'breadcrumb'));
    }
    // Menampilkan halaman ganti password
    public function changePassword()
    {
        $breadcrumb = (object) [
            'title' => 'Ganti Password',
            'list' => [
                (object) ['url' => route('profile.profil'), 'label' => 'Profile'],
                'Ganti Password'
            ]
        ];

        return view('profil.password', compact('breadcrumb'));
    }

    // Memperbarui password pengguna
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = PenggunaModel::where('id_pengguna', Auth::id())->first();

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('profil.profil')->with('success', 'Password berhasil diubah.');
    }
}
