<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

# Models
use App\Models\MateriShare;
use App\Models\TahunAjaran;
use App\Models\MataPelajaran;

# Helpers
use Auth, DataTables;
use Illuminate\Support\Facades\Validator;

class MateriController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Materi';
	}

	public function main(Request $request)
	{
		$user_id = Auth::user()->id;
		if ($request->ajax()) {
			$data = MateriShare::orderBy('tanggal_upload', 'DESC')
				->where('user_id', $user_id)
				->with('mata_pelajaran')
				->has('mata_pelajaran')
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
				return date('Y F d H:i:s', strtotime($row->tanggal_upload));
			})->addColumn('nama_mapel', function ($row) {
				$mapel = '';
				if (strlen($row->mata_pelajaran->nama_mapel) > 20) {
					$mapel = substr($row->mata_pelajaran->nama_mapel, 0, 20) . '...';
				} else {
					$mapel = $row->mata_pelajaran->nama_mapel;
				}
				return $mapel;
			})->addColumn('judul_materi', function ($row) {
				$judul = '';
				if (strlen($row->judul) > 20) {
					$judul = substr($row->judul, 0, 20) . '...';
				} else {
					$judul = $row->judul;
				}
				return $judul;
			})->addColumn('actions', function ($row) {
				$urlMateri = url('uploads/materi');
				$html = "<a href='$urlMateri/$row->file_materi' target='_blank' class='btn btn-dark btn-purple p-2'><i class='bx bx-search-alt-2 mx-1'></i></a>";
				$html .= "<button onclick='tambahMateri($row->id_materi)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusMateri($row->id_materi)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		$data['kelas'] = Kelas::get();
		$data['tahunAjaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::whereHas('kelas_mapel', function ($q) use ($user_id) {
			$q->whereHas('guru', function ($qq) use ($user_id) {
				$qq->where('users_id', $user_id);
			});
		})->get();
		return view('main.content.guru.materi.main', $data);
	}

	public function add(Request $request)
	{
		$user_id = Auth::user()->id;
		$data['materi'] = MateriShare::find($request->id);
		// $data['kelas'] = Kelas::where('guru_id',Auth::user()->user_id)->get();
		$data['kelas'] = Kelas::get();
		$data['tahunAjaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::whereHas('kelas_mapel', function ($q) use ($user_id) {
			$q->whereHas('guru', function ($qq) use ($user_id) {
				$qq->where('users_id', $user_id);
			});
		})->get();
		$content = view('main.content.guru.materi.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$params = [
			'judul' => 'required',
			'kelas_id' => 'required',
			'tahun_ajaran_id' => 'required',
			'mapel_id' => 'required',
			'semester' => 'required',
			'file_materi' => 'required_without:id|file|max:20000',
		];
		$message = [
			'judul.required' => 'ID Guru harus diisi',
			'kelas_id.required' => 'Kelas harus diisi',
			'tahun_ajaran_id.required' => 'Tahun Ajaran harus diisi',
			'mapel_id.required' => 'Mata Pelajaran harus diisi',
			'semester.required' => 'Semester harus diisi',
			'file_materi.required_without' => 'File harus diisi',
			'file_materi.max' => 'Maksimal ukuran file adalah 20 Mb',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}

		if (!empty($request->id)) {
			if (!$materi = MateriShare::where('id_materi', $request->id)->first()) {
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, data materi tidak ditemukan'];
			}
		} else {
			$materi = new MateriShare;
		}
		$materi->judul = $request->judul;
		$materi->kelas_id = $request->kelas_id;
		$materi->tahun_ajaran_id = $request->tahun_ajaran_id;
		$materi->tanggal_upload = date('Y-m-d H:i:s');
		$materi->user_id = Auth::user()->id;
		$materi->mapel_id = $request->mapel_id;
		$materi->deskripsi_materi = $request->deskripsi_materi;
		$materi->semester = $request->semester;
		if (isset($request->file_materi)) {
			if ($materi->file_materi != '') {
				if (file_exists('uploads/materi/' . $materi->file_materi)) {
					unlink('uploads/materi/' . $materi->file_materi);
				}
			}
			$ukuranFile1 = filesize($request->file_materi);
			if ($ukuranFile1 <= 20000000) { #20 mb
				$ext_foto1 = $request->file_materi->getClientOriginalExtension();
				$filename1 = "Materi" . date('Ymd-His') . "." . $ext_foto1;
				$temp_foto1 = 'uploads/materi/';
				$proses1 = $request->file_materi->move($temp_foto1, $filename1);
				$materi->file_materi = $filename1;
			} else {
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, data lebih besar dari 20 mb'];
			}
		}
		if (!$materi->save()) {
			return ['status' => 'fail', 'message' => 'Gagal menyimpan, Coba lagi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan'];
	}

	public function delete(Request $request)
	{
		$params = [
			'id' => 'required',
		];
		$message = [
			'id.required' => 'ID Materi harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}
		if (!$materi = MateriShare::where('id_materi', $request->id)->first()) {
			return ['status' => 'fail', 'message' => 'Gagal menghapus, data materi tidak ditemukan'];
		}
		if ($materi->file_materi != '') {
			if (file_exists('uploads/materi/' . $materi->file_materi)) {
				unlink('uploads/materi/' . $materi->file_materi);
			}
		}
		if (!$materi->delete()) {
			return ['status' => 'fail', 'message' => 'Gagal menghapus materi, coba lagi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menghapus materi'];
	}
}
