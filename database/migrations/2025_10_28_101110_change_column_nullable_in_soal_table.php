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
            $table->datetime('mulai_pengerjaan')->nullable()->change();
            $table->datetime('selesai_pengerjaan')->nullable()->change();
            $table->integer('jumlah_soal')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soal', function (Blueprint $table) {
            $table->datetime('mulai_pengerjaan')->nullable(false)->change();
            $table->datetime('selesai_pengerjaan')->nullable(false)->change();
            $table->integer('jumlah_soal')->nullable(false)->change();
        });
    }
};