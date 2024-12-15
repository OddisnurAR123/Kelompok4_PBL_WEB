<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function showProfile()
    {
        $user = Auth::user();
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Dashboard', 'Profile']
        ];

        $activeMenu = 'profile';

        return view('profile.profil', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu, 'user' => $user]);
    }
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }
    public function update(Request $request, $id)
{
    $request->validate([
        'nama_pengguna' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'email' => 'nullable|email',
        'nip' => 'nullable|string|max:20',
        'foto_profil' => 'nullable|image|max:2048',
        'new_password' => 'nullable|string|min:6|confirmed',
    ]);

    $pengguna = PenggunaModel::findOrFail($id);

    // Mengupdate atribut pengguna
    $pengguna->nama_pengguna = $request->input('nama_pengguna');
    $pengguna->username = $request->input('username');
    $pengguna->email = $request->input('email');
    $pengguna->nip = $request->input('nip');

    // Mengubah password jika ada input password baru
    if ($request->filled('new_password')) {
        $pengguna->password = bcrypt($request->input('new_password'));
    }

    // Mengupdate foto profil jika diupload
    if ($request->hasFile('foto_profil')) {
        $pengguna->foto_profil = $request->file('foto_profil')->store('profile_pictures', 'public');
    }

    $pengguna->save();

    return response()->json([
        'message' => 'Profil pengguna berhasil diperbarui',
        'data' => $pengguna
    ]);
}
}    