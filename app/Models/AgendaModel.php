<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PenggunaModel;

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
        'id_pengguna',
        'bobot_anggota',
        'deskripsi',
        'tanggal_agenda'
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
        return $this->hasMany(DetailAgendaModel::class, 'id_agenda', 'id_agenda');
    }

    public function pengguna() {
        return $this->belongsTo(PenggunaModel::class, 'id_pengguna', 'id_pengguna');
    }

    public function agenda()
    {
        return $this->hasMany(AgendaModel::class, 'id_kegiatan', 'id_kegiatan');
    }

    // Definisikan relasi dengan pengguna melalui tabel pivot 'agenda_user'
    public function agendaUsers()
    {
        return $this->belongsToMany(
            PenggunaModel::class, // Model pengguna
            't_agenda_user', // Nama tabel pivot
            'id_agenda', // Foreign key di tabel pivot untuk agenda
            'id_pengguna' // Foreign key di tabel pivot untuk pengguna
        );
    }
}
