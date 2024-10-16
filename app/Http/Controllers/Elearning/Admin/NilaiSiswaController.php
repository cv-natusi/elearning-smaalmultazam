<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\JawabanSiswa;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Soal;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Auth, DataTables;

class NilaiSiswaController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Nilai Siswa';
	}

	public function main(Request $request)
	{
		$data = $this->data;
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
				$html = "<button onclick='lihat($row->id_materi)' class='btn ms-1 btn-primary p-2'><i class='bx bx-spreadsheet mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['soal'] = Soal::get();
		$data['mata_pelajaran'] = MataPelajaran::get();
		return view('main.content.admin.pengerjaan-siswa.main', $data);
	}
}
