<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class AbsensiController extends Controller
{
	public function absenMasuk(Request $request) {
		$rules = [
			'latitude' => 'required',
			'longitude' => 'required',
		];
		$message = [
			'latitude.required' => 'Latitude tidak valid!',
			'longitude.required' => 'Longitude tidak valid!',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}
		
		if (!$guru = Guru::where('users_id',Auth::user()->id)->first()) {
			return ['status'=>'fail','message'=>'Anda tidak memiliki data guru aktif!'];
		}
		
		if ($absensi = Absensi::where('users_id',$guru->id_guru)->
			whereDate('tanggal_absen',date('Y-m-d'))->
			first()
		) {
			return ['status'=>'fail','message'=>'Anda telah melakukan absen hari ini pada pukul '.date('H:i:s',$absensi->absen_datang).'!'];
		}

		$absensi = new Absensi;
		$absensi->guru_id = $guru->id_guru;
		$absensi->users_id = Auth::user()->id;
		$absensi->tanggal_absen = date('Y-m-d');
		$absensi->absen_datang = date('Y-m-d H:i:s');
		$absensi->lokasi_datang = $request->latitude.','.$request->longitude;

		if (!$absensi->save()) {
			return ['status' => 'fail', 'message' => 'Gagal melakukan simpan absensi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan absensi!'];
	}

	public function absenPulang(Request $request) {
		$rules = [
			'latitude' => 'required',
			'longitude' => 'required',
		];
		$message = [
			'latitude.required' => 'Latitude tidak valid!',
			'longitude.required' => 'Longitude tidak valid!',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}
		
		if (!$guru = Guru::where('users_id',Auth::user()->id)->first()) {
			return ['status'=>'fail','message'=>'Anda tidak memiliki data guru aktif!'];
		}
		
		if (!$absensi = Absensi::where('users_id',Auth::user()->id)->
			whereDate('tanggal_absen',date('Y-m-d'))->
			first()
		) {
			return ['status'=>'fail','message'=>'Anda belum melakukan absen hari ini!'];
		}
		if ($absensi->absen_pulang) {
			return ['status'=>'fail','message'=>'Anda telah melakukan absen pulang hari ini pada pukul '.date('H:i:s',$absensi->absen_pulang).'!'];
		}

		$absensi->absen_pulang = date('Y-m-d H:i:s');
		$absensi->lokasi_pulang = $request->latitude.','.$request->longitude;

		if (!$absensi->save()) {
			return ['status' => 'fail', 'message' => 'Gagal melakukan simpan absensi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan absensi!'];
	}
}
