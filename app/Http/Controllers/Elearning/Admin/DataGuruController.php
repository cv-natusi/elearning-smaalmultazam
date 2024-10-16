<?php

namespace App\Http\Controllers\Elearning\Admin;

use App\Http\Controllers\Controller;
use App\Http\Libraries\compressFile;
use App\Imports\GuruImport;
use App\Models\Guru;
use App\Models\Users;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Validator;
// use Maatwebsite\Excel\Excel;
use Excel, DB, Help, CLog;
use Illuminate\Support\Facades\Hash;

class DataGuruController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Data Guru';
		$this->data['breadCrumb'] = ['Data Master'];
	}

	public function main(Request $request)
	{
		$data = $this->data;
		if ($request->ajax()) {
			$guru = Guru::orderBy('id_guru', 'DESC')
				->with('kelas_mapel',function ($q) {
					$q->with('mata_pelajaran');
				})
				->get();
			return DataTables::of($guru)->addIndexColumn()->addColumn('foto', function ($row) {
				return '<a>Lihat Foto</a>';
			})->addColumn('tugas_utama', function ($row) {
				$html = '-';
				if(count($row->kelas_mapel)){
					$html = '';
					foreach ($row->kelas_mapel as $key => $value) {
						if($value->mata_pelajaran){
							if ($key) {
								$html .= '<br>';
							}
							$html .= '+ '.$value->mata_pelajaran->nama_mapel;
						}
					}
				};
				return $html;
			})->addColumn('tugas_tambahan', function ($row) {
				if ($row->is_piket) {
					return '+ Guru Piket';
				};
				return '-';
			})->addColumn('status', function ($row) {
				return 'aktif';
			})->addColumn('actions', function ($row) {
				$html = "<button onclick='tambahDataGuru($row->id_guru)' class='btn ms-1 btn-primary p-2'><i class='bx bx-edit-alt mx-1'></i></button>";
				$html .= "<button onclick='resetPassword($row->users_id)' class='btn ms-1 btn-danger p-2'><i class='bx bx-key mx-1'></i></button>";
				$html .= "<button onclick='hapusDataGuru($row->id_guru)' class='btn ms-1 btn-danger p-2'><i class='bx bx-trash mx-1'></i></button>";
				return $html;
			})->rawColumns(['actions', 'foto', 'tugas_utama', 'tugas_tambahan'])->toJson();
		}
		return view('main.content.admin.master.data-guru.main', $data);
	}

	public function add(Request $request)
	{
		$data['data_guru'] = Guru::where('id_guru', $request->id)->with('users')->first();
		$content = view('main.content.admin.master.data-guru.form', $data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function save(Request $request)
	{
		$rules = [
			'email' => 'required_without:id',
			'no_induk' => 'required_without:id',
		];
		$message = [
			'email.required_without' => 'Email Diisi',
			'no_induk.required_without' => 'No Induk Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		DB::beginTransaction();
		if ($request->id) {
			if (!$guru = Guru::where('id_guru', $request->id)->first()) {
				DB::rollback();
				return ['status' => 'fail', 'message' => 'Gagal menyimpan, Data guru tidak ditemukan!'];
			}
		} else {
			$guru = new Guru;
			$guru->foto = '';
			if (!$user = Users::storeGuru($request)) {
				DB::rollback();
				return ['status' => 'fail', 'message' => 'Gagal menyimpan!'];
			}
			// DB::rollback();
			// return $user;
			$guru->users_id = $user->id;
		}
		$guru->nama = $request->nama ? $request->nama : '';
		$guru->tmp_lahir = $request->tmp_lahir ? $request->tmp_lahir : '';
		$guru->tgl_lahir = $request->tgl_lahir ? date('Y-m-d H:i:s', strtotime($request->tgl_lahir)) : null;
		$guru->gender = $request->gender ? $request->gender : '';
		$guru->alamat = $request->alamat ? $request->alamat : '';
		$guru->no_tlp = $request->no_tlp ? $request->no_tlp : '';
		$guru->nip = $request->nip ? $request->nip : '';
		$guru->is_piket = false;
		if ($request->tugas_tambahan) {
			foreach ($request->tugas_tambahan as $key => $value) {
				if ($value=='piket') {
					$guru->is_piket = true;
				}
			}
		}
		if (isset($request->foto)) {
			if ($guru->foto != '') {
				if (file_exists('uploads/guru/' . $guru->foto)) {
					unlink('uploads/guru/' . $guru->foto);
				}
			}
			$ukuranFile1 = filesize($request->foto);
			if ($ukuranFile1 <= 500000) {
				$ext_foto1 = $request->foto->getClientOriginalExtension();
				$filename1 = "Foto_Guru" . date('Ymd-His') . "." . $ext_foto1;
				$temp_foto1 = 'uploads/guru/';
				$proses1 = $request->foto->move($temp_foto1, $filename1);
				$guru->foto = $filename1;
			} else {
				$file1 = $_FILES['foto']['name'];
				$ext_foto1 = $request->foto->getClientOriginalExtension();
				if (!empty($file1)) {
					$direktori1 = 'uploads/guru/'; //tempat upload foto
					$name1 = 'foto'; //name pada input type file
					$namaBaru1 = "Foto_Guru" . date('Ymd-His'); //name pada input type file
					$quality1 = 50; //konversi kualitas gambar dalam satuan %
					$upload1 = compressFile::UploadCompress($namaBaru1, $name1, $direktori1, $quality1);
				}
				$guru->foto = $namaBaru1 . "." . $ext_foto1;
			}
		}
		if (!$guru->save()) {
			DB::rollback();
			return ['status' => 'fail', 'message' => 'Gagal menyimpan!'];
		}
		DB::commit();
		return ['status' => 'success', 'message' => 'Berhasil menyimpan!'];
	}

	public function import(Request $request)	{
		$content = view('main.content.admin.master.data-guru.import')->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function importUpload(Request $request)
	{
		$rules = [
			'file' => 'required',
		];
		$message = [
			'file.required' => 'File Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}
		$array = Excel::toArray(new GuruImport, $request->file('file'));
		// dd($array);
		return ['status' => 'success', 'data' => $array[0]];
	}

	public function importSave(Request $request)
	{
		$rules = [
			'file' => 'required',
		];
		$message = [
			'file.required' => 'File Wajib Diisi',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}
		$urutan = explode(',', $request->urutan);
		$array = Excel::toArray(new GuruImport, $request->file('file'));
		$total = 0;
		foreach ($array[0] as $key => $value) {
			DB::beginTransaction();
			$newUser = $newGuru = (object) [];
			if ($value[0]==''||$value[1]=='') {
				DB::rollback();
				continue;
			}
			$newUser->email = $value[0];
			$newUser->no_induk = $value[1];
			foreach ($urutan as $key2 => $value2) {
				$newGuru->{$value2} = isset($value[$key2 + 2]) ? $value[$key2 + 2] : '';
			}
			if (Users::where('email',$newUser->email)->orWhere('no_induk',$newUser->no_induk)->first()) {
				DB::rollback();
				continue;
			}
			if (!$saveUser = Users::storeGuru($newUser)) {
				DB::rollback();
			}
			$newGuru->users_id = $saveUser->id;
			if (!Guru::store($newGuru)) {
				DB::rollback();
			}
			DB::commit();
			$total++;
		}
		return ['status' => 'success', 'message' => "Berhasil menyimpan $total data"];
	}

	public function aktif(Request $request)
	{
		$data = Guru::where('id_guru', $request->id)->delete();
		if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
	}

	public function delete(Request $request)
	{
		$rules = [
			// 'password' => 'required',
			'id' => 'required',
		];
		$message = [
			// 'password.required' => 'Password Harus Diisi',
			'id.required' => 'ID Tidak Ditemukan',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}
		// if (!Hash::check($request->password, Auth::user()->password)) {
		// 	return Help::resMsg('Password admininstrator salah', 201);
		// }
		DB::beginTransaction();
		try {
			if(!$guru = Guru::where('id_guru', $request->id)->first()) {
				return Help::resMsg('Data guru tidak ditemukan', 201);
			}
			$data = Guru::where('id_guru', $request->id)->delete();
			$user = Users::where('id', $guru->users_id)->delete();
			if (!$data||!$user) {
				DB::rollback();
				return Help::resMsg('Gagal Menghapus', 201);
			} else {
				DB::commit();
				return Help::resMsg('Berhasil Menghapus', 200);
			}
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

	public function resetPassword(Request $request) 
	{
		$rules = [
			'id' => 'required',
		];
		$message = [
			'id.required' => 'ID Tidak Ditemukan',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}
		if (!$user = Users::where('id',$request->id)->first()) {
			return ['status' => 'fail', 'message' => 'Gagal me-reset password, User tidak ditemukan'];
		}
		$user->password = Hash::make($user->email);
		if(!$user->save()){
			return ['status' => 'fail', 'message' => 'Terjadi kesalahan sistem'];
		}
		return ['status' => 'success', 'message' => 'Password berhasil di perbarui'];
	}
}
