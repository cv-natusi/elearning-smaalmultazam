<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToPraktekBaikGuruFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('praktek_baik_guru_file', function (Blueprint $table) {
            $table->text('file_gambar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('praktek_baik_guru_file', function (Blueprint $table) {
            $table->dropColumn('file_gambar');
        });
    }
}
