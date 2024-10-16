<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraktekBaikGuru extends Model
{
    use HasFactory;
    protected $table = 'praktek_baik_guru';
    protected $primaryKey = 'id_praktek_baik_guru';
    protected $connection = 'mysql';

	public function praktek_baik_guru_file() {
		return $this->hasMany(PraktekBaikGuruFile::class,'praktek_baik_guru_id','id_praktek_baik_guru');
	}

    public static function getPraktekBaikGuruPaginate(){
		return PraktekBaikGuru::select('*')->selectRaw('id_praktek_baik_guru as id_berita, isi, judul, gambar, created_at as tanggal')
			->where('status', true)
			->with('praktek_baik_guru_file')
			->orderBy('id_praktek_baik_guru', 'DESC')
			->paginate(8);
	}

	public static function getPraktekBaikGuruDetail($id) {
		return PraktekBaikGuru::select('*')->selectRaw('id_praktek_baik_guru as id_berita, isi, judul, gambar, created_at as tanggal')
            ->where('id_praktek_baik_guru',$id)
			->with('praktek_baik_guru_file')
			->where('status', true)
			->first();
	}
}
