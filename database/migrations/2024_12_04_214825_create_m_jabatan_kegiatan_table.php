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
    Schema::create('m_jabatan_kegiatan', function (Blueprint $table) {
        $table->id(); // Kolom ID
        $table->string('nama_jabatan'); // Kolom nama jabatan
        $table->timestamps(); // Kolom created_at dan updated_at
    });
}

public function down()
{
    Schema::dropIfExists('m_jabatan_kegiatan');
}

};
