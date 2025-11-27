<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pertanyaan;
use App\Models\Soal;
use App\Models\TahunAjaran;
use App\Models\MasterJenisFile;
use Illuminate\Http\Request;
use App\Http\Requests\SoalRequest;
use Auth, Help, CLog, DB, DataTables, GRes;
use Illuminate\Support\Facades\Storage;

class SoalController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Soal';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$soal = Soal::orderBy('id_soal', 'DESC')
				->with('mata_pelajaran')
				->with('jenisFile')
				->with('guru')
				->when($request->id_mapel!='',function ($q) use ($request) {
					$q->where('mapel_id',$request->id_mapel);
				})
				->when($request->id_kelas!='',function ($q) use ($request) {
					$q->where('kelas_id',$request->id_kelas);
				})
				->when($request->id_tahun_ajaran!='',function ($q) use ($request) {
					$q->where('tahun_ajaran_id',$request->id_tahun_ajaran);
				})
				->get();
			return DataTables::of($soal)->addIndexColumn()->addColumn('tanggal', function ($row) {
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
				// $html = "<button onclick='previewSoal($row->id_soal)' class='btn ms-1 btn-primary p-2'><i class='bx bx-spreadsheet mx-1'></i></button>";
				$html = "<button onclick='tambahSoal($row->id_soal)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusSoal($row->id_soal)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'tanggal'])->toJson();
		}
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::get();
		return view('main.content.admin.soal.main',$data);
	}

	public function add(Request $request)
	{
		$user_id = Auth::user()->id;
		$data['kelas'] = Kelas::get();
		$data['tahunAjaran'] = TahunAjaran::get();
		// $data['mataPelajaran'] = MataPelajaran::whereHas('kelas_mapel', function ($q) use ($user_id) {
		// 	$q->whereHas('guru', function ($qq) use ($user_id) {
		// 		$qq->where('users_id', $user_id);
		// 	});
		// })->get();
		$data['jenis_file'] = MasterJenisFile::all();
		$data['mataPelajaran'] = MataPelajaran::all();
		$data['soal'] = Soal::where('id_soal', $request->id)->first();
		$content = view('main.content.admin.soal.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function createSoal(SoalRequest $request)
	{
		$filePath = null;
		$disk = 'public';

		try {
			if ($request->hasFile('soal_file')) {
				$file = $request->file('soal_file');
				
				$filePath = $file->store('soal', $disk);

				if (!$filePath) {
					return Help::resMsg("Gagal menyimpan file.", 500);
				}

				$request->merge([
					'file_soal' => $filePath
				]);
			}
		} catch (\Exception $e) {
			$logPayload['file'] = $e->getFile();
			$logPayload['message'] = "File upload failed: " . $e->getMessage();
			$logPayload['line'] = $e->getLine();
			CLog::catchError($request->merge(['log_payload' => $logPayload]));
			return Help::resMsg("Gagal memproses upload file.", 500);
		}

		DB::beginTransaction();
		try {
			if (!$soal = Soal::store($request)) {
				DB::rollback();
				if ($filePath) {
					Storage::disk($disk)->delete($filePath);
				}
				return Help::resMsg("Gagal menyimpan soal, coba beberapa saat lagi", 201);
			}
			if (!$pertanyaan = Pertanyaan::generatePertanyaan($soal)) {
				DB::rollback();
				if ($filePath) {
					Storage::disk($disk)->delete($filePath);
				}
				return Help::resMsg("Gagal menyimpan soal, coba beberapa saat lagi", 201);
			}
			DB::commit();
			return Help::resMsg("Berhasil menyimpan soal", 200);
		} catch (\Throwable $e) {
			DB::rollback();
			if ($filePath) {
				Storage::disk($disk)->delete($filePath);
			}
			$logPayload['file'] = $e->getFile();
			$logPayload['message'] = $e->getMessage();
			$logPayload['line'] = $e->getLine();
			CLog::catchError($request->merge(['log_payload' => $logPayload])); # Logging
			return Help::resMsg(null, 500);
		}
	}

	public function updateSoal(SoalRequest $request, $id)
	{
		$soal = Soal::findOrFail($id);
		$oldFilePath = $soal->file_soal;
		$newFilePath = null;
		$disk = 'public';

		try {
			if ($request->hasFile('soal_file')) {
				$file = $request->file('soal_file');
				$newFilePath = $file->store('soal', $disk);

				if (!$newFilePath) {
					return Help::resMsg("Gagal menyimpan file baru.", 500);
				}
				
				$request->merge(['file_soal' => $newFilePath]);
			}
		} catch (\Exception $e) {
			CLog::catchError($request->merge(['log_payload' => [
				'file' => $e->getFile(),
				'message' => "File upload failed: " . $e->getMessage(),
				'line' => $e->getLine()
			]]));
			return Help::resMsg("Gagal memproses upload file.", 500);
		}

		DB::beginTransaction();
		try {
			
			if (!Soal::modify($request, $soal)) {
				DB::rollback();
				if ($newFilePath) {
					Storage::disk($disk)->delete($newFilePath);
				}
				return Help::resMsg("Gagal mengupdate soal.", 500);
			}

			DB::commit();

			if ($newFilePath && $oldFilePath) {
				if (Storage::disk($disk)->exists($oldFilePath)) {
					Storage::disk($disk)->delete($oldFilePath);
				}
			}
			
			return Help::resMsg("Berhasil mengupdate soal", 200);

		} catch (\Throwable $e) {
			DB::rollback();
			if ($newFilePath) {
				Storage::disk($disk)->delete($newFilePath);
			}
			CLog::catchError($request->merge(['log_payload' => [
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'line' => $e->getLine()
			]]));
			return Help::resMsg(null, 500);
		}
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
		$content = view('main.content.admin.soal.preview',$data)->render();
		return ['status' => 'success', 'message' => 'Soal berhasil ditemukan', 'content' => $content];
	}

	public function hapusSoal(Request $request, $id)
	{
		$soal = Soal::findOrFail($id);
		$filePath = $soal->file_soal;
		$disk = 'public';

		DB::beginTransaction();
		try {
			$delete = $soal->delete();

			if (!$delete) {
				DB::rollback();
				return Help::resMsg("Gagal menghapus data soal.", 500);
			}

			DB::commit();
			
			if ($filePath && Storage::disk($disk)->exists($filePath)) {
				Storage::disk($disk)->delete($filePath);
			}

			return Help::resMsg("Berhasil menghapus soal", 200);

		} catch (\Throwable $e) {
			DB::rollback();
			
			CLog::catchError($request->merge(['log_payload' => [
				'file' => $e->getFile(),
				'message' => "Delete failed: " . $e->getMessage(),
				'line' => $e->getLine(),
				'id_soal' => $id
			]]));

			return Help::resMsg(null, 500);
		}
	}
}
