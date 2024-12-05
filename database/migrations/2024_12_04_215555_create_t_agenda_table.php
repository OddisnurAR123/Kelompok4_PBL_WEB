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
    Schema::create('t_agenda', function (Blueprint $table) {
        $table->id(); // Kolom ID
        $table->string('judul_agenda'); // Kolom untuk judul agenda
        $table->date('tanggal_agenda'); // Kolom untuk tanggal agenda
        $table->text('deskripsi'); // Kolom untuk deskripsi agenda
        $table->timestamps(); // Kolom created_at dan updated_at
    });
}

public function down()
{
    Schema::dropIfExists('t_agenda');
}

};
