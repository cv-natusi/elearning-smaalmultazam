<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;

class DokumenController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Dokumen';
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$dokumen = Dokumen::orderBy('id_dokumen', 'DESC')
				->get();
			return DataTables::of($dokumen)->addIndexColumn()->addColumn('tanggal', function ($row) {
				return date('Y F d H:i:s', strtotime($row->updated_at));
			})->editColumn('keterangan', function ($row) {
				$mapel = '';
				if (strlen($row->keterangan) > 20) {
					$mapel = substr($row->keterangan, 0, 20) . '...';
				} else {
					$mapel = $row->keterangan;
				}
				return $mapel;
			})->editColumn('judul', function ($row) {
				$judul = '';
				if (strlen($row->judul) > 20) {
					$judul = substr($row->judul, 0, 20) . '...';
				} else {
					$judul = $row->judul;
				}
				return $judul;
			})->addColumn('actions', function ($row) {
				$urlMateri = url('uploads/dokumen');
				$html = "<a href='$urlMateri/$row->file' target='_blank' class='btn btn-dark btn-purple p-2'><i class='bx bx-search-alt-2 mx-1'></i></a>";
				$html .= "<button onclick='tambahMateri($row->id_dokumen)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusMateri($row->id_dokumen)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.admin.dokumen.main', $data);
	}

	public function add(Request $request)
	{
		$data['dokumen'] = Dokumen::find($request->id);
		$content = view('main.content.admin.dokumen.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$params = [
			'judul' => 'required',
			'file' => 'required_without:id|file|max:20000',
			'keterangan' => 'required',
		];
		$message = [
			'judul.required' => 'Judul harus diisi',
			'file.required_without' => 'File harus diisi',
			'file.max' => 'Maksimal ukuran file adalah 20 Mb',
			'keterangan.required' => 'Keterangan harus diisi',
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
			if (!$dokumen = Dokumen::where('id_dokumen', $request->id)->first()) {
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, dokumen tidak ditemukan'];
			}
		} else {
			$dokumen = new Dokumen;
		}
		$dokumen->judul = $request->judul;
		$dokumen->keterangan = $request->keterangan;
		if (isset($request->file)) {
			if ($dokumen->file != '') {
				if (file_exists('uploads/dokumen/' . $dokumen->file)) {
					unlink('uploads/dokumen/' . $dokumen->file);
				}
			}
			$ukuranFile1 = filesize($request->file);
			if ($ukuranFile1 <= 20000000) { #20 mb
				$ext_foto1 = $request->file->getClientOriginalExtension();
				$filename1 = "Dokumen" . date('Ymd-His') . "." . $ext_foto1;
				$temp_foto1 = 'uploads/dokumen/';
				$proses1 = $request->file->move($temp_foto1, $filename1);
				$dokumen->file = $filename1;
			} else {
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, data lebih besar dari 20 mb'];
			}
		}
		if (!$dokumen->save()) {
			return ['status' => 'fail', 'message' => 'Gagal menyimpan, Coba lagi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan'];
	}

	public function delete(Request $request)
	{
		$params = [
			'id' => 'required',
		];
		$message = [
			'id.required' => 'ID Materi harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}
		if (!$dokumen = Dokumen::where('id_dokumen', $request->id)->first()) {
			return ['status' => 'fail', 'message' => 'Gagal menghapus, data dokumen tidak ditemukan'];
		}
		if ($dokumen->file_materi != '') {
			if (file_exists('uploads/dokumen/' . $dokumen->file_materi)) {
				unlink('uploads/dokumen/' . $dokumen->file_materi);
			}
		}
		if (!$dokumen->delete()) {
			return ['status' => 'fail', 'message' => 'Gagal menghapus dokumen, coba lagi!'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menghapus dokumen'];
	}
}
