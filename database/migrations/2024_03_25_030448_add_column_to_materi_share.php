<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToMateriShare extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('materi_share', function (Blueprint $table) {
            $table->integer('kelas_id');
            $table->integer('tahun_ajaran_id');
            $table->string('semester',1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('materi_share', function (Blueprint $table) {
            //
        });
    }
}
