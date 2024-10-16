<?php

namespace App\Http\Controllers\Elearning\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MateriShare;
use Illuminate\Http\Request;
use DataTables, Auth;
use Illuminate\Support\Facades\Validator;

class MateriController extends Controller
{
	public function main(Request $request)
	{
		if ($request->ajax()) {
			$user_id = Auth::user()->id;
			$data = MateriShare::whereHas('kelas', function ($q) use ($user_id) {
				$q->whereHas('kelas_siswa', function ($qq) use ($user_id) {
					$qq->whereHas('siswa', function ($qqq) use ($user_id) {
						$qqq->where('users_id', $user_id);
					});
				});
			})->has('mata_pelajaran')->with('mata_pelajaran')->has('guru')->with('guru')->when($request->tahun_ajaran_id != '', function ($q) use ($request) {
				$q->where('tahun_ajaran_id', $request->tahun_ajaran_id);
			})->when($request->mapel_id != '', function ($q) use ($request) {
				$q->where('mapel_id', $request->mapel_id);
			})->get();
			return DataTables::of($data)->addIndexColumn()->addColumn('nama_mapel', function ($row) {
				return $row->mata_pelajaran->nama_mapel;
			})->addColumn('nama_guru', function ($row) {
				return $row->guru->nama;
			})->editColumn('tanggal_upload', function ($row) {
				return date('d-m-Y', strtotime($row->tanggal_upload));
			})->editColumn('judul', function ($row) {
				$judul = '';
				if (strlen($row->judul) > 20) {
					$judul = substr($row->judul, 0, 20) . '...';
				} else {
					$judul = $row->judul;
				}
				return $judul;
			})->addColumn('actions', function ($row) {
				$fileMateri = url("uploads/materi/$row->file_materi");
				$routeDownload = route('siswa.materi.downloadFile', $row->id_materi);
				return "<a href='$routeDownload' target='_blank' class='btn btn-primary btnDownload'><i class='bx bx-download mx-auto'></i></a>
						<a href='$fileMateri' target='_blank' class='btn btn-success btnLihat'><i class='bx bx-book-open mx-auto'></i></a>";
			})->rawColumns(['actions'])->toJson();
		}
		return view('main.content.siswa.materi-elearning.main');
	}

	public function downloadFile($id_materi)
	{
		// $params = [
		// 	'id_materi' => 'required',
		// ];
		// $message = [
		// 	'id_materi.required' => 'ID Materi Tidak Diketahui!',
		// ];
		// $validator = Validator::make($request->all(), $params, $message);
		// if ($validator->fails()) {
		// 	foreach ($validator->errors()->toArray() as $key => $val) {
		// 		$msg = $val[0]; # Get validation messages, only one
		// 		break;
		// 	}
		// 	return ['status' => 'fail', 'message' => $msg];
		// }
		if (!$materi = MateriShare::where('id_materi', $id_materi)->first()) {
			return ['status' => 'fail', 'message' => 'Materi tidak ditemukan!'];
		}
		if (!file_exists('uploads/materi/' . $materi->file_materi)) {
			return ['status' => 'fail', 'message' => 'Materi tidak ditemukan!'];
		}
		$fileMateri = public_path("uploads/materi/$materi->file_materi");
		// return ['status'=>'success','path'=>$fileMateri];
		// $headers = ['Content-Type: application/pdf'];
		// $newName = "$materi->judul";
		return response()->download($fileMateri);
	}
}
