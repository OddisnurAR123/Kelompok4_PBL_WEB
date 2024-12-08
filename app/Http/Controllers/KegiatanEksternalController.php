<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KegiatanEksternalModel;
use Yajra\DataTables\DataTables;

class KegiatanEksternalController extends Controller
{
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Kegiatan Non-JTI',
            'list' => ['Home', 'Kegiatan Non-JTI']
        ];

        $page = (object) [
            'title' => 'Daftar kegiatan yang ada'
        ];

        $activeMenu = 'kegiatan_eksternal';

        return view('kegiatan_eksternal.index', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function list(Request $request) {
        // Mengambil data kegiatan eksternal dengan nama kolom yang benar
        $kegiatanEksternal = KegiatanEksternalModel::select('id_kegiatan_eksternal', 'nama_kegiatan', 'waktu_kegiatan');

        // Menampilkan data dalam bentuk DataTables tanpa kolom aksi
        return DataTables::of($kegiatanEksternal)
            ->make(true); // Mengaktifkan DataTables
    }     

    // Menampilkan form tambah kegiatan eksternal
    public function create() {
        $breadcrumb = [
            'title' => 'Input Kegiatan Eksternal Non-JTI',
            'list' => ['Dashboard', 'Kegiatan', 'Kegiatan Non-JTI']
        ];        
        return view('kegiatan_eksternal.create', ['title' => $breadcrumb['title']]); // Tampilkan halaman form
    }

    // Menyimpan data kegiatan eksternal ke dalam tabel t_kegiatan_eksternal
    public function store(Request $request) {
        // Validasi input
        $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'waktu_kegiatan' => 'required|date',
        ]);
    
        // Mendapatkan nama pengguna yang sedang login
        $user = auth()->user(); // Pastikan Anda sudah melakukan autentikasi
        $nama_pic = $user ? $user->nama_pengguna : 'Unknown';
    
        // Menyimpan data ke database
        KegiatanEksternalModel::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'waktu_kegiatan' => $request->waktu_kegiatan,
            'pic' => $nama_pic, // Nama PIC diambil dari nama pengguna yang sedang login
            'id_kategori_kegiatan' => 3, // Kategori Kegiatan dengan ID 3
        ]);
    
        return redirect()->route('kegiatan_eksternal.create')->with('success', 'Kegiatan eksternal berhasil ditambahkan!');
    }
    
}
