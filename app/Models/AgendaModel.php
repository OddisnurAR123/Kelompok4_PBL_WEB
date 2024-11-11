<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaModel extends Model
{
    use HasFactory;

    protected $table = 't_agenda';
    protected $primaryKey = 'id_agenda';

    protected $fillable = [
        'kode_agenda',
        'nama_agenda',
        'id_kegiatan',
        'tempat_agenda',
        'id_jenis_pengguna',
        'id_jabatan_kegiatan',
        'bobot_anggota',
        'deskripsi',
        'tanggal_agenda',
        'created_at',
        'updated_at',
    ];

    // Relasi ke tabel DetailAgenda
    public function detailAgenda()
    {
        return $this->hasMany(DetailAgenda::class, 'id_agenda');
    }
}
