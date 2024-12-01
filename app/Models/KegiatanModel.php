<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\KategoriKegiatanModel;
use App\Models\PenggunaModel;
use App\Models\JabatanKegiatanModel;

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
        return $this->belongsToMany(PenggunaModel::class, 't_kegiatan_user', 'id_kegiatan', 'id_pengguna');
    }

    // Relasi satu ke banyak dengan jabatan kegiatan
    public function JabatanKegiatan()
    {
        return $this->belongsTo(JabatanKegiatanModel::class, 'id_jabatan_kegiatan');
    }

}