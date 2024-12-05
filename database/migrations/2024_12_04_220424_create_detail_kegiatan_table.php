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
    Schema::create('detail_kegiatan', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('kegiatan_id')->index();
        $table->string('judul');
        $table->text('deskripsi');
        $table->timestamp('tanggal_mulai');
        $table->timestamp('tanggal_selesai');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_kegiatan');
    }
};
