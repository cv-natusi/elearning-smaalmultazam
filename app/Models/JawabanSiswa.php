<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSiswa extends Model
{
	use HasFactory;
	protected $table = 'jawaban_siswa';
	protected $primaryKey = 'id_jawaban_siswa';

	public function siswa()
	{
		return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
	}

	public function soal()
	{
		return $this->belongsTo(Soal::class, 'soal_id', 'id_soal');
	}
}
