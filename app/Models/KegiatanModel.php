<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class KegiatanModel extends Model
{
    use HasFactory;

    protected $table = 't_kegiatan';

    protected $primaryKey = 'id_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'kode_kegiatan',
        'nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
    ];    

    
    // Scope untuk mengambil kegiatan baru
    public function scopeBaru($query)
    {
        $today = Carbon::now();
        return $query->where('tanggal_mulai', '>=', $today->subDays(7))
                     ->orderBy('tanggal_mulai', 'desc');
    }
}