<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable; // Implementasi class Authenticatable
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Casts\Attribute;


class UserModel extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'm_pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'id_jenis_pengguna', 
        'nama_pengguna', 
        'username', 
        'password', 
        'email', 
        'foto_profil', 
        'created_at', 
        'updated_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed', // This will auto-hash password before saving
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Mendapatkan nama role
     */
    public function getRoleName(): string
    {
        return $this->jenis_pengguna->nama_jenis_pengguna ?? '';
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($role): bool
    {
        return $this->jenis_pengguna->kode_jenis_pengguna == $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole()
    {
        return $this->jenis_pengguna->kode_jenis_pengguna ?? null;
    }
}
