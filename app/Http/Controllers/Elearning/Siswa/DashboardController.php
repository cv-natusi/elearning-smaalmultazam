<?php

namespace App\Http\Controllers\Elearning\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Auth;

class DashboardController extends Controller{
	public function main(){
		$bulan = date('m');
		$tahun = date('Y');
		if ($bulan>=7) {
			$like = $tahun.'%';
		} else {
			$like = '%'.$tahun;
		}
		$data['bio'] = Siswa::join('users','siswas.users_id','users.id')
			->where('users.no_induk',Auth::user()->no_induk)
			->with('kelas_siswa',function ($q) use ($like) {
				$q->whereHas('tahun_ajaran',function ($qq) use ($like) {
					$qq->where('nama_tahun_ajaran','like',$like);
				})->with('kelas');
			})
			->first();
		return view('main.content.siswa.dashboard.main',$data);
	}
}
