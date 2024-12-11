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

    public function getJWTIdentifier(){
        return $this->getKey();
       }
    
    public function getJWTCustomClaims() {
        return [];
       }

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
        'password' => 'hashed',
    ];

    public function jenisPengguna(): BelongsTo
    {
        return $this->belongsTo(JenisPenggunaModel::class, 'id_jenis_pengguna', 'id_jenis_pengguna');
    }

    public function kegiatan()
    {
        return $this->belongsToMany(KegiatanModel::class, 't_kegiatan_user')
                    ->withPivot('id_jabatan_kegiatan');
    }

    protected function image(): Attribute{
        return Attribute::make(
            get: fn ($image) => url('/storage/posts/' . $image),
        );
    }

    /**
     * Mendapatkan nama role
     */
    public function getRoleName(): string
    {
        return $this->jenisPengguna->nama_jenis_pengguna;
    }
    
    public function hasRole($role): bool
    {
        return $this->jenisPengguna->kode_jenis_pengguna == $role;
    }
    
    /**
     * Mendapatkan kode role
     */
    public function getRole()
    {
        return $this->jenis_pengguna->kode_jenis_pengguna ?? null;
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
    
    public function getAuthPassword()
    {
        return $this->password;
    }
}
