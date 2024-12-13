<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\KegiatanModel;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id_pengguna; // Mendapatkan id_pengguna dari pengguna yang login

        // Ambil data kegiatan terbaru untuk pengguna yang login
        $newKegiatan = KegiatanModel::where('id_pengguna', $userId)
            ->orderBy('tanggal_mulai', 'desc')
            ->get();

        // Ambil data agenda terbaru untuk pengguna yang login
        $newAgenda = DB::table('t_agenda')
            ->where('id_pengguna', $userId)
            ->orderBy('tanggal_agenda', 'desc')
            ->get();

        // Kirim data ke view dashboard
        return view('dashboard', compact('newKegiatan', 'newAgenda'));
    }
}