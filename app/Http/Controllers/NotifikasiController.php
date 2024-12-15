<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\KegiatanModel;
use App\Models\AgendaModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id_pengguna; // Mendapatkan ID pengguna yang login

        // Ambil data kegiatan baru (7 hari terakhir)
        $newKegiatanCount = KegiatanModel::where('id_pengguna', $userId)
            ->where('tanggal_mulai', '>=', Carbon::now()->subDays(7))
            ->count();

        // Ambil data kegiatan terdekat
        $upcomingKegiatan = KegiatanModel::where('id_pengguna', $userId)
            ->where('tanggal_mulai', '>=', Carbon::now())
            ->orderBy('tanggal_mulai', 'asc')
            ->first();

        // Ambil data agenda terdekat
        $upcomingAgenda = AgendaModel::where('id_pengguna', $userId)
            ->where('tanggal_agenda', '>=', Carbon::now())
            ->orderBy('tanggal_agenda', 'asc')
            ->first();

        // Kirim data ke view dashboard
        return view('dashboard', [
            'newKegiatanCount' => $newKegiatanCount,
            'upcomingKegiatan' => $upcomingKegiatan,
            'upcomingAgenda' => $upcomingAgenda,
        ]);
    }
}
