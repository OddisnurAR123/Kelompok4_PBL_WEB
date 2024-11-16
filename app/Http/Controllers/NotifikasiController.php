<?php

namespace App\Http\Controllers;

use App\Models\KegiatanModel;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class NotifikasiController extends Controller
{
    public function getNotifications()
    {
        $today = Carbon::now();
    
        // Ambil data kegiatan baru menggunakan scope dari model
        $newKegiatan = KegiatanModel::baru()->get();
    
        // Ambil data agenda baru menggunakan query langsung
        $newAgenda = DB::table('t_agenda')
            ->whereDate('tanggal_agenda', '>=', $today->subDays(7))
            ->orderBy('tanggal_agenda', 'desc')
            ->get();
    
            return response()->json([
                'newKegiatan' => $newKegiatan->isNotEmpty() ? $newKegiatan : [],
                'newAgenda' => $newAgenda->isNotEmpty() ? $newAgenda : []
            ]);
            
    }
    
}

