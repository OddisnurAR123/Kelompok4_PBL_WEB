<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JabatanKegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'm_jabatan_kegiatan'; 
    protected $primaryKey = 'id_jabatan_kegiatan'; 
    protected $fillable = ['kode_jabatan_kegiatan', 'nama_jabatan_kegiatan', 'is_pic', 'urutan'];
    public $timestamps = false; 

    public function kegiatan()
    {
        return $this->belongsToMany(KegiatanModel::class, 't_kegiatan_user', 'id_jabatan_kegiatan', 'id_jabatan_kegiatan');
    }
}
