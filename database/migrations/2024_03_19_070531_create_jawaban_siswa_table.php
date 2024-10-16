<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJawabanSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id('id_jawaban_siswa');
            $table->integer('siswa_id');
            $table->integer('soal_id');
            $table->text('jawaban_siswa')->comment("jawaban menggunakan method explode, dengan separator '-'");
            $table->decimal('nilai',4,1)->comment('Nilai akan terisi ketika soal sudah di selesaikan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jawaban_siswa');
    }
}
