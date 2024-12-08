<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaModel extends Model
{
    use HasFactory;

    protected $table = 't_agenda'; // Nama tabel di database
    protected $primaryKey = 'id_agenda'; // Primary key
    public $timestamps = true;

    protected $fillable = [
        'nama_agenda',
        'id_kegiatan',
        'tempat_agenda',
        'id_pengguna',  // Relasi id_pengguna
        'bobot_anggota',
        'deskripsi',
        'tanggal_agenda',
        'created_at',
        'updated_at',
    ];

    // Relasi ke KegiatanUser
    public function kegiatan() {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }
    
    public function kegiatanUser() {
        return $this->belongsTo(KegiatanUser::class, 'id_pengguna', 'id_pengguna');
    }

    // Relasi dengan DetailAgenda
    public function detailAgenda()
    {
        return $this->hasMany(DetailAgendaModel::class, 'id_detail_agenda', 'id_detail_agenda');
    }

    public function pengguna() {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna', 'id_pengguna');
    }
    
}
