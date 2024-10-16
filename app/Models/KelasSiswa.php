<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasSiswa extends Model
{
	use HasFactory;
	protected $table = "kelas_siswa";
	protected $primaryKey = "id_kelas_siswa";

	public $timestamps = false;

	public function siswa()
	{
		return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
	}

	public function kelas()
	{
		return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
	}

	public function tahun_ajaran()
	{
		return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'id_tahun_ajaran');
	}
}
