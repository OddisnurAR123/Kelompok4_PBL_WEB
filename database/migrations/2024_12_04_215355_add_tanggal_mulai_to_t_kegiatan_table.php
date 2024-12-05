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
    Schema::table('t_kegiatan', function (Blueprint $table) {
        $table->date('tanggal_mulai'); // Kolom tanggal_mulai
    });
}

public function down()
{
    Schema::table('t_kegiatan', function (Blueprint $table) {
        $table->dropColumn('tanggal_mulai');
    });
}

};
