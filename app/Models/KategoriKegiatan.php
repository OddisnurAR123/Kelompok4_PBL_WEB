<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriKegiatan extends Model
{
    use HasFactory;

    // Define the table name if it's different from the default "kategori_kegiatans"
    protected $table = 'm_kategori_kegiatan';

    // Define the primary key if it's different from "id"
    protected $primaryKey = 'id_kategori_kegiatan';

    // Define the fields that are mass assignable
    protected $fillable = ['kode_kategori_kegiatan', 'nama_kategori_kegiatan'];

    // Enable or disable timestamps if necessary
    public $timestamps = true;
    
    // Specify the created_at and updated_at columns if they're named differently
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}
