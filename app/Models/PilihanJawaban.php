<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PilihanJawaban extends Model
{
	use HasFactory;


	protected $table = "pilihan_jawaban";
	protected $primaryKey = "id_pilihan_jawaban";
	// public $incrementing = false;

	public function pertanyaan()
	{
		return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id', 'id_pertanyaan');
	}

	public static function store($request)
	{
		$save = new PilihanJawaban;
		$save->id_pilihan_jawaban = $request->id_pilihan_jawaban;
		$save->pertanyaan_id = $request->pertanyaan_id;
		$save->pilihan_text = $request->pilihan_text;
		$save->nama_file = $request->nama_file;
		$save->tipe_file = $request->tipe_file;
		$save->benar = $request->benar;
		return $save->save() ? $save : false;
	}
}
