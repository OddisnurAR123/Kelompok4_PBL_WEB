<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; 

class PenggunaModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'm_pengguna';
    protected $primaryKey = 'id_pengguna';
    public $timestamps = true;

    protected $fillable = [
        'id_jenis_pengguna', 
        'nama_pengguna',
        'username', 
        'password', 
        'email', 
        'foto_profil'
    ];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];

    public function jenisPengguna()
    {
        return $this->belongsTo(JenisPenggunaModel::class, 'id_jenis_pengguna', 'id_jenis_pengguna');
    }

    public function kegiatan()
    {
        return $this->belongsToMany(KegiatanModel::class, 't_kegiatan_user', 'id_pengguna', 'id_kegiatan');
    }

    public function jabatanKegiatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan', 'id_jabatan_kegiatan');
    }
}
