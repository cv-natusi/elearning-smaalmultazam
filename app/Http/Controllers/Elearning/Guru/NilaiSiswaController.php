<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\JawabanSiswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use DataTables, Auth;

class NilaiSiswaController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Nilai Siswa';
	}

	public function main(Request $request)
	{
		$user_id = Auth::user()->id;
		if ($request->ajax()) {
			$data = JawabanSiswa::orderBy('waktu_mulai', 'DESC')
				->whereHas('soal', function ($q) use ($request) {
					$q->when($request->tahun_ajaran_id != '', function ($qq) use ($request) {
						$qq->where('tahun_ajaran_id', $request->tahun_ajaran_id);
					});
					$q->when($request->mapel_id != '', function ($qq) use ($request) {
						$qq->where('mapel_id', $request->mapel_id);
					});
					$q->when($request->kelas_id != '', function ($qq) use ($request) {
						$qq->where('kelas_id', $request->kelas_id);
					});
				})
				->when($request->soal_id != '', function ($q) use ($request) {
					$q->where('soal_id', $request->soal_id);
				})
				->with('soal')
				->has('siswa')
				->with('siswa')
				// ->whereHas('kelas', function ($q) use ($request) {
				// 	$q->when($request->semester != '', function ($qq) use ($request) {
				// 		$qq->where('semester', $request->semester);
				// 	});
				// })
				->get();
			return DataTables::of($data)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('Y F d H:i:s', strtotime($row->tanggal_upload));
			})->addColumn('kkm', function ($row) {
				return $row->soal->kkm;
			})->addColumn('nisn', function ($row) {
				return $row->siswa->nisn;
			})->addColumn('nama_siswa', function ($row) {
				return $row->siswa->nama;
			})->addColumn('nilai', function ($row) {
				return $row->nilai ? $row->nilai : '-';
			})->addColumn('status_lulus', function ($row) {
				if ($row->waktu_selesai == '') {
					return 'DALAM PENGERJAAN';
				}
				if ($row->nilai >= $row->soal->kkm) {
					return 'LULUS';
				} else {
					return 'TIDAK LULUS';
				}
			})->addColumn('actions', function ($row) {
				$urlMateri = url('uploads/materi');
				$html = "<a href='$urlMateri/$row->file_materi' target='_blank' class='btn btn-dark btn-purple p-2'><i class='bx bx-search-alt-2 mx-1'></i></a>";
				$html .= "<button onclick='tambahMateri($row->id_materi)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusMateri($row->id_materi)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['soal'] = Soal::where('user_id', $user_id)->get();
		$data['mata_pelajaran'] = MataPelajaran::whereHas('kelas_mapel', function ($q) use ($user_id) {
			$q->whereHas('guru', function ($qq) use ($user_id) {
				$qq->where('users_id', $user_id);
			});
		})->get();
		return view('main.content.guru.pengerjaan-siswa.main', $data);
	}
}
