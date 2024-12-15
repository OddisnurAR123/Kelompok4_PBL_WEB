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
        $user = auth()->user();
    
        return view('profile.show', compact('user'));
    }
    public function editPassword()
    {
        $user = auth()->user();
        return view('profile.edit-password');
    }

    public function updatePassword(Request $request)
{
    // Validasi input password
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|min:6|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Password lama tidak sesuai']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return redirect()->route('profile.show')->with('success', 'Password berhasil diperbarui');
}

    
    public function editPhoto()
{
    // Ambil data pengguna yang sedang login
    $user = auth()->user();

    return view('profile.edit_photo', compact('user'));
}

    public function updatePhoto(Request $request)
    {
        // Validasi input foto
        $request->validate([
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = auth()->user();
    
        // Periksa apakah file diunggah
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($user->foto_profil && Storage::exists('public/profile/' . $user->foto_profil)) {
                Storage::delete('public/profile/' . $user->foto_profil);
            }
    
            // Simpan foto baru
            $fileName = time() . '.' . $request->foto_profil->extension();
            $path = $request->foto_profil->storeAs('public/profile', $fileName);
    
            // Perbarui atribut foto profil di database
            $user->foto_profil = $fileName;
            $user->save();
    
            return redirect()->route('profile.show')->with('success', 'Foto profil berhasil diperbarui.');
        }
    
        return redirect()->route('profile.show')->with('info', 'Tidak ada foto yang diunggah.');
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'email' => 'required|email',
        ]);
    
        $user = Auth::user();
        $user->nama_pengguna = $request->nama;
        $user->email = $request->email;
        $user->save();
    
        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui');
    }
}    
    // public function update(Request $request)
    // {
    //     $user = Auth::user();
    
    //     // Validasi input
    //     $request->validate([
    //         'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    //         'current_password' => 'nullable',
    //         'new_password' => 'nullable|string|min:6|confirmed',
    //     ]);
    
    //     // Cek apakah password lama benar
    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return back()->withErrors(['current_password' => 'Password lama salah']);
    //     }
    //     // Handle upload foto profil jika ada
    //     if ($request->hasFile('foto_profil')) {
    //         // Hapus foto lama jika ada
    //         if ($user->foto_profil) {
    //             Storage::delete('public/' . $user->foto_profil);
    //         }
    
    //         // Simpan foto profil yang baru
    //         $fotoProfilPath = $request->file('foto_profil')->store('foto_profil', 'public');
    
    //         // Update foto profil di database
    //         DB::table('m_pengguna')
    //             ->where('id_pengguna', $user->id_pengguna)
    //             ->update(['foto_profil' => $fotoProfilPath]);
    //     }
    
    //     // Update password baru jika diperlukan
    //     if ($request->new_password) {
    //         DB::table('m_pengguna')
    //             ->where('id_pengguna', $user->id_pengguna)
    //             ->update(['password' => Hash::make($request->new_password)]);
    //     }
    
    //     return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    // }
    

