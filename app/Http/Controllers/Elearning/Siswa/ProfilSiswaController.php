<?php

namespace App\Http\Controllers\Elearning\Siswa;

use App\Http\Controllers\Controller;
use App\Http\Libraries\compressFile;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;
use Illuminate\Support\Facades\Hash;

class ProfilSiswaController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Profil Siswa';
	}

	public function main()
	{
		$data = $this->data;
		$data['siswa'] = Siswa::where('users_id', Auth::user()->id)->first();
		return view('main.content.siswa.profil.main', $data);
	}

	public function save(Request $request)
	{
		$params = [
			'nama' => 'required',
			'tempat_lahir' => 'required',
			'tanggal_lahir' => 'required',
			'jenis_kelamin' => 'required',
			'nama_ayah' => 'required',
			'nama_ibu' => 'required',
			'no_telp' => 'required',
			'alamat' => 'required',
		];
		$message = [
			'nama.required' => 'Nama harus diisi',
			'tempat_lahir.required' => 'Tempat Lahir harus diisi',
			'tanggal_lahir.required' => 'Tanggal Lahir harus diisi',
			'jenis_kelamin.required' => 'Jenis Kelamin harus diisi',
			'nama_ayah.required' => 'Nama Ayah harus diisi',
			'nama_ibu.required' => 'Nama Ibu harus diisi',
			'no_telp.required' => 'No Telepon harus diisi',
			'alamat.required' => 'Alamat harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}
		if (!$siswa = Siswa::where('users_id', Auth::user()->id)->first()) {
			return ['status' => 'fail', 'message' => 'Gagal mengupdate, data siswa tidak ditemukan'];
		}
		$siswa->nama = $request->nama;
		$siswa->tmp_lahir = $request->tempat_lahir;
		$siswa->tgl_lahir = $request->tanggal_lahir;
		$siswa->gender = $request->jenis_kelamin;
		$siswa->nama_ayah = $request->nama_ayah;
		$siswa->nama_ibu = $request->nama_ibu;
		$siswa->no_tlp = $request->no_telp;
		$siswa->alamat = $request->alamat;

		if (isset($request->foto)) {
			if ($siswa->foto != '') {
				if (file_exists('uploads/siswa/' . $siswa->foto)) {
					unlink('uploads/siswa/' . $siswa->foto);
				}
			}
			$ukuranFile1 = filesize($request->foto);
			if ($ukuranFile1 <= 500000) {
				$ext_foto1 = $request->foto->getClientOriginalExtension();
				$filename1 = "Foto_Siswa" . date('Ymd-His') . "." . $ext_foto1;
				$temp_foto1 = 'uploads/siswa/';
				$proses1 = $request->foto->move($temp_foto1, $filename1);
				$siswa->foto = $filename1;
			} else {
				$file1 = $_FILES['foto']['name'];
				$ext_foto1 = $request->foto->getClientOriginalExtension();
				if (!empty($file1)) {
					$direktori1 = 'uploads/siswa/'; //tempat upload foto
					$name1 = 'foto'; //name pada input type file
					$namaBaru1 = "Foto_Siswa" . date('Ymd-His'); //name pada input type file
					$quality1 = 50; //konversi kualitas gambar dalam satuan %
					$upload1 = compressFile::UploadCompress($namaBaru1, $name1, $direktori1, $quality1);
				}
				$siswa->foto = $namaBaru1 . "." . $ext_foto1;
			}
		}
		if (!$siswa->save()) {
			return ['status' => 'fail', 'message' => 'Gagal menyimpan data'];
		}
		return ['status' => 'success', 'message' => 'Berhasil menyimpan!'];
	}

	public function ubahPassword(Request $request) {
		$params = [
			'password_baru' => 'required|min:3',
			'ulangi_password_baru' => 'required|min:3|same:password_baru',
		];
		$message = [
			'password_baru.required' => 'Password Baru harus diisi',
			'ulangi_password_baru.required' => 'Ulangi Password Baru harus diisi',
			'password_baru.min' => 'Password Baru minimal 3 karakter',
			'ulangi_password_baru.min' => 'Ulangi Password Baru minimal 3 karakter',
			'ulangi_password_baru.same' => 'Ulangi Password Tidak Sama',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}

		if (!$user = User::where('id',Auth::user()->id)->first()) {
			return ['status' => 'fail', 'message' => 'Terjadi kesalahan, silahkan lakukan logout dan login'];
		}

		$user->password = Hash::make($request->password_baru);
		if(!$user->save()){
			return ['status' => 'fail', 'message' => 'Terjadi kesalahan sistem'];
		}
		return ['status' => 'success', 'message' => 'Password berhasil di perbarui'];
	}
}
