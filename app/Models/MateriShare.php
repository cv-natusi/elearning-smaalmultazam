<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriShare extends Model
{
	use HasFactory;
	protected $table = "materi_share";
	protected $primaryKey = "id_materi";

	public $timestamps = false;

	public function mata_pelajaran()
	{
		return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'id_mapel');
	}

	public function kelas() {
		return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
	}

	public function guru() {
		return $this->belongsTo(Guru::class, 'user_id','users_id');
	}
}
