<?php

namespace App\Helpers;

use App\Models\Website\Identitas;

class Helpers
{
	public static function resHttp($data = [])
	{
		$keyData = ['message', 'code', 'response'];
		$arr = [];
		foreach ($keyData as $key => $val) {
			$arr[$val] = isset($data[$val]) ? $data[$val] : ( # Cek key, apakah sudah di set
				$val == 'code' ? 500 : ($val == 'message' ? '-' : []));
		}
		$code = $arr['code'];
		$msg = $arr['message'];

		$metadata = [
			'code'    => $arr['code'],
			'message' => $arr['message'],
		];
		$payload['metadata'] = $metadata;
		$payload['response'] = $arr['response'];
		return response()->json($payload, $code);
	}

	public static function resMsg($message = 'Terjadi Kesalahan Sistem', $code = 500)
	{
		return response()->json(['message' => $message, 'code' => $code], $code);
	}

	public static function mainSetting()
	{
		$data['identitas'] = Identitas::first();
		return $data;
	}

	public static function hitungNilai(String $jawabanSiswa, $kunciJawaban = [])
	{
		$jawabanSiswaArr = explode('-', $jawabanSiswa);
		$nilai = 0;
		foreach ($jawabanSiswaArr as $key => $value) {
			foreach ($kunciJawaban as $key2 => $value2) {
				if ($key + 1 == $value2->nomor && $value2->prefix_pilihan==$value) {
					$nilai+=$value2->poin?$value2->poin:0;
				}
			}
		}
		return $nilai;
	}
}
