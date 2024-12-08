<?php

namespace App\Http\Controllers;

use App\Models\PenggunaModel;
use Illuminate\Http\Request;

class KinerjaDosenController extends Controller
{
    // Menampilkan statistik kinerja dosen
    public function index()
    {
        // Ambil data dosen beserta jumlah kegiatan yang diikuti
        $dosenKegiatan = PenggunaModel::withCount('kegiatan') // Menghitung jumlah kegiatan yang diikuti
            ->whereHas('jenisPengguna', function ($query) {
                $query->where('kode_jenis_pengguna', 'dosen'); // Pastikan hanya dosen yang diambil
            })
            ->orderBy('kegiatan_count', 'desc') // Mengurutkan berdasarkan jumlah kegiatan terbanyak
            ->get();

        // Kirim data ke view
        return view('kinerja_dosen.index', compact('dosenKegiatan'));
    }
}
