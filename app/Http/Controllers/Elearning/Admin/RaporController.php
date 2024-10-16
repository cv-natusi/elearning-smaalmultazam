<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\SpreadsheetShare;
use Illuminate\Http\Request;
use DataTables, Help;
use Illuminate\Support\Facades\Validator;

class RaporController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'E-RAPOR';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$rapor = SpreadsheetShare::with('guru')->get();
			return DataTables::of($rapor)->addIndexColumn()->addColumn('nama_guru', function ($row) {
				return $row->guru ? $row->guru->nama : '-';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahDataGuru($row->id_spreadsheet_share)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusDataGuru($row->id_spreadsheet_share)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.admin.rapor.main', $data);
	}

	public function add(Request $request)
	{
		$data['rapor'] = SpreadsheetShare::where('id_spreadsheet_share', $request->id)->first();
		$data['guru'] = Guru::get();
		$content = view('main.content.admin.rapor.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$rules = [
			'judul' => 'required',
			// 'tahun_ajaran_id' => 'required',
			'guru_id' => 'required',
			'link' => 'required',
		];
		$message = [
			'judul.required' => 'Judul Wajib Diisi',
			// 'tahun_ajaran_id.required' => 'Kolom Tahun Ajaran Wajib Diisi',
			'guru_id.required' => 'Kolom Guru Wajib Diisi',
			'link.required' => 'Kolom Link Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		if (empty($request->id)) {
			$rapor = new SpreadsheetShare;
		} else {
			$rapor = SpreadsheetShare::find($request->id);
		}
		$rapor->judul = $request->judul;
		$rapor->guru_id = $request->guru_id;
		$rapor->link = $request->link;
		if ($rapor->save()) {
			return ['code' => 200, 'status' => 'success', 'Berhasil.'];
		} else {
			return ['code' => 201, 'status' => 'error', 'Gagal.'];
		}
	}

	public function delete(Request $request)
	{
		$data = SpreadsheetShare::where('id_spreadsheet_share', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}
}
