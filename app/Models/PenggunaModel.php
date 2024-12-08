<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class PenggunaModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'm_pengguna';
    protected $primaryKey = 'id_pengguna';
<<<<<<< HEAD
=======
    public $timestamps = true;
>>>>>>> 6f1566684b9ceda7ae2943632e05bb5053592a4e

    protected $fillable = [
        'id_jenis_pengguna',
        'nama_pengguna',
        'username',
        'password',
        'nip',
        'email',
        'foto_profil',
        'created_at',
        'updated_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function jenisPengguna()
    {
        return $this->belongsTo(JenisPenggunaModel::class, 'id_jenis_pengguna', 'id_jenis_pengguna');
    }

<<<<<<< HEAD
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Mengembalikan ID pengguna
    }

    public function getJWTCustomClaims()
    {
        return [];  // Kustom klaim JWT, bisa ditambahkan jika perlu
=======
    public function kegiatan()
    {
        return $this->belongsToMany(KegiatanModel::class, 't_kegiatan_user', 'id_pengguna', 'id_kegiatan');
    }

    public function jabatanKegiatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan', 'id_jabatan_kegiatan');
>>>>>>> 6f1566684b9ceda7ae2943632e05bb5053592a4e
    }
}
