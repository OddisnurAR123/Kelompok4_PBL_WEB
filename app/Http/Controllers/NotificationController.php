<?php

namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use App\Models\AgendaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function index()
    {
        // Dapatkan pengguna yang sedang login
        $pengguna = auth()->user();

        // Query untuk mendapatkan kegiatan
        $kegiatan = DB::table('t_kegiatan')
            ->join('t_kegiatan_user', 't_kegiatan.id_kegiatan', '=', 't_kegiatan_user.id_kegiatan')
            ->where('t_kegiatan_user.id_pengguna', $pengguna->id_pengguna)
            ->where('t_kegiatan.tanggal_mulai', '>=', Carbon::now())
            ->where('t_kegiatan.tanggal_mulai', '<=', Carbon::now()->addDays(7))
            ->orderBy('t_kegiatan.tanggal_mulai', 'asc')
            ->first();

        // Ambil agenda (sesuaikan jika langsung terhubung ke pengguna)
        $agenda = DB::table('t_agenda')
            ->where('id_pengguna', $pengguna->id_pengguna)
            ->where('tanggal_agenda', '>=', Carbon::now())
            ->where('tanggal_agenda', '<=', Carbon::now()->addDays(7))
            ->orderBy('tanggal_agenda', 'asc')
            ->first();

        // Kirim data ke view dashboard
        return view('dashboard', compact('kegiatan', 'agenda'));
    }
}