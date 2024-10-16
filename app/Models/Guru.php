<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
	use HasFactory;
	protected $table = "gurus";
	protected $primaryKey = "id_guru";

	public $timestamps = false;

	public function users()
	{
		return $this->belongsTo(Users::class, 'users_id', 'id');
	}

	public function kelas_mapel()
	{
		return $this->hasMany(KelasMapel::class, 'guru_id', 'id_guru');
	}

	public function kelas()
	{
		return $this->hasMany(Kelas::class, 'guru_id', 'id_guru');
	}

	public function soal()
	{
		return $this->hasMany(Soal::class, 'user_id', 'users_id');
	}

	public function materi_share()
	{
		return $this->hasMany(MateriShare::class, 'user_id', 'users_id');
	}

	public function spreadsheet_share()
	{
		return $this->hasMany(SpreadsheetShare::class, 'guru_id', 'id_guru');
	}

	public function absensi()
	{
		return $this->hasMany(Absensi::class, 'guru_id', 'id_guru');
	}

	public static function store($request)
	{
		$guru = new Guru;
		$guru->nama = $request->nama ? $request->nama : '';
		$guru->tmp_lahir = isset($request->tmp_lahir) ? $request->tmp_lahir : '';
		$guru->tgl_lahir = isset($request->tgl_lahir) ? date('Y-m-d',strtotime($request->tgl_lahir)) : null;
		$guru->gender = isset($request->gender) ? $request->gender : '';
		$guru->alamat = isset($request->alamat) ? $request->alamat : '';
		$guru->foto = isset($request->foto) ? $request->foto : '';
		$guru->no_tlp = isset($request->no_tlp) ? $request->no_tlp : '';
		$guru->users_id = $request->users_id;
		$guru->nip = isset($request->nip) ? $request->nip : '';
		return $guru->save();
	}
}
