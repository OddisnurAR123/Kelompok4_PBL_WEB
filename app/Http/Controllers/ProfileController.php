<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        // Handle upload foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil) {
                Storage::delete('public/' . $user->foto_profil);
            }

            // Simpan foto profil yang baru
            $fotoProfilPath = $request->file('foto_profil')->store('uploads/foto_profil', 'public');
            $user->foto_profil = $fotoProfilPath;
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);

        // Simpan perubahan
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}
