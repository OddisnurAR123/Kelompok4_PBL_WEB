<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanUser extends Model
{
    use HasFactory;

    protected $table = 't_kegiatan_user'; // Nama tabel di database
    protected $primaryKey = 'id_legiatan_user'; // Primary key
    // Tambahkan jika tabel menggunakan timestamp
    public $timestamps = true;

    protected $fillable = [
        'id_kegiatan',
        'id_pengguna',
        'id_jabatan_kegiatan',
        'created_at',
        'updated_at',
    ];

    // Relasi ke model pengguna
    public function pengguna()
    {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna', 'id_pengguna');
    }

    // Relasi ke model jabatan kegiatan
    public function jabatanKegiatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan', 'id_jabatan_kegiatan');
    }
}
