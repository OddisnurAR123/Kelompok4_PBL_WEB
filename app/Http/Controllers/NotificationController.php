<?php

namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use App\Models\AgendaModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil kegiatan yang terjadi dalam 7 hari ke depan dan diikuti oleh pengguna
        $kegiatan = KegiatanModel::query();

        // Batasi kegiatan untuk pengguna yang bukan admin atau manager
        if (!in_array($user->id_jenis_pengguna, [1, 2])) {
            $kegiatan->whereHas('kegiatanUsers', function ($query) use ($user) {
                $query->where('t_kegiatan_user.id_pengguna', $user->id_pengguna);
            });
        }

        // Ambil kegiatan yang terjadi dalam 7 hari ke depan
        $kegiatan = $kegiatan->where('tanggal_mulai', '>=', Carbon::now())
            ->where('tanggal_mulai', '<=', Carbon::now()->addDays(7))
            ->first(); // Mengambil kegiatan pertama dalam rentang waktu 7 hari

        // Ambil agenda yang terjadi dalam 7 hari ke depan dan diikuti oleh pengguna
        $agenda = AgendaModel::query();

        // Batasi agenda untuk pengguna yang bukan admin atau manager
        if (!in_array($user->id_jenis_pengguna, [1, 2])) {
            $agenda->whereHas('agendaUsers', function ($query) use ($user) {
                $query->where('id_pengguna', $user->id_pengguna);
            });
        }

        // Ambil agenda yang terjadi dalam 7 hari ke depan
        $agenda = $agenda->where('tanggal_agenda', '>=', Carbon::now())
            ->where('tanggal_agenda', '<=', Carbon::now()->addDays(7))
            ->first(); // Mengambil agenda pertama dalam rentang waktu 7 hari

        // Mengirim data kegiatan dan agenda ke view dashboard
        return view('dashboard', compact('kegiatan', 'agenda'));
    }
}
