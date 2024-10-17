<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Http\Libraries\compressFile;
use App\Models\PraktekBaikGuru;
use App\Models\PraktekBaikGuruFile;
use App\Models\PraktekBaikGuruGambar;
use Illuminate\Http\Request;
use DataTables, Auth, Help, DB;
use Illuminate\Support\Facades\Validator;

class PraktekBaikGuruController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Paktek Baik Guru';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$praktek = PraktekBaikGuru::orderBy('id_praktek_baik_guru', 'DESC')
				->where('user_id', Auth::user()->id)
				->get();
			return DataTables::of($praktek)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('Y F d H:i:s', strtotime($row->created_at));
			})->addColumn('judul', function ($row) {
				$mapel = '';
				if (strlen($row->judul) > 20) {
					$mapel = substr($row->judul, 0, 20) . '...';
				} else {
					$mapel = $row->judul;
				}
				return $mapel;
			})->editColumn('status', function ($row) {
				if ($row->status) {
					return 'aktif';
				} else {
					return 'tidak aktif';
				}
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahBerita($row->id_praktek_baik_guru)' class='btn btn-dark btn-purple p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				if ($row->status) {
					$html .= "<button onclick='aktifBerita($row->id_praktek_baik_guru)' class='btn ms-1 btn-secondary p-2'><i class='bx bx-power-off mx-1'></i></button>";
				} else {
					$html .= "<button onclick='aktifBerita($row->id_praktek_baik_guru)' class='btn ms-1 btn-primary p-2'><i class='bx bx-power-off mx-1'></i></button>";
				}
				$html .= "<button onclick='hapusBerita($row->id_praktek_baik_guru)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.guru.praktek-baik-guru.main', $data);
	}

	public function add(Request $request)
	{
		$data = $this->data;
		$data['praktek'] = PraktekBaikGuru::with('praktek_baik_guru_file')->find($request->id);
		$content = view('main.content.guru.praktek-baik-guru.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$params = [
			'judul' => 'required',
			'status' => 'required',
			'isi' => 'required',
			// 'gambar' => 'required_without:id'
		];
		$message = [
			'judul.required' => 'Judul harus diisi',
			'status.required' => 'Status harus diisi',
			'isi.required' => 'Isi tidak boleh kosong',
			// 'gambar.required_without' => 'Gambar Wajib Diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}

		DB::beginTransaction();
		try {
			if (empty($request->id)) {
				$praktek = new PraktekBaikGuru;
			} else {
				$praktek = PraktekBaikGuru::find($request->id);
			}
			$praktek->user_id = Auth::user()->id;
			$praktek->judul = $request->judul;
			$praktek->isi = $request->isi;
			$praktek->status = $request->status;
			$foto = date('YmdHis');
			if (!empty($request->id)) {
				$idFile = !empty($request->id_file)?$request->id_file:[];
				$pbgFile = PraktekBaikGuruFile::where('praktek_baik_guru_id',$request->id)->whereNotIn('id_praktek_baik_guru_file',$idFile);
				$pbgFileGet = $pbgFile->get();
				$pbgFileDelete = $pbgFile->delete();
			}
			$praktek->save();
			if ($praktek) {
				if (!empty($request->file)) {
					foreach ($request->file as $key => $value) {
						$praktekFile = new PraktekBaikGuruFile;
						// $ukuranFile1 = filesize($value);
						$ext_file = $value->getClientOriginalExtension();
						$nama_file = $value->getClientOriginalName();
						$filename1 = "Praktek" . date('Ymd-His') . "_" . $key . "." . $ext_file;
						$temp_foto1 = 'uploads/praktek/';
						$proses1 = $value->move($temp_foto1, $filename1);
						$praktekFile->praktek_baik_guru_id = $praktek->id_praktek_baik_guru;
						$praktekFile->original_name = $nama_file;
						$praktekFile->file_name = $filename1;
						if (!$praktekFile->save()) {
							DB::rollBack();
							return ['code' => 201, 'status' => 'error', 'Gagal.'];
						}
					}
				}
                if(!empty($request->file_gambar)) {
                    $praktekGambar = new PraktekBaikGuruGambar;
                    $fileGambar = $request->file_gambar;
                    // $ukuranFile1 = filesize($value);
                    $ext_file2 = $fileGambar->getClientOriginalExtension();
                    $nama_file2 = $fileGambar->getClientOriginalName();
                    $filenameGambar = "Praktek" . date('Ymd-His') . "_" . "." . $ext_file2;
                    $temp_foto1 = 'uploads/praktek/gambar/';
                    $proses1 = $fileGambar->move($temp_foto1, $filenameGambar);
                    $praktekGambar->praktek_baik_guru_id = $praktek->id_praktek_baik_guru;
                    $praktekGambar->original_name = $nama_file2;
                    $praktekGambar->file_name = $filenameGambar;
                    if (!$praktekGambar->save()) {
                        DB::rollBack();
                        return ['code' => 201, 'status' => 'error', 'Gagal.'];
                    }
                }
				if (!empty($request->id)) {
					foreach ($pbgFileGet as $key => $value) {
						if (file_exists('uploads/praktek/' . $value->file_name)) {
							unlink('uploads/praktek/' . $value->file_name);
						}
					}
				}
				DB::commit();
				return ['code' => 200, 'status' => 'success', 'Berhasil.'];
			} else {
				DB::rollBack();
				return ['code' => 201, 'status' => 'error', 'Gagal.'];
			}
		} catch (\Throwable $th) {
			DB::rollBack();
			\Log::info(json_encode($th,JSON_PRETTY_PRINT));
            return response()->json(['message' => $th->getMessage()]);
			// return response('Terjadi kesalahan sistem',500);
		}

	}

	public function delete(Request $request)
	{
		$data = PraktekBaikGuru::where('id_praktek_baik_guru', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}

	public function aktif(Request $request)
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

		$berita = PraktekBaikGuru::find($request->id);
		$berita->status = !$berita->status;

		if (!$berita->save()) {
			return response()->json(['message' => 'Gagal'], 201);
		}
		if ($berita->status) {
			return Help::resMsg('Berita Berhasil Diaktifkan', 200);
		}
		return Help::resMsg('Berita Berhasil Dinonaktifkan', 200);
	}

	public function downloadFile($id)
	{
		if (!$file = PraktekBaikGuruFile::find($id)) {
			return ['status' => 'fail', 'message' => 'File tidak ditemukan!'];
		}
		if (!file_exists('uploads/praktek/' . $file->file_name)) {
			return ['status' => 'fail', 'message' => 'File tidak ditemukan!'];
		}
		$fileMateri = public_path("uploads/praktek/$file->file_name");
		return response()->download($fileMateri,$file->original_name);
	}
}
