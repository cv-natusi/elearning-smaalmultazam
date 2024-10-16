<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePraktekBaikGuruTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('praktek_baik_guru', function (Blueprint $table) {
			$table->id('id_praktek_baik_guru');
			$table->string('judul');
			$table->text('isi');
			$table->string('gambar');
			$table->boolean('status');
			$table->integer('user_id');
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
		Schema::dropIfExists('praktek_baik_guru');
	}
}
