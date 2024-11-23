<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKegiatanModel extends Model
{
    use HasFactory;

    protected $table = 'm_kategori_kegiatan'; // Mendefinisikan nama tabel yang digunakan oleh model ini
    protected $primaryKey = 'id_kategori_kegiatan'; // Mendefinisikan primary key dari tabel yang digunakan
    protected $fillable = ['kode_kategori_kegiatan','nama_kategori_kegiatan'];

    public function kegiatan()
    {
        return $this->hasMany(KegiatanModel::class, 'id_kategori_kegiatan');
    }
}