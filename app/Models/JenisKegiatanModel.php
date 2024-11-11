<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisKegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'kategori_kegiatan'; 
    protected $primaryKey = 'id_kategori_kegiatan'; 
    protected $fillable = ['kode_kategori_kegiatan', 'nama_kategori_kegiatan'];
    public $timestamps = false; 
}
