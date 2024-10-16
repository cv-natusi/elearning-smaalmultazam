<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use DataTables, Help;
use Illuminate\Support\Facades\Validator;

class TahunAjaranController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Tahun Ajaran';
		$this->data['breadCrumb'] = ['Data Master'];
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$tahun_ajaran = TahunAjaran::orderBy('nama_tahun_ajaran', 'DESC')
				->get();
			return DataTables::of($tahun_ajaran)->addIndexColumn()->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahTahunAjaran($row->id_tahun_ajaran)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusTahunAjaran($row->id_tahun_ajaran)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.admin.master.tahun-ajaran.main', $data);
	}

	public function add(Request $request)
	{
		$data['tahun_ajaran'] = TahunAjaran::find($request->id);
		$content = view('main.content.admin.master.tahun-ajaran.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$rules = [
			'nama_tahun_ajaran' => 'required',
		];
		$message = [
			'nama_tahun_ajaran.required' => 'Kolom Tahun Ajaran Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		if (empty($request->id)) {
			$tahun_ajaran = new TahunAjaran;
		} else {
			$tahun_ajaran = TahunAjaran::find($request->id);
		}
		$tahun_ajaran->nama_tahun_ajaran = $request->nama_tahun_ajaran;
		if ($tahun_ajaran->save()) {
			return ['code' => 200, 'status' => 'success', 'Berhasil.'];
		} else {
			return ['code' => 201, 'status' => 'error', 'Gagal.'];
		}
	}

	public function delete(Request $request)
	{
		$data = TahunAjaran::where('id_tahun_ajaran', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}
}
