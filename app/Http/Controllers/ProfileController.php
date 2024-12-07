<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel; // Model untuk m_pengguna
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function showProfile()
    {
        // Ambil data pengguna yang sedang login berdasarkan ID
        $user = PenggunaModel::where('id_pengguna', Auth::id())->first();

        if (!$user) {
            return redirect()->route('login')->withErrors('Anda harus login terlebih dahulu.');
        }

        // Membuat breadcrumb untuk halaman profil
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => [
                'Home',
                (object) ['url' => route('profile.profil'), 'label' => 'Profile'],
                'Profil'
            ]
        ];

        return view('profile.profil', compact('user', 'breadcrumb'));
    }

    // Menampilkan halaman edit profil
    public function edit()
    {
        // Ambil data pengguna yang sedang login
        $user = PenggunaModel::where('id_pengguna', Auth::id())->first();

        $breadcrumb = (object) [
            'title' => 'Edit Profile',
            'list' => [
                (object) ['label' => 'Profile', 'url' => route('profile.edit')],
                'Edit'
            ]
        ];

        return view('profile.edit', compact('user', 'breadcrumb'));
    }

    // Memperbarui data profil
    public function update(Request $request)
    {
        $user = PenggunaModel::where('id_pengguna', Auth::id())->first();

        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:m_pengguna,username,' . $user->id_pengguna . ',id_pengguna',
            'email' => 'required|email|max:255',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
        ]);

        // Update nama, username, dan email
        $user->nama_pengguna = $request->nama_pengguna;
        $user->username = $request->username;
        $user->email = $request->email;

        // Handle upload foto profil
        if ($request->hasFile('foto_profil')) {
            $fotoPath = $request->file('foto_profil')->store('uploads/foto_profil', 'public');
            $user->foto_profil = $fotoPath;
        }

        $user->save();

        return redirect()->route('profile.profil')->with('success', 'Profil berhasil diperbarui!');
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

        return view('profile.password', compact('breadcrumb'));
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

        return redirect()->route('profile.profil')->with('success', 'Password berhasil diubah.');
    }
}
