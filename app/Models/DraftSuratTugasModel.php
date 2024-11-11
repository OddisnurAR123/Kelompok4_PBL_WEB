<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftSuratTugas extends Model
{
    use HasFactory;

    protected $table = 'surat_tugas'; // Sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_surat',
        'judul_surat',
        'id_jabatan_kegiatan',
        'created_at',
        'updated_at',
    ];
}
