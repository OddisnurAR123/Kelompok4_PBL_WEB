<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ambil data dosen yang memiliki kegiatan di internal maupun eksternal
        $dosen = DB::table('m_pengguna')
            ->leftJoin('t_kegiatan_user', 'm_pengguna.id_pengguna', '=', 't_kegiatan_user.id_pengguna')
            ->leftJoin('t_kegiatan_eksternal', 'm_pengguna.id_pengguna', '=', 't_kegiatan_eksternal.id_pengguna')
            ->select('m_pengguna.id_pengguna', 'm_pengguna.nama_pengguna')
            ->groupBy('m_pengguna.id_pengguna', 'm_pengguna.nama_pengguna')
            ->get();

        return view('admin.index', compact('dosen'));
    }

    public function listKegiatan($idPengguna)
    {
        // Ambil daftar kegiatan dari internal dan eksternal
        $kegiatanInternal = DB::table('t_kegiatan_user')
            ->join('t_kegiatan', 't_kegiatan_user.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->where('t_kegiatan_user.id_pengguna', $idPengguna)
            ->select('t_kegiatan.nama_kegiatan', 't_kegiatan.tanggal_selesai')
            ->get();

        $kegiatanEksternal = DB::table('t_kegiatan_eksternal')
            ->where('id_pengguna', $idPengguna)
            ->select('nama_kegiatan', 'waktu_kegiatan as tanggal_selesai')
            ->get();

        $kegiatan = $kegiatanInternal->merge($kegiatanEksternal);

        return view('admin.kegiatan-list', compact('kegiatan'));
    }
}
