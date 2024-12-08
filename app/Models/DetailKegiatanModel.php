<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKegiatanModel extends Model
{
    use HasFactory;
    protected $table = 't_detail_kegiatan';

    // Primary key tabel
    protected $primaryKey = 'id_detail_kegiatan';

    protected $fillable = [
        'id_kegiatan',
        'keterangan',
        'progres_kegiatan',
        'beban_kerja',
    ];
    public $timestamps = false;

    // Relasi ke tabel t_kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }
}
