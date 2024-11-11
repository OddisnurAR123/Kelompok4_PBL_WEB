<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPenggunaModel extends Model
{
    use HasFactory;

    protected $table= 'm_jenis_pengguna'; //mendefinisikan nama tabel yang akan digunakan. :o
    protected $primaryKey = 'id_jenis_pengguna';
    protected $fillable = ['kode_jenis_pengguna','nama_jenis_pengguna'];
}
