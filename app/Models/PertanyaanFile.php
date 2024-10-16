<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanFile extends Model
{
	use HasFactory;


	protected $table = "pertanyaan_file";
	protected $primaryKey = "id_pertanyaan_file";

	public function pertanyaan()
	{
		return $this->belongsTo(Pertanyaan::class, 'pertanyaan_id', 'id_pertanyaan');
	}
}
