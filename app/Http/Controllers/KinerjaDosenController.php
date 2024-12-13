<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class KinerjaDosenController extends Controller
{
    public function index()
    {
        // Mengambil jumlah kegiatan yang diikuti pengguna dari t_kegiatan_user
        $dataKegiatanInternal = DB::table('t_kegiatan_user')
            ->join('m_pengguna', 't_kegiatan_user.id_pengguna', '=', 'm_pengguna.id_pengguna')
            ->selectRaw('m_pengguna.nama_pengguna as pengguna, COUNT(t_kegiatan_user.id_kegiatan_user) as total_kegiatan')
            ->groupBy('t_kegiatan_user.id_pengguna', 'm_pengguna.nama_pengguna')
            ->orderBy('total_kegiatan', 'desc')
            ->get();

        $dataKegiatanEksternal = DB::table('t_kegiatan_eksternal')
            ->join('m_pengguna', DB::raw('t_kegiatan_eksternal.pic COLLATE utf8mb4_unicode_ci'), '=', DB::raw('m_pengguna.nama_pengguna COLLATE utf8mb4_unicode_ci'))
            ->select('m_pengguna.nama_pengguna as pengguna', DB::raw('COUNT(t_kegiatan_eksternal.id_kegiatan_eksternal) as total_kegiatan'))
            ->groupBy('t_kegiatan_eksternal.pic', 'm_pengguna.nama_pengguna')
            ->orderBy('total_kegiatan', 'desc')
            ->get();
        
        // Siapkan data untuk grafik kegiatan internal
        $chartDataInternal = [
            'labels' => $dataKegiatanInternal->pluck('pengguna')->toArray(), // Nama pengguna
            'datasets' => [
                [
                    'label' => 'Jumlah Kegiatan Internal',
                    'data' => $dataKegiatanInternal->pluck('total_kegiatan')->toArray(), // Total kegiatan
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        // Siapkan data untuk grafik kegiatan eksternal
        $chartDataEksternal = [
            'labels' => $dataKegiatanEksternal->pluck('pengguna')->toArray(), // Nama pengguna
            'datasets' => [
                [
                    'label' => 'Jumlah Kegiatan Eksternal',
                    'data' => $dataKegiatanEksternal->pluck('total_kegiatan')->toArray(), // Total kegiatan
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        // Kirim data ke view
        return view('kinerja_dosen.index', compact('chartDataInternal', 'chartDataEksternal'));
    }
}
