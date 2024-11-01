<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugasTambahansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tugas_tambahan', function (Blueprint $table) {
            $table->id('id_tugas_tambahan');
            $table->integer('guru_id');
            $table->string('nama_tugas_tambahan', 255);
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
        Schema::dropIfExists('tugas_tambahan');
    }
}
