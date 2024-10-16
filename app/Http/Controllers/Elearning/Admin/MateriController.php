<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\MateriShare;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use DataTables;

class MateriController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Materi';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$materi = MateriShare::orderBy('tanggal_upload', 'DESC')
				->with('mata_pelajaran')
				->has('mata_pelajaran')
				->get();
			return DataTables::of($materi)->addIndexColumn()->addColumn('tanggal', function ($row) {
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
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::get();
		return view('main.content.admin.materi.main', $data);
	}
}
