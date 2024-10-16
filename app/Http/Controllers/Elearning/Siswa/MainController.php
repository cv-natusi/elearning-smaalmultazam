<?php

namespace App\Http\Controllers\Elearning\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

# Models
use App\Models\JawabanSiswa;
use App\Models\Pertanyaan;
use App\Models\Siswa;
use App\Models\Soal;
use Illuminate\Support\Facades\Auth;
use Help;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller{
	public function kerjakan(Request $request){
        $data['bio'] = Siswa::join('users','siswas.users_id','users.id')->where('users.no_induk',Auth::user()->no_induk)->first();
        $data['soal'] = Soal::find($request->ids);
        $data['pertanyaans'] = Pertanyaan::selectRaw("id_pertanyaan,nomor")->where('soal_id', $request->ids)->get();
        $data['id_jawaban_siswa'] = JawabanSiswa::selectRaw("id_jawaban_siswa")->where('id_jawaban_siswa', $request->idjs)->first()->id_jawaban_siswa;
        $data['waktu_mulai'] = date(
            'Y-m-d H:i:s',
            strtotime(JawabanSiswa::selectRaw("waktu_mulai")->where('id_jawaban_siswa', $request->idjs)->first()->waktu_mulai)
        );
        $data['batas_waktu'] = date(
            'Y-m-d H:i:s',
            strtotime(
                $data['waktu_mulai'].'+'.$data['soal']->durasi.' minute'
            )
        );
		return view('main.content.siswa.soal.lembar-kerja',$data);
	}

    public function contentSoal(Request $request){
        $data['data'] = Pertanyaan::selectRaw("
                            id_pertanyaan,
                            pertanyaan_text,
                            nomor
                        ")->
                        with(['pilihan_jawaban', 'pertanyaan_file'])->
                        where('soal_id', $request->ids)->
                        where('nomor', $request->nU)->
                        first();
        $data['nU'] = $request->nU;
        $data['ids'] = $request->ids;
        $data['idjs'] = $request->idjs;

        $jawaban_siswa = JawabanSiswa::selectRaw("id_jawaban_siswa,jawaban_siswa")->where('id_jawaban_siswa', $request->idjs)->first();
        $arrJs = explode('-',$jawaban_siswa->jawaban_siswa);
        $strJs = $arrJs[$request->nU-1];
        $data['strJs'] = $strJs;
        $data['arrJs'] = $arrJs;

        $lastNumber = null;
        $nomor = Pertanyaan::selectRaw("id_pertanyaan,nomor")->where('soal_id', $request->ids)->get();
        foreach($nomor as $no){
            $lastNumber = $no->nomor;
        }
        $next = (int)$request->nU+1 <= (int)$lastNumber ? $request->nU+1 : $lastNumber;
        $prev = (int)$request->nU-1 > 0 ? $request->nU-1 : null;

        $content = view('main.content.siswa.soal.soal',$data)->render();
		return ['status' => 'success', 'message' => 'Soal berhasil ditemukan', 'content' => $content, 'current' => (int)$request->nU, 'next'=> $next, 'prev' => $prev, 'lastNumber' => $lastNumber, 'js' => $strJs, 'arrJs' => $arrJs ];
	}

	public function store(Request $request){
		if(!($soal = Soal::where('id_soal',$request->ids)->first())){
			return response()->json([
				'metadata' => [
					'code' => 204,
					'message' => 'Soal tidak ditemukan'
				],
			]);
		}
		if($jawaban = JawabanSiswa::where('soal_id',$request->ids)->first()){ # Jawaban sudah dibuat

            if($request->jawaban){ # simpan jawaban siswa
                $jawaban_siswa = JawabanSiswa::find($request->idjs);
                $arrJs = explode('-',$jawaban_siswa->jawaban_siswa);
                foreach ($arrJs as $key => $value) {
                    if($request->nU-1 == $key){
                        $arrJs[$key] = $request->jawaban;
                    }
                }
                $strJs = implode('-',$arrJs);
                $jawaban_siswa->jawaban_siswa = $strJs;
                $jawaban_siswa->save();

                return ['status' => 'success', 'message' => 'oke', 'terpilih' => $request->jawaban, 'arrJs' => $arrJs];
            }

			return response()->json([
				'metadata' => [
					'code' => 200,
					'message' => 'Data berhasil ditemukan'
				],
				'response' => $jawaban
			]);
		}

        $jawaban_siswa_awal = null;
        $jumlah_soal = Pertanyaan::selectRaw("id_pertanyaan,nomor")->where('soal_id', $request->ids)->get();
        for($i=1; $i <= count($jumlah_soal); $i++){
            $jawaban_siswa_awal .= '0';
            if($i != count($jumlah_soal)){
                $jawaban_siswa_awal .= '-';
            }
        }
        $siswa = Siswa::select('id_siswa')->join('users','siswas.users_id','users.id')->where('users.no_induk',Auth::user()->no_induk)->first();

		$jawaban = new JawabanSiswa;
		$jawaban->siswa_id = $siswa->id_siswa;
		$jawaban->soal_id = $request->ids;
		$jawaban->jawaban_siswa = $jawaban_siswa_awal;
        $jawaban->waktu_mulai = date('Y-m-d H:i:s');
		$jawaban->save();
		return response()->json([
			'metadata' => [
				'code' => 200,
				'message' => 'Data berhasil ditemukan'
			],
			'response' => $jawaban
		]);
	}

	public function selesaikan(Request $request) {
		$params = [
			'id_soal' => 'required',
		];
		$message = [
			'id_soal.required' => 'ID Soal Tidak Diketahui!',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}

		$user_id = Auth::user()->id;
		if (!$jawabanSiswa=JawabanSiswa::where('soal_id',$request->id_soal)->
				whereHas('siswa',function ($q) use($user_id) {
					$q->where('users_id',$user_id);
				})->
				first()) {
			return ['status' => 'fail', 'message' => 'Anda belum mengerjakan soal!'];
		}
		if ($jawabanSiswa->waktu_mulai=='') {
			return ['status' => 'fail', 'message' => 'Anda belum mengerjakan soal!'];
		}
		if ($jawabanSiswa->nilai!=null) {
			return ['status' => 'fail', 'message' => 'Nilai telah keluar!'];
		}
		if (!$kunciJawaban=Pertanyaan::getPertanyaanKunciJawaban($request)) {
			return ['status' => 'fail', 'message' => 'Pertanyaan tidak ditemukan!'];
		}
		$jawabanSiswa->nilai=Help::hitungNilai($jawabanSiswa->jawaban_siswa,$kunciJawaban);
		$jawabanSiswa->waktu_selesai=date('Y-m-d H:i:s');
		if (!$jawabanSiswa->save()) {
			return ['status' => 'fail', 'message' => 'Gagal menyimpan nilai, coba lagi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan pengerjaan!'];
	}
}
