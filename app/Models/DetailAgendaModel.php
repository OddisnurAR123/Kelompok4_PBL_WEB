<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailAgenda extends Model
{
    use HasFactory;

    protected $table = 't_detail_agenda';
    protected $primaryKey = 'id_detail_agenda';

    protected $fillable = [
        'id_agenda',
        'dokumen',
        'progres_agenda',
        'keterangan',
        'berkas',
        'created_at',
        'updated_at',
    ];

    // Relasi ke tabel Agenda
    public function agenda()
    {
        return $this->belongsTo(AgendaModel::class, 'id_agenda');
    }
}
