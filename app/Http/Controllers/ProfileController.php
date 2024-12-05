<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini jika belum ada
use App\Models\PenggunaModel; // Model yang Anda gunakan
use Hash;


class ProfileController extends Controller
{
    public function index()
    {
        // Ambil data pengguna yang sedang login
        $user = PenggunaModel::find(Auth::id());

        // Definisikan variabel $activeMenu agar sidebar aktif
        $activeMenu = 'pengaturan-profil';
        
        // Kirim data pengguna ke view
        return view('profile.index', compact('user', 'activeMenu'));
    }
        // Menampilkan form untuk mengedit profil
        public function edit()
        {
            $user = Auth::user();
            return view('update_profile.index', compact('user'));
        }

    public function update(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nama_pengguna' => 'required|string|max:255',
            'password' => 'nullable|confirmed|min:8',
            'fotoprofil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Ambil data pengguna yang sedang login
        $user = Auth::user();

        // Update foto profil jika ada file yang di-upload
        if ($request->hasFile('fotoprofil')) {
            $path = $request->file('fotoprofil')->store('public/profiles');
            $user->fotoprofil = $path;
        }

        // Simpan perubahan
        if ($request->hasFile('fotoprofil')) {
    // Menghapus foto lama jika ada
    if ($user->fotoprofil && file_exists(public_path($user->fotoprofil))) {
        unlink(public_path($user->fotoprofil));
    }

    // Simpan file foto baru
    $path = $request->file('fotoprofil')->store('profiles', 'public');
    $user->fotoprofil = 'storage/' . $path;
}

        // Redirect kembali dengan pesan sukses
        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui');
    }
}