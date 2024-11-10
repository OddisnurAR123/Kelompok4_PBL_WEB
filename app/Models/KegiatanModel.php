<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use HasFactory;

    protected $table = 'kegiatan'; // Nama tabel di database, sesuaikan jika berbeda

    protected $primaryKey = 'id'; // Nama kolom primary key, sesuaikan jika berbeda

    // Kolom yang dapat diisi melalui mass assignment
    protected $fillable = [
        'nama_kegiatan',
    ];
}