<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraktekBaikGuruGambar extends Model
{
    use HasFactory;
    protected $table = 'praktek_baik_guru_gambar';
    protected $primaryKey = 'id_praktek_baik_guru_gambar';
    protected $connection = 'mysql';

	public function praktek_baik_guru() {
		return $this->belongsTo(PraktekBaikGuru::class,'praktek_baik_guru_id','id_praktek_baik_guru');
	}
}
