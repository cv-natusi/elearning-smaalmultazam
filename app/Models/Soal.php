<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Auth;

class Soal extends Model
{
	use HasFactory;

	protected $table = "soal";
	protected $primaryKey = "id_soal";

	public function mata_pelajaran()
	{
		return $this->belongsTo(MataPelajaran::class, 'mapel_id', 'id_mapel');
	}

	public function guru()
	{
		return $this->belongsTo(Guru::class, 'user_id', 'users_id');
	}

	public function pertanyaan(){
		return $this->hasMany(Pertanyaan::class, 'soal_id', 'id_soal');
	}

	public function jawaban_siswa(){
		return $this->hasMany(JawabanSiswa::class, 'soal_id', 'id_soal');
	}

	public static function store($request)
	{
		$save = new Soal;
		$save->kelas_id = $request->kelas_id;
		$save->tahun_ajaran_id = $request->tahun_ajaran_id;
		$save->judul_soal = $request->judul_soal;
		$save->mapel_id = $request->mapel_id;
		$save->kkm = $request->kkm;
		$save->mulai_pengerjaan = $request->mulai_pengerjaan;
		$save->selesai_pengerjaan = $request->selesai_pengerjaan;
		$save->jumlah_soal = $request->jumlah_soal;
		$save->pendahuluan = $request->pendahuluan;
		$save->jenis = $request->jenis;
		$save->durasi = $request->durasi;
		$save->user_id = Auth::user()->id;
		$save->acak = empty($request->acak) ?? false;
		$save->status = empty($request->status) ?? true;
		return $save->save() ? $save : false;
	}
}
