<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
	use HasFactory;

	protected $table = "users";

	public function siswa()
	{
		return $this->hasOne(Siswa::class, 'users_id', 'id');
	}

	public function guru()
	{
		return $this->hasOne(Guru::class, 'users_id', 'id');
	}

	public function jurnal_guru()
	{
		return $this->hasMany(JurnalGuru::class, 'user_id', 'id');
	}

	public function absensi()
	{
		return $this->hasMany(Absensi::class, 'users_id', 'id');
	}

	public static function storeGuru($request)
	{
		$user = new Users;
		$user->email = $request->email;
		$user->no_induk = $request->no_induk;
		$user->password = bcrypt($request->email);
		$user->level_user = 3;
		$user->active = 1;

		return $user->save() ? $user : false;
	}

	public static function storeSiswa($request)
	{
		$user = new Users;
		$user->email = $request->no_induk;
		$user->no_induk = $request->no_induk;
		$user->password = bcrypt($request->no_induk);
		$user->level_user = 4;
		$user->active = 1;

		return $user->save() ? $user : false;
	}

	public function getFotoAttribute()
	{
		if ($this->attributes['level_user'] == '4') { #siswa
			return $this->siswa->foto ? 'uploads/siswa/' . $this->siswa->foto : null;
			// return $this->siswa->foto;
		} else if ($this->attributes['level_user'] == '3') { #guru
			return 'uploads/guru/' . $this->guru->foto;
		} else {
			return null;
		}
	}

	public function getPiketAttribute()
	{
		if ($this->guru) {
			return $this->guru->is_piket;
		} else {
			return false;
		}
	}
}
