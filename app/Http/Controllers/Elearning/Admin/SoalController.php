<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Pertanyaan;
use App\Models\Soal;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use DataTables;

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
				$html = "<button onclick='previewSoal($row->id_soal)' class='btn ms-1 btn-primary p-2'><i class='bx bx-spreadsheet mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'tanggal'])->toJson();
		}
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['mataPelajaran'] = MataPelajaran::get();
		return view('main.content.admin.soal.main',$data);
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
}
