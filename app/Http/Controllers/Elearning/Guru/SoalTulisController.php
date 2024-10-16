<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Http\Requests\SoalRequest;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pertanyaan;
use App\Models\PilihanJawaban;
use App\Models\Soal;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Libraries\compressFile;
use App\Models\PertanyaanFile;
use Auth, Help, CLog, DB, DataTables, GRes;

class SoalTulisController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Soal';
	}

	public function main(Request $request)
	{
		$user_id = Auth::user()->id;
		if ($request->ajax()) {
			$data = Soal::orderBy('id_soal', 'DESC')
				->where('user_id', $user_id)
				->with('mata_pelajaran')
				->with('guru')
				->when($request->id_semester!='',function ($q) use ($request) {
					$q->where('semester',$request->id_semester);
				})
				->when($request->id_kelas!='',function ($q) use ($request) {
					$q->where('kelas_id',$request->id_kelas);
				})
				->when($request->id_tahun_ajaran!='',function ($q) use ($request) {
					$q->where('tahun_ajaran_id',$request->id_tahun_ajaran);
				})
				->get();
			return DataTables::of($data)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('H:i:s d F Y', strtotime($row->mulai_pengerjaan)) . '<br>S/D<br>' . date('H:i:s d F Y', strtotime($row->selesai_pengerjaan));
			})->addColumn('nama_mapel', function ($row) {
				$mapel = '-';
				if ($row->mata_pelajaran) {
					if (strlen($row->mata_pelajaran->nama_mapel) > 20) {
						$mapel = substr($row->mata_pelajaran->nama_mapel, 0, 20) . '...';
					} else {
						$mapel = $row->mata_pelajaran->nama_mapel;
					}
				}
				return $mapel;
			})->addColumn('judul_soal', function ($row) {
				$judul = '';
				if (strlen($row->judul_soal) > 20) {
					$judul .= substr($row->judul_soal, 0, 20) . '...';
				} else {
					$judul .= $row->judul_soal ? $row->judul_soal : '-';
				}
				return $judul;
			})->addColumn('nama_guru', function ($row) {
				return $row->guru ? $row->guru->nama : '-';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='previewSoal($row->id_soal)' class='btn btn-primary p-2'><i class='bx bx-spreadsheet mx-1'></i></button>";
				if ($row->tampilkan_nilai) {
					$html .= "<button onclick='hiddenNilai($row->id_soal)' class='btn ms-1 btn-secondary p-2'><i class='bx bx-low-vision mx-1'></i></button>";
				} else {
					$html .= "<button onclick='hiddenNilai($row->id_soal)' class='btn ms-1 btn-dark btn-purple p-2'><i class='bx bx-low-vision mx-1'></i></button>";
				}
				$html .= "<button onclick='tambahSoal($row->id_soal)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusSoal($row->id_soal)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'tanggal'])->toJson();
		}
		$data['kelas'] = Kelas::get();
		$data['tahunAjaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::whereHas('kelas_mapel', function ($q) use ($user_id) {
			$q->whereHas('guru', function ($qq) use ($user_id) {
				$qq->where('users_id', $user_id);
			});
		})->get();
		return view('main.content.guru.soal-materi.main',$data);
	}

	public function add(Request $request)
	{
		$user_id = Auth::user()->id;
		$data['kelas'] = Kelas::get();
		$data['tahunAjaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::whereHas('kelas_mapel', function ($q) use ($user_id) {
			$q->whereHas('guru', function ($qq) use ($user_id) {
				$qq->where('users_id', $user_id);
			});
		})->get();
		$data['soal'] = Soal::where('id_soal', $request->id)->first();
		$content = view('main.content.guru.soal-materi.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function createSoal(SoalRequest $request)
	{
		DB::beginTransaction();
		try {
			if (!$soal = Soal::store($request)) {
				DB::rollback();
				return Help::resMsg("Gagal menyimpan soal, coba beberapa saat lagi", 201);
			}
			if (!$pertanyaan = Pertanyaan::generatePertanyaan($soal)) {
				DB::rollback();
				return Help::resMsg("Gagal menyimpan soal, coba beberapa saat lagi", 201);
			}
			DB::commit();
			return Help::resMsg("Berhasil menyimpan soal", 200);
		} catch (\Throwable $e) {
			DB::rollback();
			$logPayload['file'] = $e->getFile();
			$logPayload['message'] = $e->getMessage();
			$logPayload['line'] = $e->getLine();
			CLog::catchError($request->merge(['log_payload' => $logPayload])); # Logging
			return Help::resMsg(null, 500);
		}
	}

	public function pertanyaanForm(Request $request)
	{
		$params = [
			'id_soal' => 'required',
		];
		$message = [
			'id_soal.required' => 'ID harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}
		if (!$data['soal'] = Soal::where('id_soal', $request->id_soal)->first()) {
			return ['status' => 'fail', 'message' => 'Soal tidak ditemukan'];
		}
		$data['pertanyaans'] = Pertanyaan::getPertanyaans($request);
		if (isset($request->id_pertanyaan)) {
			if (!$data['pertanyaan'] = Pertanyaan::where('id_pertanyaan', $request->id_pertanyaan)->with('pertanyaan_file')->first()) {
				return ['status' => 'fail', 'message' => 'Pertanyaan tidak ditemukan'];
			}
			$data['pilihan_jawaban'] = PilihanJawaban::where('pertanyaan_id', $data['pertanyaan']->id_pertanyaan)->get();
			return ['status' => 'success', 'message' => 'Pertanyaan berhasil ditemukan', 'data' => $data];
		}
		$content = view('main.content.guru.soal-materi.lembar-soal', $data)->render();
		return ['status' => 'success', 'message' => 'Soal berhasil ditemukan', 'content' => $content];
	}

	public function pertanyaanStore(Request $request)
	{
		$params = [
			'id_pertanyaan' => 'required',
			'id_soal' => 'required',
		];
		$message = [
			'id_pertanyaan.required' => 'ID Pertanyaan harus diisi',
			'id_soal.required' => 'ID Soal harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}
		$alphabet = range('A', 'Z');
		DB::beginTransaction();
		try {
			if (!$pertanyaan = Pertanyaan::where('id_pertanyaan', $request->id_pertanyaan)->first()) {
				return ['status' => 'fail', 'message' => 'Pertanyaan tidak dapat ditemukan'];
			}
			$pertanyaan->pertanyaan_text = $request->pertanyaan_text;
			$pertanyaan->poin = $request->poin_pertanyaan;
			foreach ($request->pilihan_jawaban as $key => $value) {
				$pilihan_jawaban = (object) $value;
				if (isset($pilihan_jawaban->id_pilihan_jawaban)) {
					if (!$pilihan_jawaban_new = PilihanJawaban::where('id_pilihan_jawaban', $pilihan_jawaban->id_pilihan_jawaban)->first()) {
						$pilihan_jawaban_new = new PilihanJawaban;
						$pilihan_jawaban_new->pertanyaan_id = $request->id_pertanyaan;
					}
				} else {
					$pilihan_jawaban_new = new PilihanJawaban;
					$pilihan_jawaban_new->pertanyaan_id = $request->id_pertanyaan;
				}
				$pilihan_jawaban_new->benar = isset($request->benar) ? ($request->benar == $key) : false;
				$pilihan_jawaban_new->pilihan_text = $pilihan_jawaban->pilihan_text;
				$pilihan_jawaban_new->prefix_pilihan = $alphabet[$key];
				if (!empty($pilihan_jawaban->file)) {
					if ($pilihan_jawaban_new->nama_file != '') {
						if (file_exists('uploads/elearning/pilihan_jawaban/' . $pilihan_jawaban_new->nama_file)) {
							unlink('uploads/elearning/pilihan_jawaban/' . $pilihan_jawaban_new->nama_file);
						}
					}
					$ukuranFile1 = filesize($pilihan_jawaban->file);
					if ($ukuranFile1 <= 500000) {
						$ext_foto1 = $pilihan_jawaban->file->getClientOriginalExtension();
						$filename1 = "Pilihan_jawaban" . date('Ymd-His') . "." . $ext_foto1;
						$temp_foto1 = 'uploads/elearning/pilihan_jawaban/';
						$proses1 = $pilihan_jawaban->file->move($temp_foto1, $filename1);
						$pilihan_jawaban_new->nama_file = $filename1;
						$pilihan_jawaban_new->type_file = 'image';
					} else {
						$file1 = $_FILES['gambar']['name'];
						$ext_foto1 = $pilihan_jawaban->file->getClientOriginalExtension();
						if (!empty($file1)) {
							$direktori1 = 'uploads/elearning/pilihan_jawaban/'; //tempat upload foto
							$name1 = 'gambar'; //name pada input type file
							$namaBaru1 = "Pilihan_jawaban" . date('Ymd-His'); //name pada input type file
							$quality1 = 50; //konversi kualitas gambar dalam satuan %
							$upload1 = compressFile::UploadCompress($namaBaru1, $name1, $direktori1, $quality1);
						}
						$pilihan_jawaban_new->nama_file = $namaBaru1 . "." . $ext_foto1;
						$pilihan_jawaban_new->type_file = 'image';
					}
				}
				if (!$pilihan_jawaban_new->save()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan'];
				}
			}
			if (!$pertanyaan->save()) {
				DB::rollback();
				return ['status' => 'fail', 'message' => 'Gagal menyimpan'];
			}
			DB::commit();
			$data['pertanyaans'] = Pertanyaan::getPertanyaans($request);
			return ['status' => 'success', 'message' => 'Tersimpan', 'data' => $data];
		} catch (\Throwable $e) {
			DB::rollback();
			$request->merge([
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'line' => $e->getLine(),
			]);
			CLog::catchError($request);
			return Help::resMsg(null, 500);
		}
	}

	public function getPertanyaanFile(Request $request)
	{
		$data['id_pertanyaan'] = $request->id_pertanyaan;
		$data['pertanyaan_file'] = PertanyaanFile::where('pertanyaan_id', $request->id_pertanyaan)->get();
		// return $data;
		$content = view('main.content.guru.soal-materi.modal-file', $data)->render();
		return ['status' => 'success', 'message' => 'File berhasil ditemukan', 'content' => $content];
	}

	public function storePertanyaanFile(Request $request)
	{
		$params = [
			'id_pertanyaan' => 'required',
		];
		$message = [
			'id_pertanyaan.required' => 'ID Pertanyaan harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}
		try {
			DB::beginTransaction();
			if (isset($request->file_gambar)) {
				if (!$pertanyaan_file = PertanyaanFile::where('pertanyaan_id', $request->id_pertanyaan)->where('type_file', 'gambar')->first()) {
					$pertanyaan_file = new PertanyaanFile;
					$pertanyaan_file->pertanyaan_id = $request->id_pertanyaan;
				}
				if ($pertanyaan_file->file != '') {
					if (file_exists('uploads/elearning/pertanyaan/' . $pertanyaan_file->file)) {
						unlink('uploads/elearning/pertanyaan/' . $pertanyaan_file->file);
					}
				}
				$ukuranFile1 = filesize($request->file_gambar);
				if ($ukuranFile1 <= 500000) {
					$ext_foto1 = $request->file_gambar->getClientOriginalExtension();
					$filename1 = "soal_gambar" . date('Ymd-His') . "." . $ext_foto1;
					$temp_foto1 = 'uploads/elearning/pertanyaan/';
					$proses1 = $request->file_gambar->move($temp_foto1, $filename1);
					$pertanyaan_file->file = $filename1;
					$pertanyaan_file->type_file = 'gambar';
				} else {
					$file1 = $_FILES['file_gambar']['name'];
					$ext_foto1 = $request->file_gambar->getClientOriginalExtension();
					if (!empty($file1)) {
						$direktori1 = 'uploads/elearning/pertanyaan/'; //tempat upload foto
						$name1 = 'file_gambar'; //name pada input type file
						$namaBaru1 = "soal_gambar" . date('Ymd-His'); //name pada input type file
						$quality1 = 50; //konversi kualitas gambar dalam satuan %
						$upload1 = compressFile::UploadCompress($namaBaru1, $name1, $direktori1, $quality1);
					}
					$pertanyaan_file->file = $namaBaru1 . "." . $ext_foto1;
					$pertanyaan_file->type_file = 'gambar';
				}
				if (!$pertanyaan_file->save()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan'];
				}
			}
			if (isset($request->file_audio)) {
				if (!$pertanyaan_file_audio = PertanyaanFile::where('pertanyaan_id', $request->id_pertanyaan)->where('type_file', 'audio')->first()) {
					$pertanyaan_file_audio = new PertanyaanFile;
					$pertanyaan_file_audio->pertanyaan_id = $request->id_pertanyaan;
				}
				if ($pertanyaan_file_audio->file != '') {
					if (file_exists('uploads/elearning/pertanyaan/' . $pertanyaan_file_audio->file)) {
						unlink('uploads/elearning/pertanyaan/' . $pertanyaan_file_audio->file);
					}
				}
				$ukuranFile1 = filesize($request->file_audio);
				if ($ukuranFile1 <= 5000000) {
					$ext_foto1 = $request->file_audio->getClientOriginalExtension();
					$filename1 = "soal_audio" . date('Ymd-His') . "." . $ext_foto1;
					$temp_foto1 = 'uploads/elearning/pertanyaan/';
					$proses1 = $request->file_audio->move($temp_foto1, $filename1);
					$pertanyaan_file_audio->file = $filename1;
					$pertanyaan_file_audio->type_file = 'audio';
				} else {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan, Maksimal audio size adalah 5 MB'];
				}
				if (!$pertanyaan_file_audio->save()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan'];
				}
			}
			if (isset($request->file_video)) {
				if (!$pertanyaan_file_video = PertanyaanFile::where('pertanyaan_id', $request->id_pertanyaan)->where('type_file', 'video')->first()) {
					$pertanyaan_file_video = new PertanyaanFile;
					$pertanyaan_file_video->pertanyaan_id = $request->id_pertanyaan;
				}
				$pertanyaan_file_video->file = $request->file_video;
				$pertanyaan_file_video->type_file = 'video';
				if (!$pertanyaan_file_video->save()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan'];
				}
			}
			if (isset($request->file_link)) {
				if (!$pertanyaan_file_link = PertanyaanFile::where('pertanyaan_id', $request->id_pertanyaan)->where('type_file', 'link')->first()) {
					$pertanyaan_file_link = new PertanyaanFile;
					$pertanyaan_file_link->pertanyaan_id = $request->id_pertanyaan;
				}
				$pertanyaan_file_link->file = $request->file_link;
				$pertanyaan_file_link->type_file = 'link';
				if (!$pertanyaan_file_link->save()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan'];
				}
			}
			DB::commit();
			return ['status' => 'success', 'message' => 'Berhasil menyimpan'];
		} catch (\Throwable $e) {
			DB::rollback();
			$request->merge([
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'line' => $e->getLine(),
			]);
			CLog::catchError($request);
			return Help::resMsg(null, 500);
		}
	}

	public function showNilai(Request $request)
	{
		$rules = [
			'id' => 'required',
		];
		$message = [
			'id.required' => 'Id Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);

		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		$soal = Soal::find($request->id);
		$soal->tampilkan_nilai = !$soal->tampilkan_nilai;

		if (!$soal->save()) {
			return response()->json(['message' => 'Gagal'], 201);
		}
		if ($soal->tampilkan_nilai) {
			return Help::resMsg('Nilai Diaktifkan', 200);
		}
		return Help::resMsg('Nilai Dinonaktifkan', 200);
	}

	public function preview(Request $request) {
		$data['soal'] = Soal::where('id_soal',$request->id)->first();
		$data['pertanyaan'] = Pertanyaan::selectRaw("
				id_pertanyaan,
				pertanyaan_text,
				nomor
			")->
			with(['pilihan_jawaban', 'pertanyaan_file'])->
			where('soal_id',$request->id)->
			get();
		if (!$data['soal']||!$data['pertanyaan']) {
			return ['status' => 'fail', 'message' => 'Soal tidak ditemukan'];
		}
		$content = view('main.content.guru.soal-materi.preview',$data)->render();
		return ['status' => 'success', 'message' => 'Soal berhasil ditemukan', 'content' => $content];
	}
}
