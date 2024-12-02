<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DraftSuratTugas extends Model
{
    use HasFactory;

    protected $table = 't_draft_surat_tugas';
    protected $primaryKey = 'id_draft';

    protected $fillable = [
        'kode_surat',
        'judul_surat',
        'id_kegiatan',
        'created_at',
        'updated_at',
    ];
}
