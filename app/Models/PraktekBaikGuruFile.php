<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraktekBaikGuruFile extends Model
{
    use HasFactory;
    protected $table = 'praktek_baik_guru_file';
    protected $primaryKey = 'id_praktek_baik_guru_file';
    protected $connection = 'mysql';

	public function praktek_baik_guru() {
		return $this->belongsTo(PraktekBaikGuru::class,'praktek_baik_guru_id','id_praktek_baik_guru');
	}
}
