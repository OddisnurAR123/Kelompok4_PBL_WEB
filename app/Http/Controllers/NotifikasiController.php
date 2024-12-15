<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KegiatanModel;
use App\Models\KegiatanUser;
use App\Models\AgendaModel;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function index()
    {
        // Ambil pengguna yang sedang login
        $user = Auth::user();
    
        // Ambil semua ID kegiatan yang diikuti pengguna yang login
        $kegiatanUser = KegiatanUser::where('id_pengguna', $user->id_pengguna)->pluck('id_kegiatan');
    
        // Pastikan ada ID kegiatan yang ditemukan
        if ($kegiatanUser->isEmpty()) {
            // Kirim notifikasi kosong jika tidak ada kegiatan
            return view('dashboard', ['notifikasi' => []]);
        }
    
        // Ambil data kegiatan berdasarkan ID yang ditemukan di KegiatanUser
        $kegiatanDiikuti = KegiatanModel::whereIn('id_kegiatan', $kegiatanUser)
            ->select('id_kegiatan', 'nama_kegiatan', 'tanggal_mulai', 'tanggal_selesai')
            ->get();
    
        // Ambil data agenda yang berkaitan dengan pengguna
        $agendaDiikuti = AgendaModel::where('id_pengguna', $user->id_pengguna)->get();
    
        // Inisialisasi array notifikasi
        $notifikasi = [];
    
        // Tambahkan notifikasi kegiatan yang berlangsung hari ini
        foreach ($kegiatanDiikuti as $kegiatan) {
            $tanggalMulai = Carbon::parse($kegiatan->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($kegiatan->tanggal_selesai);
    
            if ($tanggalMulai->isToday()) {
                $notifikasi[] = "Kegiatan '{$kegiatan->nama_kegiatan}' dimulai hari ini!";
            }
    
            if ($tanggalSelesai->isToday()) {
                $notifikasi[] = "Kegiatan '{$kegiatan->nama_kegiatan}' selesai hari ini!";
            }
        }
    
        // Tambahkan notifikasi agenda untuk hari ini
        foreach ($agendaDiikuti as $agenda) {
            $tanggalAgenda = Carbon::parse($agenda->tanggal_agenda);
    
            if ($tanggalAgenda->isToday()) {
                if (Carbon::now()->greaterThanOrEqualTo($tanggalAgenda)) {
                    $notifikasi[] = "Agenda '{$agenda->nama_agenda}' sedang berlangsung sekarang!";
                } else {
                    $notifikasi[] = "Agenda '{$agenda->nama_agenda}' akan berlangsung hari ini.";
                }
            }
        }
    
        // Tambahkan notifikasi kegiatan baru dalam 1 minggu terakhir
        $kegiatanBaru = KegiatanModel::whereIn('id_kegiatan', $kegiatanUser)
            ->where('tanggal_mulai', '>=', Carbon::now()->subDays(7))
            ->get();
    
        foreach ($kegiatanBaru as $baru) {
            $notifikasi[] = "Kegiatan baru '{$baru->nama_kegiatan}' telah ditambahkan!";
        }
    
        // Kirim data notifikasi ke view
        return view('dashboard', compact('notifikasi'));
    }
}
