<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->unsignedBigInteger('jenis_file')->nullable()->change();
            $table->foreign('jenis_file')
                  ->references('id')
                  ->on('master_jenis_file')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->dropForeign('soal_jenis_file_foreign');
            $table->string('jenis_file')->nullable()->change();
        });
    }
};