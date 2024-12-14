<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AgendaModel;
use App\Models\KegiatanModel;
use App\Models\KegiatanEksternalModel;
use App\Notifications\KegiatanAgendaNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;


class NotifikasiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        // Ambil agenda terdekat
        $agenda = AgendaModel::where('id_pengguna', $user->id_pengguna)
            ->whereDate('tanggal_agenda', '>=', Carbon::now())
            ->orderBy('tanggal_agenda', 'asc')
            ->first();
    
        // Ambil kegiatan terdekat
        $kegiatan = KegiatanModel::whereDate('tanggal_mulai', '>=', Carbon::now())
            ->orderBy('tanggal_mulai', 'asc')
            ->first();
    
        // Ambil kegiatan eksternal terdekat
        $kegiatanEksternal = KegiatanEksternalModel::where('id_pengguna', $user->id_pengguna)
            ->whereDate('waktu_kegiatan', '>=', Carbon::now())
            ->orderBy('waktu_kegiatan', 'asc')
            ->first();
    
        // Kirimkan notifikasi jika ada data
        if ($agenda || $kegiatan || $kegiatanEksternal) {
            $user->notify(new KegiatanAgendaNotification($agenda, $kegiatan, $kegiatanEksternal));
        }
    
        // Reminder kegiatan dalam 1 minggu ke depan
        $reminderKegiatan = KegiatanModel::whereDate('tanggal_mulai', '>=', Carbon::now())
            ->whereDate('tanggal_mulai', '<=', Carbon::now()->addWeek())
            ->orderBy('tanggal_mulai', 'asc')
            ->get();
    
        // Reminder agenda dalam 1 minggu ke depan
        $reminderAgenda = AgendaModel::where('id_pengguna', $user->id_pengguna)
            ->whereDate('tanggal_agenda', '>=', Carbon::now())
            ->whereDate('tanggal_agenda', '<=', Carbon::now()->addWeek())
            ->orderBy('tanggal_agenda', 'asc')
            ->get();
    
        // Pastikan variabel dikirim ke view
        return view('dashboard', compact('reminderAgenda', 'reminderKegiatan'));
    }
    
}
