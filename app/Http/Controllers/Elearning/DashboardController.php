<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Dokumen;
use App\Models\Guru;
use App\Models\JurnalGuru;
use App\Models\KelasMapel;
use App\Models\MateriShare;
use App\Models\PraktekBaikGuru;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\SpreadsheetShare;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller
{

	public function __construct()
	{ }
	public function main()
	{
		if (Auth::User()->level_user == '4') {
			return redirect()->route('siswa.dashboard');
		}
		if (Auth::user()->level_user == '3') {
			$data['jurnal'] = JurnalGuru::where('user_id', Auth::user()->id)->where('tanggal_upload', date('Y-m-d 00:00:00'))->first();
			$data['soal'] = Soal::where('user_id', Auth::user()->id)->count();
			$data['materi'] = MateriShare::where('user_id', Auth::user()->id)->count();
			$data['mapel'] = KelasMapel::whereHas('guru', function ($q) {
				$q->where('users_id', Auth::user()->id);
			})->count();
			$data['praktek'] = PraktekBaikGuru::where('user_id', Auth::user()->id)->count();
			$data['absensi'] = Absensi::where('users_id', Auth::user()->id)->whereDate('tanggal_absen',date('Y-m-d'))->first();
			return view('main.content.guru.dashboard.main', $data);
		}
		// if (Auth::user()->level_user == '2') {
		if (in_array(Auth::user()->level_user,['2','5'])) {
			$data['siswa'] = Siswa::where('status', 'Siswa Aktif')->count();
			$data['guru'] = Guru::count();
			$data['rapor'] = SpreadsheetShare::count();
			$data['dokumen'] = Dokumen::count();
			return view('main.content.admin.dashboard.main', $data);
		}
		return view('main.content.dashboard.main');
	}
}
