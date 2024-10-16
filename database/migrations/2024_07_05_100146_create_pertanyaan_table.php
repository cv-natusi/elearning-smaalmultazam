<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePertanyaanTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pertanyaan', function (Blueprint $table) {
			$table->id('id_pertanyaan');
			$table->integer('soal_id');
			$table->integer('nomor');
			$table->integer('poin')->nullable();
			$table->text('pertanyaan_text')->nullable();
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
		Schema::dropIfExists('pertanyaan');
	}
}
