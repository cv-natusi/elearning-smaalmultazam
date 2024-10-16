<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use DataTables, Help;
use Illuminate\Support\Facades\Validator;

class MataPelajaranController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Mata Pelajaran';
		$this->data['breadCrumb'] = ['Data Master'];
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$mapel = MataPelajaran::orderBy('id_mapel', 'DESC')
				// ->has('kelas_mapel')
				->with('kelas_mapel', function ($q) {
					$q->has('kelas')->with('kelas');
				})
				->get();
			return DataTables::of($mapel)->addIndexColumn()->addColumn('kelas', function ($row) {
				$kelas = '';
				foreach ($row->kelas_mapel as $key => $value) {
					$kelas .= ($value->kelas?$value->kelas->nama_kelas:'') . ", ";
				};
				return $kelas;
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahMapel($row->id_mapel)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusMapel($row->id_mapel)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.admin.master.mata-pelajaran.main',$data);
	}

	public function add(Request $request)
	{
		$data['mapel'] = MataPelajaran::find($request->id);
		$content = view('main.content.admin.master.mata-pelajaran.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	
	public function save(Request $request)
	{
		$params = [
			'nama_mapel' => 'required',
		];
		$message = [
			'nama_mapel.required' => 'Nama Mata Pelajaran harus diisi',
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
			if (!$mapel = MataPelajaran::where('id_mapel', $request->id)->first()) {
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, data tidak ditemukan'];
			}
		} else {
			$mapel = new MataPelajaran;
		}
		$mapel->nama_mapel = $request->nama_mapel;
		if (!$mapel->save()) {
			return ['status' => 'fail', 'message' => 'Gagal menyimpan data'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan data'];
	}

	public function delete(Request $request) {
		$data = MataPelajaran::where('id_mapel', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}
}
