<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePraktekBaikGuruFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('praktek_baik_guru_file', function (Blueprint $table) {
            $table->id('id_praktek_baik_guru_file');
            $table->foreignId('praktek_baik_guru_id');
            $table->string('original_name');
            $table->string('file_name');
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
        Schema::dropIfExists('praktek_baik_guru_file');
    }
}
