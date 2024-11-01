<?php

namespace App\Http\Controllers\Elearning\Guru;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Auth,Help;
use App\Http\Libraries\compressFile;
use App\Models\Kelas;
use App\Models\KelasMapel;
use App\Models\TugasTambahan;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfilGuruController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Profil Guru';
	}

	public function main()
	{
		$data['guru'] = Guru::where('users_id', Auth::user()->id)->first();
		$data['tugas_utama'] = KelasMapel::where('guru_id',$data['guru']->id_guru)->
			with(['mata_pelajaran','kelas'])->
			has('mata_pelajaran')->
			has('kelas')->
			get();
		$data['tugas_tambahan'] = TugasTambahan::where('guru_id',Auth::user()->id)->get();
		if (Auth::user()->piket) {
			$data['tugas_tambahan'][] = (object)[
				'nama_tugas' => 'Guru Piket'
			];
		}
		if ($kelas = Kelas::where('guru_id',$data['guru']->id_guru)->where('guru_id','!=',null)->get()) {
			foreach ($kelas as $key => $value) {
				$data['tugas_tambahan'][] = (object)[
					'nama_tugas' => 'Wali Kelas '.$value->nama_kelas
				];
			}
		}
        // return $data;
		return view('main.content.guru.profil-guru.main', $data);
	}

	public function save(Request $request)
	{
		$params = [
			'id_guru' => 'required',
			'nip' => 'required',
			'no_tlp' => 'required',
			'gender' => 'required',
			'tmp_lahir' => 'required',
			'tgl_lahir' => 'required',
			'alamat' => 'required',
		];
		$message = [
			'id_guru.required' => 'ID Guru harus diisi',
			'nip.required' => 'NIP harus diisi',
			'no_tlp.required' => 'No Telepon harus diisi',
			'gender.required' => 'Gender harus diisi',
			'tmp_lahir.required' => 'Tempat Lahir harus diisi',
			'tgl_lahir.required' => 'Tanggal Lahir harus diisi',
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
		if (!$guru = Guru::where('id_guru', $request->id_guru)->first()) {
			return ['status' => 'fail', 'message' => 'Data Guru tidak ditemukan'];
		}
		$guru->nama = $request->nama;
		$guru->nip = $request->nip;
		$guru->no_tlp = $request->no_tlp;
		$guru->gender = $request->gender;
		$guru->tmp_lahir = $request->tmp_lahir;
		$guru->tgl_lahir = $request->tgl_lahir;
		$guru->alamat = $request->alamat;
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
			return ['status' => 'fail', 'message' => 'Gagal Menyimpan, coba lagi!'];
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
			return ['status' => 'fail', 'message' => 'Terjadi kesalahan, silahkan lakukan logout dan login'];
		}
		return ['status' => 'success', 'message' => 'Password berhasil di perbarui'];
	}

    public function formTugasTambahan(Request $request) {
        try {
            $data['data'] = (!empty($request->id_tugas_tambahan)) ? TugasTambahan::find($request->id_tugas_tambahan) : "";
            $content = view('main.content.guru.profil-guru.modal', $data)->render();
		    return ['status' => 'success', 'content' => $content];
          } catch(\Exception $e) {
            return ['status' => 'error', 'content' => $e->getMessage(),
            'line' => $e->getLine()];
          }
    }

    public function saveTugasTambahan(Request $request) {
        // return $request->all();
        $params = [
			'nama_tugas_tambahan' => 'required',
		];
		$message = [
			'nama_tugas_tambahan.required' => 'Nama Tugas harus diisi',
		];
		$validator = Validator::make($request->all(), $params, $message);
		if ($validator->fails()) {
			foreach ($validator->errors()->toArray() as $key => $val) {
				$msg = $val[0]; # Get validation messages, only one
				break;
			}
			return ['status' => 'fail', 'message' => $msg];
		}

        try {
            $newdata = $request->id_tugas_tambahan ? TugasTambahan::find($request->id_tugas_tambahan) : new TugasTambahan;
            $newdata->guru_id = Auth::user()->id;
            $newdata->nama_tugas_tambahan = $request->nama_tugas_tambahan;
            $newdata->save();
            return response()->json([
                'status' =>'success',
                'code' => 200,
                'message' => 'Data Berhasil Disimpan'
            ]);
        } catch(\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'code' => 500,
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function deleteTugasTambahan(Request $request) {
        $data = TugasTambahan::where('id_tugas_tambahan',$request->id)->delete();
        if ($data) {
			return Help::resMsg('Berhasil Menghapus', 200);
		} else {
			return Help::resMsg('Gagal Menghapus', 201);
		}
    }
}
