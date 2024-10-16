<?php

namespace App\Http\Controllers\Elearning\Siswa;

use App\Http\Controllers\Controller;
use App\Models\JawabanSiswa;
use Illuminate\Http\Request;
use Auth, DataTables;

class DataNilaiController extends Controller
{
	public function main(Request $request)
	{
		if ($request->ajax()) {
			$user_id = Auth::user()->id;
			$data = JawabanSiswa::whereHas('siswa', function ($q) use ($user_id) {
				$q->where('users_id', $user_id);
			})->whereHas('soal', function ($q) {
				$q->has('mata_pelajaran');
			})->with('soal', function ($q) {
				$q->with('mata_pelajaran');
			})->get();
			return DataTables::of($data)->addIndexColumn()->addColumn('nama_mapel', function ($row) {
				return $row->soal->mata_pelajaran->nama_mapel;
			})->addColumn('status_lulus', function ($row) {
				if (!$row->soal->tampilkan_nilai) {
					return '-';
				}
				if ($row->nilai >= $row->soal->kkm) {
					return 'LULUS';
				} else {
					return 'TIDAK LULUS';
				}
			})->addColumn('judul', function ($row) {
				return $row->soal->judul_soal;
			})->addColumn('kkm', function ($row) {
				return $row->soal->kkm;
			})->addColumn('jumlah_soal', function ($row) {
				return $row->soal->jumlah_soal;
			})->editColumn('waktu_mulai', function ($row) {
				return date('d F Y', strtotime($row->waktu_mulai));
			})->editColumn('nilai', function ($row) {
				return $row->soal->tampilkan_nilai ? $row->nilai : '-';
			})->toJson();
		}
		return view('main.content.siswa.data-nilai.main');
	}
}
