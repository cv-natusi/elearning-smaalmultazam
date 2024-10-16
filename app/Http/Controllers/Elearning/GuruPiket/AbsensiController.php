<?php

namespace App\Http\Controllers\Elearning\GuruPiket;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use DataTables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

class AbsensiController extends Controller
{
	protected $data;

	public function __construct()
	{
		$this->data['title'] = 'Presensi Guru';
	}

	public function main(Request $request) {
		$data = $this->data;
		if ($request->ajax()) {
			$data = Absensi::orderBy('tanggal_absen', 'DESC')
				->with('guru')
				->has('guru')
				->whereDate('tanggal_absen',date('Y-m-d',strtotime($request->tanggal)))
				->get();
			return DataTables::of($data)->addIndexColumn()->editColumn('absen_datang', function ($row) {
				return date('H:i:s', strtotime($row->absen_datang));
			})->editColumn('absen_pulang', function ($row) {
				if ($row->absen_pulang) {
					return date('H:i:s', strtotime($row->absen_pulang));
				}
				return '-';
			})->addColumn('nama', function ($row) {
				return $row->guru->nama;
			})->editColumn('lokasi_datang', function ($row) {
				return "<a href='javascript:void(0);' onclick='openMap($row->id_absensi,0)' <i class='bx bx-map mx-1'></i> Lihat lokasi</a>";
			})->addColumn('lokasi_pulang', function ($row) {
				return "<a href='javascript:void(0);' onclick='openMap($row->id_absensi,1)' <i class='bx bx-map mx-1'></i> Lihat lokasi</a>";
			})->rawColumns(['lokasi_datang','lokasi_pulang'])->toJson();
		}
		return view('main.content.guru-piket.absensi.main',$data);
	}

	public function openMap(Request $request) {
		$rules = [
			'id' => 'required',
			'absen' => 'required',
		];
		$message = [
			'id.required' => 'Data tidak valid!',
			'absen.required' => 'Absen tidak valid!',
		];
		$validate = Validator::make($request->all(), $rules, $message);
		if ($validate->fails()) {
			return response()->json(['message' => $validate->errors()->all()[0]], 201);
		}

		if (!$absensi = Absensi::where('id_absensi',$request->id)->with('guru')->first()) {
			return ['status' => 'fail', 'message' => 'Data tidak ditemukan!'];
		}
		$data['absensi'] = $absensi;
		$data['absen'] = $request->absen;
		$content = view('main.content.guru-piket.absensi.maps',$data)->render();
		return ['status' => 'success', 'content' => $content];
	}

	public function exportPdf(Request $request) 
	{
		$data['data'] = Absensi::orderBy('tanggal_absen', 'DESC')
			->with('guru')
			->has('guru')
			->whereDate('tanggal_absen',date('Y-m-d',strtotime($request->tanggal)))
			->get();
		$data['tanggal'] = $request->tanggal;
		$pdf = Pdf::loadView('main.content.guru-piket.absensi.pdf',$data);
		return $pdf->stream("presensi_guru_".$request->tanggal.".pdf");
		return view('main.content.guru-piket.absensi.pdf',$data);
	}
}
