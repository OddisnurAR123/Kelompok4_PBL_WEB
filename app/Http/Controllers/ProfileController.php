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

    

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'nama_pengguna' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:50|unique:m_pengguna,username,',
            'email' => 'nullable|email|max:255|unique:m_pengguna,email,',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'password' => 'nullable|confirmed|min:6',
        ]);

        // Persiapkan data untuk diupdate
        $dataToUpdate = [
            'nama_pengguna' => $validated['nama_pengguna'],
            'username' => $validated['username'],
            'email' => $validated['email'],
        ];

        // Update foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::delete('public/' . $user->foto_profil);
            }

            // Simpan foto baru
            $path = $request->file('foto_profil')->store('public/profiles');
            $dataToUpdate['foto_profil'] = basename($path);
        }

        // Update password jika diisi
        if ($request->filled('password')) {
            $dataToUpdate['password'] = Hash::make($validated['password']);
        }

        DB::table('m_pengguna')
            ->where('id_pengguna', $user->id_pengguna)
            ->update($dataToUpdate);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
