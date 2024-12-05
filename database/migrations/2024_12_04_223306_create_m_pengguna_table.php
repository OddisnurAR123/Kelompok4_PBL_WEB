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
    Schema::create('m_pengguna', function (Blueprint $table) {
    $table->bigIncrements('id_pengguna');  // Kolom AUTO_INCREMENT dan PRIMARY KEY
    $table->string('username');
    $table->string('email');
    $table->string('password');
    $table->timestamps();
});

}

public function down()
{
    Schema::dropIfExists('m_pengguna');
}

};
