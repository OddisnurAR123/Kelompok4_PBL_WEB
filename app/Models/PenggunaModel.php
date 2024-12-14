<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;

class PenggunaModel extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;

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
        // Tidak perlu menggunakan 'hashed' di sini, karena Laravel secara otomatis menangani hashing password
        'password' => 'string',  // Bisa gunakan tipe 'string' jika Anda tidak perlu casting khusus untuk password
    ];

    // Relasi ke model JenisPenggunaModel
    public function jenisPengguna()
    {
        return $this->belongsTo(JenisPenggunaModel::class, 'id_jenis_pengguna', 'id_jenis_pengguna');
    }

    // Implementasi metode JWTSubject
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

    // Di dalam model User
    public function jabatanKegiatans()
    {
        return $this->belongsToMany(JabatanKegiatanModel::class, 't_kegiatan_user', 'id_pengguna', 'id_jabatan_kegiatan');
    }

    // Relasi ke model JabatanKegiatanModel
    public function jabatanKegiatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan', 'id_jabatan_kegiatan');
    }

    // Pada model User
    public function jabatanKegiatanss()
    {
        return $this->belongsToMany(KegiatanModel::class, 't_kegiatan_user', 'id_pengguna', 'id_kegiatan')
                    ->withPivot('id_jabatan_kegiatan')
                    ->join('m_jabatan_kegiatan', 't_kegiatan_user.id_jabatan_kegiatan', '=', 'm_jabatan_kegiatan.id_jabatan_kegiatan')
                    ->where('m_jabatan_kegiatan.is_pic', 1);
    }
    public function agendas()
    {
        return $this->hasMany(AgendaModel::class, 'id_pengguna'); // or the appropriate foreign key
    }
    /**
     * Mendapatkan nama role
     */
    public function getRoleName(): string
    {
        return $this->jenisPengguna->nama_jenis_pengguna ?? 'Role tidak ditemukan';
    }

    /**
     * Cek apakah user memiliki role tertentu
     */
    public function hasRole($role): bool
    {
        // Pastikan jenisPengguna sudah ter-load sebelum mengakses kode_jenis_pengguna
        return $this->jenisPengguna && $this->jenisPengguna->kode_jenis_pengguna == $role;
    }

    /**
     * Mendapatkan kode role
     */
    public function getRole()
    {
        return $this->jenisPengguna->kode_jenis_pengguna ?? null;
    }
}
