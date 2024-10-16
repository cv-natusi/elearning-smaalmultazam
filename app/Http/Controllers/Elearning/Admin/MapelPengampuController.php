<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\MataPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class MapelPengampuController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Data Mapel Pengampu';
	}

	public function main(Request $request)
	{
		if ($request->ajax()) {
			$kelasMapel = KelasMapel::orderBy('id_kelas_mapel', 'DESC')
				->with(['kelas', 'mata_pelajaran', 'guru', 'tahun_ajaran'])
				->when($request->id_kelas != '', function ($q) use ($request) {
					$q->where('kelas_id', $request->id_kelas);
				})
				->when($request->id_tahun_ajaran != '', function ($q) use ($request) {
					$q->where('tahun_ajaran_id', $request->id_tahun_ajaran);
				})
				->get();
			return DataTables::of($kelasMapel)->addIndexColumn()->addColumn('nama_kelas', function ($row) {
				return $row->kelas ? $row->kelas->nama_kelas : '-';
			})->addColumn('nama_mata_pelajaran', function ($row) {
				return $row->mata_pelajaran ? $row->mata_pelajaran->nama_mapel : '-';
			})->addColumn('nama_guru', function ($row) {
				return $row->guru ? $row->guru->nama : '-';
			})->addColumn('nama_tahun_ajaran', function ($row) {
				return $row->tahun_ajaran ? $row->tahun_ajaran->nama_tahun_ajaran : '-';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahMapel($row->id_kelas_mapel)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusMapel($row->id_kelas_mapel)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		$data = $this->data;
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		return view('main.content.admin.master.data-mapel-pengampu.main', $data);
	}

	public function add(Request $request)
	{
		$data['kelas_mapel'] = KelasMapel::find($request->id);
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['guru'] = Guru::get();
		$data['mapel'] = MataPelajaran::get();
		$content = view('main.content.admin.master.data-mapel-pengampu.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$params = [
			'guru_id' => 'required',
			'mapel_id' => 'required',
			'kelas_id' => 'required',
			'tahun_ajaran_id' => 'required',
		];
		$message = [
			'guru_id.required' => 'Guru harus diisi',
			'mapel_id.required' => 'Mata Pelajaran harus diisi',
			'kelas_id.required' => 'Kelas harus diisi',
			'tahun_ajaran_id.required' => 'Tahun Ajaran harus diisi',
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
			if (!$kelasMapel = KelasMapel::where('id_kelas_mapel', $request->id)->first()) {
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, data tidak ditemukan'];
			}
		} else {
			if ($kelasMapel = KelasMapel::where('guru_id', $request->guru_id)->where('mapel_id', $request->mapel_id)->where('kelas_id', $request->kelas_id)->where('tahun_ajaran_id', $request->tahun_ajaran_id)->first()) {
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, data yang sama sudah tersimpan sebelumnya'];
			}
			$kelasMapel = new KelasMapel;
		}
		$kelasMapel->guru_id = $request->guru_id;
		$kelasMapel->mapel_id = $request->mapel_id;
		$kelasMapel->kelas_id = $request->kelas_id;
		$kelasMapel->tahun_ajaran_id = $request->tahun_ajaran_id;

		if (!$kelasMapel->save()) {
			return ['status' => 'fail', 'message' => 'Gagal menyimpan data'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan data'];
	}
}
