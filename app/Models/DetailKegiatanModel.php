<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailKegiatanModel extends Model
{
    use HasFactory;

    // Tentukan nama tabel yang digunakan
    protected $table = 'detail_kegiatan'; // Sesuaikan dengan nama tabel di database Anda

    // Tentukan primary key
    protected $primaryKey = 'id_detail_kegiatan'; // Sesuaikan dengan kolom primary key

    // Tentukan kolom yang boleh diisi
    protected $fillable = [
        'id_kegiatan', 
        'deskripsi_kegiatan', 
        'tanggal_kegiatan', 
        'lokasi_kegiatan', 
        'status_kegiatan'
    ];

    // Jika primary key bukan auto increment
    // public $incrementing = false;
    
    // Tentukan tipe data kolom yang ingin di-casting (misalnya tanggal)
    protected $casts = [
        'tanggal_kegiatan' => 'datetime',
    ];

    // Relasi ke model Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan');
    }
}