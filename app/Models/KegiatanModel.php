<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\KategoriKegiatanModel;
use App\Models\PenggunaModel;
use App\Models\AgendaModel;
use App\Models\JabatanKegiatanModel;
use App\Models\DetailKegiatanModel;

class KegiatanModel extends Model
{
    use HasFactory;

    protected $table = 't_kegiatan';

    protected $primaryKey = 'id_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'kode_kegiatan',
        'nama_kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'periode',
        'id_kategori_kegiatan',
    ];    

    public function kategoriKegiatan()
    {
        return $this->belongsTo(KategoriKegiatanModel::class, 'id_kategori_kegiatan');
    }

    // Scope untuk mengambil kegiatan baru
    public function scopeBaru($query)
    {
        $today = Carbon::now();
        return $query->where('tanggal_mulai', '>=', $today->subDays(7))
                     ->orderBy('tanggal_mulai', 'desc');
    }

    // Relasi ke tabel detail_kegiatan
    public function detailKegiatan()
    {
        return $this->hasMany(DetailKegiatanModel::class, 'id_kegiatan', 'id_kegiatan');
    }
    
    // Relasi banyak ke banyak dengan pengguna
    public function pengguna()
{
    return $this->belongsToMany(PenggunaModel::class, 't_kegiatan_user', 'id_kegiatan', 'id_pengguna')
                ->withPivot('id_jabatan_kegiatan');
}

    // Relasi satu ke banyak dengan jabatan kegiatan
    public function JabatanKegiatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan');
    }

    public function anggota()
    {
        return $this->hasMany(KegiatanUser::class, 'id_kegiatan', 'id_kegiatan')
            ->join('m_pengguna', 't_kegiatan_user.id_pengguna', '=', 'm_pengguna.id_pengguna')
            ->join('m_jabatan_kegiatan', 't_kegiatan_user.id_jabatan_kegiatan', '=', 'm_jabatan_kegiatan.id_jabatan_kegiatan')
            ->select(
                'm_pengguna.nama_pengguna',
                'm_jabatan_kegiatan.nama_jabatan_kegiatan',
                't_kegiatan_user.*'
            );
    }

    public function agenda()
    {
        return $this->hasMany(AgendaModel::class, 'id_kegiatan', 'id_kegiatan');
    }
    

    // Relasi dengan DetailAgenda
    public function detailAgenda()
    {
        return $this->hasMany(DetailAgendaModel::class, 'id_kegiatan');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 't_kegiatan_user', 'id_kegiatan', 'id_kegiatan_user');
    }
    
}