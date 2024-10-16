<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use DB;

class Pertanyaan extends Model
{
	use HasFactory;


	protected $table = "pertanyaan";
	protected $primaryKey = "id_pertanyaan";

	public function pilihan_jawaban()
	{
		return $this->hasMany(PilihanJawaban::class, 'pertanyaan_id', 'id_pertanyaan');
	}

	public function pertanyaan_file()
	{
		return $this->hasMany(PertanyaanFile::class, 'pertanyaan_id', 'id_pertanyaan');
	}

	public function soal(){
		return $this->belongsTo(Soal::class, 'soal_id', 'id_soal');
	}

	public static function generatePertanyaan($request)
	{
		if (count(Pertanyaan::where('soal_id', $request->id_soal)->get())) {
			return false;
		}
		for ($i = 0; $i < (int) ($request->jumlah_soal); $i++) {
			$pertanyaan = new Pertanyaan;
			$pertanyaan->soal_id = $request->id_soal;
			$pertanyaan->nomor = $i + 1;
			$pertanyaan->poin = $request->poin_pertanyaan;
			$pertanyaan->pertanyaan_text = $request->pertanyaan_text;
			if (!$pertanyaan->save()) {
				return false;
			}
		}
		return true;
	}

	public static function getPertanyaans($request) {
		return Pertanyaan::select('id_pertanyaan')->selectRaw("(case when (pertanyaan.pertanyaan_text='' or pertanyaan.pertanyaan_text is null) then false else true end) as pertanyaan_text")->with(['pilihan_jawaban', 'pertanyaan_file'])->where('soal_id', $request->id_soal)->get();
	}

	public static function getPertanyaanKunciJawaban($request) {
		return Pertanyaan::where('soal_id',$request->id_soal)->
			leftJoin('pilihan_jawaban','pertanyaan.id_pertanyaan','=','pilihan_jawaban.pertanyaan_id')->
			where('pilihan_jawaban.benar',1)->
			get();
	}
}
