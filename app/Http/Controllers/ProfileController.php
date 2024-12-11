<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PenggunaModel;  // Pastikan model PenggunaModel sudah benar
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function showProfile()
    {
        $user = Auth::user();  // Mendapatkan data pengguna yang sedang login

        // Membuat breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Dashboard', 'Profile']
        ];

        $activeMenu = 'profile';

        // Mengirim data ke view untuk ditampilkan
        return view('profile.profil', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }

    // Menampilkan halaman edit profil
    public function edit()
    {
        $user = Auth::user();  // Mendapatkan data pengguna yang sedang login

        // Membuat breadcrumb untuk navigasi
        $breadcrumb = (object) [
            'title' => 'Edit Profile',
            'list' => [
                (object) ['label' => 'Profile', 'url' => route('profile.edit')],
                'Edit'
            ]
        ];

        // Mengirim data ke view untuk halaman edit profil
        return view('profile.edit', compact('user', 'breadcrumb'));
    }

    // Memperbarui data profil
    public function update(Request $request)
    {
        $user = Auth::user();  // Mendapatkan data pengguna yang sedang login

        // Validasi inputan
        $request->validate([
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:m_user,username,' . $user->user_id . ',user_id', // Sesuaikan dengan tabel dan kolom
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048', // Pastikan format gambar sesuai
        ]);

        // Update nama dan username pengguna
        $user->nama = $request->nama;
        $user->username = $request->username;

        // Handle upload avatar
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('uploads/avatars', 'public');
            $user->avatar = $avatarPath;
        }

        // Simpan perubahan
        $user->save();

        // Redirect dengan pesan sukses
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

    // Memperbarui password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();  // Mendapatkan data pengguna yang sedang login

        // Log untuk debug (hanya untuk tujuan debugging)
        \Log::info('User ID: ' . $user->id);
        \Log::info('Current Password: ' . $request->current_password);
        
        // Cek apakah password lama benar
        if (!Hash::check($request->current_password, $user->password)) {
            \Log::info('Old password does not match');
            return back()->withErrors(['current_password' => 'Password lama salah']);
        }

        // Update password baru
        $user->password = Hash::make($request->new_password);
        $user->save();

        // Log untuk debug
        \Log::info('Password updated successfully for User ID: ' . $user->id);

        // Redirect dengan pesan sukses
        return redirect()->route('profile.profil')->with('success', 'Password berhasil diubah.');
    }
}
