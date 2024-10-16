<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
	use HasFactory;
	protected $table = "siswas";
	protected $primaryKey = "id_siswa";

	public $timestamps = false;

	public static function store($request)
	{
		$siswa = new Siswa;
		$siswa->nama = $request->nama ? $request->nama : '';
		$siswa->tmp_lahir = isset($request->tmp_lahir) ? $request->tmp_lahir : '';
		$siswa->tgl_lahir = isset($request->tgl_lahir) ? date('Y-m-d',strtotime($request->tgl_lahir)) : null;
		$siswa->gender = isset($request->gender) ? $request->gender : '';
		$siswa->nama_ayah = isset($request->nama_ayah) ? $request->nama_ayah : '';
		$siswa->nama_ibu = isset($request->nama_ibu) ? $request->nama_ibu : '';
		$siswa->alamat = isset($request->alamat) ? $request->alamat : '';
		$siswa->no_tlp = isset($request->no_tlp) ? $request->no_tlp : '';
		$siswa->thn_masuk = isset($request->thn_masuk) ? $request->thn_masuk : '';
		$siswa->foto = '';
		$siswa->users_id = $request->users_id;
		$siswa->status = 'Siswa Aktif';
		$siswa->nisn = isset($request->nisn) ? $request->nisn : '';
		return $siswa->save();
	}

	public function kelas_siswa()
	{
		return $this->hasOne(KelasSiswa::class, 'siswa_id', 'id_siswa');
	}

	public function user()
	{
		return $this->belongsTo(Users::class, 'users_id', 'id');
	}

	public function jawaban_siswa() {
		return $this->hasMany(JawabanSiswa::class,'siswa_id','id_siswa');
	}
}
