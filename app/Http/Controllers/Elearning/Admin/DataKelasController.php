<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use DataTables, Help;
use Illuminate\Support\Facades\Validator;

class DataKelasController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Data Kelas';
		$this->data['breadCrumb'] = ['Data Master'];
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$kelas = Kelas::orderBy('id_kelas', 'DESC')
				->with(['guru', 'tahun_ajaran'])
				->when($request->id_tahun_ajaran != '', function ($q) use ($request) {
					$q->where('tahun_ajaran_id', $request->id_tahun_ajaran);
				})
				->get();
			return DataTables::of($kelas)->addIndexColumn()->addColumn('kelas', function ($row) {
				$kelas = '-';
				switch ($row->kelas_tingkat) {
					case '1':
						$kelas = 'X';
						break;

					case '2':
						$kelas = 'XI';
						break;

					case '3':
						$kelas = 'XII';
						break;

					default:
						# code...
						break;
				}
				return $kelas;
			})->addColumn('guru', function ($row) {
				return $row->guru ? $row->guru->nama : '-';
			})->editColumn('tahun_ajaran', function ($row) {
				return $row->tahun_ajaran ? $row->tahun_ajaran->nama_tahun_ajaran : '-';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahDataKelas($row->id_kelas)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='hapusDataKelas($row->id_kelas)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'foto'])->toJson();
		}
		$data['tahun_ajaran'] = TahunAjaran::get();
		return view('main.content.admin.master.data-kelas.main', $data);
	}

	public function add(Request $request)
	{
		$data['kelas'] = Kelas::find($request->id);
		$data['guru'] = Guru::select('id_guru', 'nama')->get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$content = view('main.content.admin.master.data-kelas.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$rules = [
			'kelas_tingkat' => 'required',
			// 'tahun_ajaran_id' => 'required',
			'nama_kelas' => 'required',
			'guru_id' => 'required',
		];
		$message = [
			'kelas_tingkat.required' => 'Kolom Kelas Wajib Diisi',
			// 'tahun_ajaran_id.required' => 'Kolom Tahun Ajaran Wajib Diisi',
			'nama_kelas.required' => 'Kolom Nama Kelas Wajib Diisi',
			'guru_id.required' => 'Kolom Wali Kelas Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		if (empty($request->id)) {
			$kelas = new Kelas;
		} else {
			$kelas = Kelas::find($request->id);
		}
		$kelas->nama_kelas = $request->nama_kelas;
		$kelas->guru_id = $request->guru_id;
		// $kelas->tahun_ajaran_id = $request->tahun_ajaran_id;
		$kelas->kelas_tingkat = $request->kelas_tingkat;
		if ($kelas->save()) {
			return ['code' => 200, 'status' => 'success', 'Berhasil.'];
		} else {
			return ['code' => 201, 'status' => 'error', 'Gagal.'];
		}
	}

	public function delete(Request $request)
	{
		$data = Kelas::where('id_kelas', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}
}
