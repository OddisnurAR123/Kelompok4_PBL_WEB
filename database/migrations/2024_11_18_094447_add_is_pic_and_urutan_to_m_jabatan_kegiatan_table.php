<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPicAndUrutanToMJabatanKegiatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_jabatan_kegiatan', function (Blueprint $table) {
            $table->boolean('is_pic')->default(false); // Kolom ditambahkan di akhir tabel
            $table->integer('urutan')->default(1);     // Kolom ditambahkan di akhir tabel
        });
    }    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_jabatan_kegiatan', function (Blueprint $table) {
            // Menghapus kolom is_pic dan urutan
            $table->dropColumn(['is_pic', 'urutan']);
        });
    }
}
