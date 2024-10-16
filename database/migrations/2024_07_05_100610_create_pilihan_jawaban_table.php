<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePilihanJawabanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pilihan_jawaban', function (Blueprint $table) {
            $table->id('id_pilihan_jawaban');
			$table->integer('pertanyaan_id');
			$table->text('pilhan_text');
			$table->string('nama_file')->nullable();
			$table->string('type_file')->nullable();
			$table->string('prefix_pilihan',1);
            $table->boolean('benar');
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
        Schema::dropIfExists('pilihan_jawaban');
    }
}
