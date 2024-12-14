<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanUser extends Model
{
    use HasFactory;

    protected $table = 't_kegiatan_user';
    protected $primaryKey = 'id_kegiatan_user'; // Perbaikan dari id_legiatan_user
    public $timestamps = true;

    protected $fillable = [
        'id_kegiatan',
        'id_pengguna',
        'id_jabatan_kegiatan',
        'created_at',
        'updated_at',
    ];

    public function pengguna()
    {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna', 'id_pengguna');
    }

    public function jabatanKegiatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan', 'id_jabatan_kegiatan');
    }
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan');
    }
}
