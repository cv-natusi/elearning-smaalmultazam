<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Imports\KelasSiswaImport;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\KelasSiswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use DataTables, Help, DB, CLog, Excel;
use Illuminate\Support\Facades\Validator;

class KelasSiswaController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Data Kelas Siswa';
		$this->data['breadCrumb'] = ['Data Master'];
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$siswa = KelasSiswa::orderBy('siswa_id', 'DESC')
				->when($request->id_kelas != '', function ($q) use ($request) {
					$q->where('kelas_id', $request->id_kelas);
				})
				->when($request->id_tahun_ajaran != '', function ($q) use ($request) {
					$q->where('tahun_ajaran_id', $request->id_tahun_ajaran);
				})
				->with(['kelas', 'tahun_ajaran'])
				->with('siswa', function ($q) {
					$q->with('user');
				})
				->get();
			return DataTables::of($siswa)->addIndexColumn()->addColumn('nisn', function ($row) {
				return $row->siswa ? $row->siswa->nisn : '-';
			})->addColumn('nama_siswa', function ($row) {
				return $row->siswa ? $row->siswa->nama : '-';
			})->addColumn('no_induk', function ($row) {
				return $row->siswa ? $row->siswa->user ? $row->siswa->user->no_induk : '-' : '-';
			})->addColumn('kelas', function ($row) {
				return $row->kelas ? $row->kelas->nama_kelas : '-';
			})->editColumn('tahun_ajaran', function ($row) {
				return $row->tahun_ajaran ? $row->tahun_ajaran->nama_tahun_ajaran : '-';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='hapusKelasSiswa($row->id_kelas_siswa)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions'])->toJson();
		}
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['kelas'] = Kelas::get();
		return view('main.content.admin.master.data-kelas-siswa.main', $data);
	}

	public function add()
	{
		$data['kelas'] = Kelas::get();
		$data['tahun_ajaran'] = TahunAjaran::get();
		$content = view('main.content.admin.master.data-kelas-siswa.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function cariSiswa(Request $request)
	{
		$siswa = Siswa::where('nama', 'like', "%$request->term%")
			->orWhere('nisn', 'like', "%$request->term%")
			->orWhereHas('user', function ($q) use ($request) {
				$q->where('no_induk', 'like', "%$request->term%");
			})
			->with('user')
			->get();
		if (count($siswa) > 0) {
			return Help::resHttp(['code' => 200, 'message' => 'Ok', 'response' => $siswa]);
		}
		return Help::resHttp(['code' => 200, 'message' => 'Data not found', 'response' => $siswa]);
	}

	public function save(Request $request)
	{
		$rules = [
			'id_tahun_ajaran' => 'required',
			'id_kelas' => 'required',
			'siswa_id' => 'required',
		];
		$message = [
			'id_tahun_ajaran.required' => 'Kolom Tahun Ajaran Wajib Diisi',
			'id_kelas.required' => 'Kolom Kelas Wajib Diisi',
			'siswa_id.required' => 'Kolom Siswa Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		try {
			DB::beginTransaction();
			foreach ($request->siswa_id as $key => $value) {
				if ($kelas_siswa = KelasSiswa::where('siswa_id', $value)->where('kelas_id', $request->id_kelas)->where('tahun_ajaran_id', $request->id_tahun_ajaran)->first()) {
					continue;
				}
				$kelas_siswa = new KelasSiswa;
				$kelas_siswa->siswa_id = $value;
				$kelas_siswa->kelas_id = $request->id_kelas;
				$kelas_siswa->tahun_ajaran_id = $request->id_tahun_ajaran;
				if (!$kelas_siswa->save()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Gagal menyimpan cek data kembali'];
				}
			}
			DB::commit();
			return ['status' => 'success', 'message' => 'Berhasil menyimpan data'];
		} catch (\Throwable $e) {
			DB::rollback();
			$request->merge([
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'line' => $e->getLine(),
			]);
			CLog::catchError($request);
			return Help::resMsg(null, 500);
		}
	}

	public function delete(Request $request)
	{
		$rules = [
			'id' => 'required',
		];
		$message = [
			'id.required' => 'ID Kelas Siswa Tidak Diketahui',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		$siswa = KelasSiswa::where('id_kelas_siswa', $request->id)->delete();
		if (!$siswa) {
			return Help::resHttp(['code' => 201, 'message' => 'Data Gagal Dihapus']);
		}
		return Help::resHttp(['code' => 200, 'message' => 'Data Berhasil Dihapus']);
	}

	public function naikKelasForm()
	{
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['kelas'] = Kelas::get();
		$content = view('main.content.admin.master.data-kelas-siswa.naik-kelas', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function naikKelas(Request $request)
	{
		$siswa = KelasSiswa::orderBy('siswa_id', 'DESC')
			->when(($request->id_kelas == '' || $request->id_tahun_ajaran == ''), function ($q) {
				$q->where('siswa_id', 0);
			})
			->when($request->id_kelas != '', function ($q) use ($request) {
				$q->where('kelas_id', $request->id_kelas);
			})
			->when($request->id_tahun_ajaran != '', function ($q) use ($request) {
				$q->where('tahun_ajaran_id', $request->id_tahun_ajaran);
			})
			->with(['kelas', 'tahun_ajaran'])
			->has('siswa')
			->with('siswa', function ($q) {
				$q->with('user');
			})
			->get();
		return DataTables::of($siswa)->addIndexColumn()->addColumn('nisn', function ($row) {
			return $row->siswa ? $row->siswa->nisn : '-';
		})->addColumn('nama_siswa', function ($row) {
			return "<input type='hidden' name='id_siswa[]' value='" . $row->siswa->id_siswa . "' disabled/>" . $row->siswa->nama;
		})->addColumn('no_induk', function ($row) {
			return $row->siswa ? $row->siswa->user ? $row->siswa->user->no_induk : '-' : '-';
		})->addColumn('kelas', function ($row) {
			return $row->kelas ? $row->kelas->nama_kelas : '-';
		})->editColumn('tahun_ajaran', function ($row) {
			return $row->tahun_ajaran ? $row->tahun_ajaran->nama_tahun_ajaran : '-';
		})->rawColumns(['nama_siswa'])->toJson();
	}

	public function naikKelasSave(Request $request)
	{
		$rules = [
			'id_tahun_ajaran_old' => 'required',
			'id_kelas_old' => 'required',
			'id_tahun_ajaran_new' => 'required',
			'id_kelas_new' => 'required',
		];
		$message = [
			'id_tahun_ajaran_old.required' => 'Kolom Tahun Ajaran Kelas Awal Wajib Diisi',
			'id_kelas_old.required' => 'Kolom Kelas Kelas Awal Wajib Diisi',
			'id_tahun_ajaran_new.required' => 'Kolom Tahun Kelas Baru Ajaran Wajib Diisi',
			'id_kelas_new.required' => 'Kolom Kelas Kelas Baru Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}
		try {
			DB::beginTransaction();
			$kelasSiswa = KelasSiswa::orderBy('siswa_id', 'DESC')
				->when(($request->id_kelas_old == '' || $request->id_tahun_ajaran_old == ''), function ($q) {
					$q->where('siswa_id', 0);
				})
				->when($request->id_kelas_old != '', function ($q) use ($request) {
					$q->where('kelas_id', $request->id_kelas_old);
				})
				->when($request->id_tahun_ajaran_old != '', function ($q) use ($request) {
					$q->where('tahun_ajaran_id', $request->id_tahun_ajaran_old);
				})
				->has('siswa')
				->get();
			foreach ($kelasSiswa as $key => $value) {
				if (KelasSiswa::where('siswa_id', $value->siswa_id)
					->where('kelas_id', $request->id_kelas_new)
					->where('tahun_ajaran_id', $request->id_tahun_ajaran_new)
					->first()
				) {
					continue;
				}
				$siswa = new KelasSiswa;
				$siswa->siswa_id = $value->siswa_id;
				$siswa->kelas_id = $request->id_kelas_new;
				$siswa->tahun_ajaran_id = $request->id_tahun_ajaran_new;
				if (!$siswa->save()) {
					DB::rollback();
					return ['status' => 'fail', 'message' => 'Terjadi kesalahan sistem'];
				}
			}
			DB::commit();
			return ['status' => 'success', 'message' => 'Berhasil menyimpan data'];
		} catch (\Throwable $e) {
			DB::rollback();
			$request->merge([
				'file' => $e->getFile(),
				'message' => $e->getMessage(),
				'line' => $e->getLine(),
			]);
			CLog::catchError($request);
			return Help::resMsg(null, 500);
		}
	}

	public function importNaikKelasForm()
	{
		$data['tahun_ajaran'] = TahunAjaran::get();
		$data['kelas'] = Kelas::get();
		$content = view('main.content.admin.master.data-kelas-siswa.import-naik-kelas', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function readExcel(Request $request) {
		$arraySiswa = Excel::toArray(new KelasSiswaImport, $request->file('file'));
		$no_induk = [];
		foreach ($arraySiswa[0] as $key => $value) {
			$no_induk[] = (string)$value[$request->kolom-1];
		}
		$siswas = Siswa::whereHas('user',function ($q) use($no_induk) {
				$q->whereIn('no_induk',$no_induk);
			})->with('user');
		$data['siswa'] = $siswas->get();
		$data['no_induk'] = implode(',',$no_induk);
		$data['no_induk_ada'] = implode(',',$siswas->get()->pluck('user.no_induk')->toArray());
		$data['id_siswa'] = implode(',',$siswas->pluck('id_siswa')->toArray());
		return ['status' => 'success', 'content' => $data];
	}

	public function listSiswa(Request $request) {
		$siswa = Siswa::orderBy('id_siswa', 'DESC')
			->with('user')
			->has('user')
			->whereIn('id_siswa',explode(',',$request->id_siswa))
			->get();
		return DataTables::of($siswa)->addIndexColumn()->
		addColumn('nama_siswa', function ($row) {
			return $row->nama;
		})->addColumn('no_induk', function ($row) {
			return $row->user->no_induk;
		})->toJson();
	}
	
	public function importNaikKelasSave(Request $request) {
		
	}
}
