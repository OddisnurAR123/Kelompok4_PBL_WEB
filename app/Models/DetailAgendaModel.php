<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AgendaModel;
use App\Models\KegiatanModel;

class DetailAgendaModel extends Model
{
    use HasFactory;

    protected $table = 't_detail_agenda';
    protected $primaryKey = 'id_detail_agenda';

    protected $fillable = [
        'id_kegiatan',
        'id_agenda',
        'progres_agenda',
        'keterangan',
        'berkas',
    ];

    // Relasi ke tabel Agenda
    public function agenda()
    {
        return $this->belongsTo(AgendaModel::class, 'id_agenda', 'id_agenda');
    }
    
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }
    
}
