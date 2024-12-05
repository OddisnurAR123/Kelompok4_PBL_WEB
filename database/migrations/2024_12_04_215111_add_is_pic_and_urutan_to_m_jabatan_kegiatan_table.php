<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('m_jabatan_kegiatan', function (Blueprint $table) {
        $table->tinyInteger('is_pic')->default(0); // Kolom is_pic
        $table->integer('urutan')->default(1); // Kolom urutan
    });
}

public function down()
{
    Schema::table('m_jabatan_kegiatan', function (Blueprint $table) {
        $table->dropColumn(['is_pic', 'urutan']);
    });
}

};
