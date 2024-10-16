<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\JurnalGuru;
use Illuminate\Http\Request;
use DataTables, Help, CLog, Auth;
use Illuminate\Support\Facades\Validator;

class JurnalGuruController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Jurnal Guru';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$jurnal = JurnalGuru::orderBy('tanggal_upload', 'DESC')
			->where('user_id',Auth::user()->id)
				->get();
			return DataTables::of($jurnal)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('Y F d H:i:s', strtotime($row->tanggal_upload));
			})->addColumn('jurnal', function ($row) {
				$mapel = '';
				if (strlen($row->jurnal) > 20) {
					$mapel = substr($row->jurnal, 0, 20) . '...';
				} else {
					$mapel = $row->jurnal;
				}
				return $mapel;
			})->addColumn('actions', function ($row) {
				$html = '';
				if ($row->tanggal_upload == date('Y-m-d 00:00:00')) {
					$html .= "<button onclick='tambahMateri($row->id_jurnal_guru)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
					$html .= "<button onclick='hapusMateri($row->id_jurnal_guru)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				}
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.guru.jurnal.main',$data);
	}

	public function add(Request $request)
	{
		$data = $this->data;
		$data['jurnal'] = JurnalGuru::find($request->id);
		if ($request->id == '') {
			$data['jurnal'] = JurnalGuru::whereDate('tanggal_upload', date('Y-m-d'))->where('user_id', Auth::user()->id)->first();
		}
		$content = view('main.content.guru.jurnal.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$params = [
			'jurnal' => 'required',
			'tanggal_upload' => 'required',
		];
		$message = [
			'jurnal.required' => 'Jurnal harus diisi',
			'tanggal_upload.required' => 'Tanggal Upload harus diisi',
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
			if (!$jurnal = JurnalGuru::where('id_jurnal_guru', $request->id)->first()) {
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, data tidak ditemukan'];
			}
		} else {
			$jurnal = new JurnalGuru;
		}
		$jurnal->user_id = Auth::user()->id;
		$jurnal->jurnal = $request->jurnal;
		$jurnal->tanggal_upload = date('Y-m-d H:i:s', strtotime($request->tanggal_upload));
		if (!$jurnal->save()) {
			return ['status' => 'fail', 'message' => 'Gagal menyimpan, Coba Lagi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan Jurnal'];
	}

	public function delete(Request $request)
	{
		if (!JurnalGuru::where('id_jurnal_guru', $request->id)->where('user_id', Auth::user()->id)->whereDate('tanggal_upload',date('Y-m-d'))->delete()) {
			return ['status' => 'fail', 'message' => 'Gagal menghapus, Coba Lagi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menghapus jurnal!'];
	}
}
