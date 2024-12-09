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
    public $timestamps = true;

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

    public function getJWTIdentifier()
    {
        return $this->getKey();  // Mengembalikan ID pengguna
    }

    public function getJWTCustomClaims()
    {
        return [];  // Kustom klaim JWT, bisa ditambahkan jika perlu
    }
    public function kegiatan()
    {
        return $this->belongsToMany(KegiatanModel::class, 't_kegiatan_user', 'id_pengguna', 'id_kegiatan');
    }

    public function jabatanKegiatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan', 'id_jabatan_kegiatan');
    }

    /**
     * Mendapatkan nama role
     */
    public function getRoleName(): string
    {
        return $this->jenisPengguna->nama_jenis_pengguna;
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($role): bool
    {
        return $this->jenisPengguna->kode_jenis_pengguna == $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole()
    {
        return $this->jenisPengguna->kode_jenis_pengguna;
    }

}
