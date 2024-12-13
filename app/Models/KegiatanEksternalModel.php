<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanEksternalModel extends Model
{
    use HasFactory;

    protected $table = 't_kegiatan_eksternal'; // Mendefinisikan nama tabel yang akan digunakan
    protected $primaryKey = 'id_kegiatan_eksternal';
    public $timestamps = true;
    protected $fillable = ['nama_kegiatan', 'waktu_kegiatan', 'pic', 'periode', 'id_pengguna']; // Menambahkan id_pengguna
}
