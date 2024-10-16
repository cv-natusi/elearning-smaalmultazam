<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
	use HasFactory;
	protected $table = "mata_pelajaran";
	protected $primaryKey = "id_mapel";

	public $timestamps = false;

	public function materi_share()
	{
		return $this->hasMany(MateriShare::class, 'mapel_id', 'id_mapel');
	}

	public function kelas_mapel()
	{
		return $this->hasMany(KelasMapel::class, 'mapel_id', 'id_mapel');
	}

	public function soal()
	{
		return $this->hasMany(Soal::class, 'mapel_id', 'id_mapel');
	}
}
