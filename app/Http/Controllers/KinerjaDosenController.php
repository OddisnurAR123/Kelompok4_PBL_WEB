<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class KinerjaDosenController extends Controller
{
    public function index()
    {
        // Mengambil jumlah kegiatan yang diikuti pengguna dari t_kegiatan_user
        $data = DB::table('t_kegiatan_user')
            ->join('m_pengguna', 't_kegiatan_user.id_pengguna', '=', 'm_pengguna.id_pengguna')
            ->selectRaw('m_pengguna.nama_pengguna as pengguna, COUNT(t_kegiatan_user.id_kegiatan_user) as total_kegiatan')
            ->groupBy('t_kegiatan_user.id_pengguna', 'm_pengguna.nama_pengguna')
            ->orderBy('total_kegiatan', 'desc')
            ->get();

        // Siapkan data untuk grafik
        $chartData = [
            'labels' => $data->pluck('pengguna'), // Nama pengguna
            'datasets' => [
                [
                    'label' => 'Jumlah Kegiatan',
                    'data' => $data->pluck('total_kegiatan'), // Total kegiatan
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];

        // Kirim data ke view
        return view('kinerja_dosen.index', compact('chartData'));
    }
}
