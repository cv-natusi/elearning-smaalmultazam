<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterJenisFile;
use Illuminate\Http\Request;
use DataTables, Help;
use Illuminate\Support\Facades\Validator;

class JenisFileController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Jenis File';
		$this->data['breadCrumb'] = ['Data Master'];
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$jenis_file = MasterJenisFile::get();
			return DataTables::of($jenis_file)->addIndexColumn()->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahJenisFile($row->id)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusJenisFile($row->id)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.admin.master.data-jenis-file.main', $data);
	}

	public function add(Request $request)
	{
		$data['jenis_file'] = MasterJenisFile::find($request->id);
		$content = view('main.content.admin.master.data-jenis-file.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$rules = [
			'nama_jenis_file' => 'required',
		];
		$message = [
			'nama_jenis_file.required' => 'Nama Jenis File Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		if (empty($request->id)) {
			$jenis_file = new MasterJenisFile;
		} else {
			$jenis_file = MasterJenisFile::find($request->id);
		}
		$jenis_file->nama = $request->nama_jenis_file;
		$jenis_file->tampil_pada_siswa = $request->tampil_pada_siswa ?? 0;
		if ($jenis_file->save()) {
			return ['code' => 200, 'status' => 'success', 'Berhasil.'];
		} else {
			return ['code' => 201, 'status' => 'error', 'Gagal.'];
		}
	}

	public function delete(Request $request)
	{
		$data = MasterJenisFile::where('id', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}
}
