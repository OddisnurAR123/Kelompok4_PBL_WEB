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
}
