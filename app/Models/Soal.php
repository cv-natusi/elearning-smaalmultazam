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
		$save->file_soal = $request->file_soal;
		$save->jenis_file = $request->jenis_file;
		$save->jenis = $request->jenis;
		$save->durasi = $request->durasi;
		$save->user_id = Auth::user()->id;
		$save->acak = empty($request->acak) ?? false;
		$save->status = empty($request->status) ?? true;
		return $save->save() ? $save : false;
	}

	public static function modify($request, $soal)
	{
		$soal->kelas_id = $request->kelas_id;
		$soal->tahun_ajaran_id = $request->tahun_ajaran_id;
		$soal->judul_soal = $request->judul_soal;
		$soal->mapel_id = $request->mapel_id;
		$soal->kkm = $request->kkm;
		$soal->mulai_pengerjaan = $request->mulai_pengerjaan;
		$soal->selesai_pengerjaan = $request->selesai_pengerjaan;
		$soal->jumlah_soal = $request->jumlah_soal;
		$soal->pendahuluan = $request->pendahuluan;
		
		if ($request->has('file_soal')) {
			$soal->file_soal = $request->file_soal;
		}

		$soal->jenis_file = $request->jenis_file;
		$soal->jenis = $request->jenis;
		$soal->durasi = $request->durasi;
		
		$soal->acak = $request->boolean('acak');
		$soal->status = $request->boolean('status', true);
		
		return $soal->save() ? $soal : false;
	}

	public function jenisFile()
    {
        return $this->belongsTo(MasterJenisFile::class, 'jenis_file');
    }
}
