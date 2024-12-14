<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KinerjaDosenController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->input('year', date('Y')); // Tahun default adalah tahun saat ini
    
        // Mendapatkan data kegiatan internal
        $dataKegiatanInternal = DB::table('t_kegiatan_user')
            ->join('m_pengguna', 't_kegiatan_user.id_pengguna', '=', 'm_pengguna.id_pengguna')
            ->join('t_kegiatan', 't_kegiatan_user.id_kegiatan', '=', 't_kegiatan.id_kegiatan')
            ->where('t_kegiatan.periode', $year)
            ->selectRaw('m_pengguna.nama_pengguna as pengguna, COUNT(t_kegiatan_user.id_kegiatan_user) as total_kegiatan')
            ->groupBy('t_kegiatan_user.id_pengguna', 'm_pengguna.nama_pengguna')
            ->orderBy('total_kegiatan', 'desc')
            ->get();
    
        // Mendapatkan data kegiatan eksternal
        $dataKegiatanEksternal = DB::table('t_kegiatan_eksternal')
            ->join('m_pengguna', DB::raw('t_kegiatan_eksternal.pic COLLATE utf8mb4_unicode_ci'), '=', DB::raw('m_pengguna.nama_pengguna COLLATE utf8mb4_unicode_ci'))
            ->where('t_kegiatan_eksternal.periode', $year)
            ->select('m_pengguna.nama_pengguna as pengguna', DB::raw('COUNT(t_kegiatan_eksternal.id_kegiatan_eksternal) as total_kegiatan'))
            ->groupBy('t_kegiatan_eksternal.pic', 'm_pengguna.nama_pengguna')
            ->orderBy('total_kegiatan', 'desc')
            ->get();
    
        // Mengambil data periode dari t_kegiatan dan t_kegiatan_eksternal
        $internalPeriods = DB::table('t_kegiatan')->pluck('periode')->toArray();
        $externalPeriods = DB::table('t_kegiatan_eksternal')->pluck('periode')->toArray();
    
        // Menggabungkan dan menghilangkan duplikasi periode
        $availablePeriods = array_unique(array_merge($internalPeriods, $externalPeriods));
    
        // Mengurutkan periode
        sort($availablePeriods);
    
        // Menyiapkan data grafik
        $chartDataInternal = [
            'labels' => $dataKegiatanInternal->pluck('pengguna')->toArray(),
            'datasets' => [
                [
                    'label' => 'Jumlah Kegiatan Internal',
                    'data' => $dataKegiatanInternal->pluck('total_kegiatan')->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    
        $chartDataEksternal = [
            'labels' => $dataKegiatanEksternal->pluck('pengguna')->toArray(),
            'datasets' => [
                [
                    'label' => 'Jumlah Kegiatan Eksternal',
                    'data' => $dataKegiatanEksternal->pluck('total_kegiatan')->toArray(),
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    
        return view('kinerja_dosen.index', compact('chartDataInternal', 'chartDataEksternal', 'availablePeriods', 'year'));
    }
}
    