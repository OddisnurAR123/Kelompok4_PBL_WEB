<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgendaModel extends Model
{
    use HasFactory;

    protected $table = 't_agenda';
    protected $primaryKey = 'id_agenda';

    protected $fillable = [
        'kode_agenda',
        'nama_agenda',
        'id_kegiatan',
        'tempat_agenda',
        'id_jenis_pengguna',
        'id_jabatan_kegiatan',
        'bobot_anggota',
        'deskripsi',
        'tanggal_agenda',
        'created_at',
        'updated_at',
    ];

    // Relasi ke tabel JenisPengguna
    public function jenisPengguna()
    {
        return $this->belongsTo(JenisPenggunaModel::class, 'id_jenis_pengguna');
    }

    // Relasi ke tabel Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(KegiatanModel::class, 'id_kegiatan');
    }
    public function create_ajax()
{
    // Ambil data yang diperlukan untuk form
    $kegiatans = KegiatanModel::select('id_kegiatan', 'nama_kegiatan')->get(); 
    $jenisPenggunas = JenisPenggunaModel::select('id_jenis_pengguna', 'nama_jenis_pengguna')->get();
    $jabatanKegiatans = JabatanKegiatanModel::select('id', 'nama_jabatan')->get();
    
    // Menambahkan breadcrumb
    $breadcrumb = (object) [
        'title' => 'Tambah Agenda Kegiatan (AJAX)',
        'list' => ['Dashboard', 'Agenda', 'Tambah']
    ];

    // Kembalikan view dengan data yang dibutuhkan
    return view('agenda.create_ajax', compact('kegiatans', 'jenisPenggunas', 'jabatanKegiatans', 'breadcrumb'));
}

}
