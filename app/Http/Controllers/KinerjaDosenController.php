<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KinerjaDosenController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
            // Ambil data kegiatan internal berdasarkan tahun
            $dataInternal = DB::table('t_kegiatan_user')
                ->join('t_kegiatan', 't_kegiatan_user.id_kegiatan', '=', 't_kegiatan.id_kegiatan') // Join ke tabel t_kegiatan
                ->join('m_pengguna', 't_kegiatan_user.id_pengguna', '=', 'm_pengguna.id_pengguna') // Join ke tabel m_pengguna
                ->selectRaw('m_pengguna.nama_pengguna as pengguna, COUNT(t_kegiatan_user.id_kegiatan_user) as total_kegiatan')
                ->whereYear('t_kegiatan.tanggal_selesai', $tahun) // Filter berdasarkan tahun
                ->groupBy('t_kegiatan_user.id_pengguna', 'm_pengguna.nama_pengguna')
                ->orderBy('total_kegiatan', 'desc')
                ->get();

            // Ambil data kegiatan eksternal berdasarkan tahun
            $dataEksternal = DB::table('t_kegiatan_eksternal')
                ->join('m_pengguna', 't_kegiatan_eksternal.id_pengguna', '=', 'm_pengguna.id_pengguna')
                ->selectRaw('m_pengguna.nama_pengguna as pengguna, COUNT(t_kegiatan_eksternal.id_kegiatan_eksternal) as total_kegiatan')
                ->whereYear('t_kegiatan_eksternal.waktu_kegiatan', $tahun) // Filter berdasarkan tahun dari kolom periode
                ->groupBy('t_kegiatan_eksternal.id_pengguna', 'm_pengguna.nama_pengguna')
                ->orderBy('total_kegiatan', 'desc')
                ->get();

        // Mengambil data untuk grafik
        $chartLabelsInternal = $dataInternal->pluck('pengguna')->toArray();
        $chartDataInternal = $dataInternal->pluck('total_kegiatan')->toArray();
    
        $chartLabelsEksternal = $dataEksternal->pluck('pengguna')->toArray();
        $chartDataEksternal = $dataEksternal->pluck('total_kegiatan')->toArray();

        // dd($dataInternal, $dataEksternal);
    
        return view('kinerja_dosen.index', compact('dataInternal', 'dataEksternal', 'chartLabelsInternal', 'chartDataInternal', 'chartLabelsEksternal', 'chartDataEksternal', 'tahun'));
    }    
}
