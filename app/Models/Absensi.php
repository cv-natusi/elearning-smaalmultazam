<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
	use HasFactory;
	protected $table = 'absensi';
	protected $primaryKey = 'id_absensi';

	public function guru() {
		return $this->belongsTo(Guru::class,'guru_id','id_guru');
	}

	public function user() {
		return $this->belongsTo(Users::class,'users_id','id');
	}
}
