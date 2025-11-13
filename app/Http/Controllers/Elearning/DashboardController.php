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
use App\Models\Rapor;
use App\Models\Siswa;
use App\Models\Soal;
use App\Models\SpreadsheetShare;
use App\Models\Visitor;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Auth;
use App\Exports\VisitorsReportExport; // <-- Tambahkan ini di atas
use Maatwebsite\Excel\Facades\Excel;

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
			$data['rapor'] = Rapor::count();
			$data['dokumen'] = Dokumen::count();
			$subQuery = DB::table('visitors')
				->selectRaw("
					DATE(created_at) as date,
					ip_address,
					MAX(CASE WHEN user_id IS NOT NULL THEN 1 ELSE 0 END) as is_user
				")
				->where('created_at', '>=', now()->subDays(30))
				->groupBy('date', 'ip_address');
			
			$visitorData = DB::table(DB::raw("({$subQuery->toSql()}) as sub"))
				->mergeBindings($subQuery)
				->selectRaw("
					sub.date,
					SUM(CASE WHEN sub.is_user = 0 THEN 1 ELSE 0 END) as guest_visitors,
					SUM(CASE WHEN sub.is_user = 1 THEN 1 ELSE 0 END) as user_visitors
				")
				->groupBy('sub.date')
				->orderBy('sub.date', 'asc')
				->get();

			// Format data untuk Chart.js
			$data['labels'] = $visitorData->pluck('date');
			$data['guestData'] = $visitorData->pluck('guest_visitors');
			$data['userData'] = $visitorData->pluck('user_visitors');
			return view('main.content.admin.dashboard.main', $data);
		}
		return view('main.content.dashboard.main');
	}

	public function download(Request $request)
	{
		$request->validate([
			'kategori' => 'required|in:harian,bulanan,tahunan',
		]);

		$kategori = $request->kategori;
		$fileName = "laporan_pengunjung_{$kategori}_" . now()->format('Y-m-d') . ".xlsx";

		// Ini akan men-trigger download file di browser
		return Excel::download(new VisitorsReportExport($kategori), $fileName);
	}
}
