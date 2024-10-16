<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Auth;

class SoalRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::check();
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'judul_soal' => 'required',
			'kelas_id' => 'required',
			'tahun_ajaran_id' => 'required',
			'mapel_id' => 'required',
			'kkm' => 'required|min:0|max:100',
			'mulai_pengerjaan' => 'required',
			'selesai_pengerjaan' => 'required',
			'jumlah_soal' => 'required|min:1|max:300',
			'pendahuluan' => 'required',
			'jenis' => 'required|in:1,2',
			'durasi' => 'required',
			// 'status' => 'required',
		];
	}

	public function messages(): array
	{
		return [
			'judul_soal.required' => 'Judul Soal Wajib Diisi',
			'kelas_id.required' => 'Kelas Wajib Diisi',
			'tahun_ajaran_id.required' => 'Tahun Ajaran Wajib Diisi',
			'mapel_id.required' => 'Mata Pelajaran Wajib Diisi',
			'kkm.required' => 'KKM Wajib Diisi',
			'mulai_pengerjaan.required' => 'Tanggal Mulai Wajib Diisi',
			'selesai_pengerjaan.required' => 'Tanggal Selesai Wajib Diisi',
			'jumlah_soal.required' => 'Jumlah Soal Wajib Diisi',
			'jumlah_soal.min' => 'Minimal Jumlah Soal 1',
			'jumlah_soal.max' => 'Maksimal Jumlah Soal 300',
			'kkm.min' => 'Minimal KKM 0',
			'kkm.max' => 'Maksimal KKM 100',
			'pendahuluan.required' => 'Pendahuluan Wajib Diisi',
			'durasi.required' => 'Durasi Wajib Diisi',
			// 'status.required' => 'Status Wajib Diisi',
		];
	}

	public function failedValidation(Validator $validator)
	{
		throw new HttpResponseException(response()->json([
			'success' => false,
			'code' => 400,
			'message' => $validator->errors()->first(),
		]));
	}
}
