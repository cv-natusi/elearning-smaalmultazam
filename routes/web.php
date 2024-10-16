<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Elearning\Admin\DataGuruController;
use App\Http\Controllers\Elearning\Admin\DataKelasController;
use App\Http\Controllers\Elearning\Admin\DataSiswaController;
use App\Http\Controllers\Elearning\Admin\DokumenController as AdminDokumenController;
use App\Http\Controllers\Elearning\Admin\KelasSiswaController;
use App\Http\Controllers\Elearning\Admin\MapelPengampuController;
use App\Http\Controllers\Elearning\Admin\MataPelajaranController;
use App\Http\Controllers\Elearning\Admin\MateriController as AdminMateriController;
use App\Http\Controllers\Elearning\Admin\MateriElearningController;
use App\Http\Controllers\Elearning\Admin\NilaiSiswaController as AdminNilaiSiswaController;
use App\Http\Controllers\Elearning\Admin\RaporController as AdminRaporController;
use App\Http\Controllers\Elearning\Admin\SoalController;
use App\Http\Controllers\Elearning\Admin\TahunAjaranController;
use App\Http\Controllers\Elearning\DashboardController;
use App\Http\Controllers\Elearning\Guru\DokumenController;
use App\Http\Controllers\Elearning\Guru\JurnalGuruController;
use App\Http\Controllers\Elearning\Guru\MateriController;
use App\Http\Controllers\Elearning\Guru\NilaiSiswaController;
use App\Http\Controllers\Elearning\Guru\PraktekBaikGuruController;
use App\Http\Controllers\Elearning\Guru\ProfilGuruController;
use App\Http\Controllers\Elearning\Guru\RaporController;
use App\Http\Controllers\Elearning\Guru\SoalTulisController;
use App\Http\Controllers\Elearning\Guru\AbsensiController;
use App\Http\Controllers\Elearning\GuruPiket\AbsensiController as AppAbsensiController;
use App\Http\Controllers\Elearning\GuruPiket\JurnalGuruController as AppJurnalGuruController;
use App\Http\Controllers\Elearning\Siswa\DashboardController as DashboardSiswa;
use App\Http\Controllers\Elearning\Siswa\DataNilaiController;
use App\Http\Controllers\Elearning\Siswa\MainController;
use App\Http\Controllers\Elearning\Siswa\MateriController as MateriSiswaController;
use App\Http\Controllers\Elearning\Kepsek\JurnalGuruController as KepsekJurnalGuruController;
use App\Http\Controllers\Elearning\Siswa\ProfilSiswaController;
use App\Http\Controllers\Elearning\Siswa\UjiKompetensiController;
use App\Http\Controllers\Error\ErrorController;
use App\Http\Controllers\TestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return redirect()->route('dashboard');
});

# START ERROR
Route::controller(ErrorController::class)
	->prefix('error')
	->as('error.')->group(function () {
		Route::get('401', 'error401')->name('401');
	});
# END ERROR

# START AUTH
Route::controller(AuthController::class)
	->as('auth.')->group(function () {
		Route::get('login', 'login')->name('login');
		Route::post('do-login', 'doLogin')->name('doLogin');
		Route::get('logout', 'logout')->name('logout');
	});
# END AUTH

# START MIDDLEWARE AUTH
Route::middleware(['auth'])->group(function () {
	Route::get('dashboard', [DashboardController::class, 'main'])->name('dashboard');

	# START MIDDLEWARE ADMIN
	Route::middleware(['adminElearning'])
		->prefix('admin')
		->as('admin.')
		->group(function () {

			# START MASTER > TAHUN AJARAN
			Route::controller(TahunAjaranController::class)
				->prefix('tahun-ajaran')
				->as('tahunAjaran.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > TAHUN AJARAN

			# START MASTER > DATA GURU
			Route::controller(DataGuruController::class)
				->prefix('data-guru')
				->as('dataGuru.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
					Route::get('/import', 'import')->name('import');
					Route::post('/importUpload', 'importUpload')->name('importUpload');
					Route::post('/importSave', 'importSave')->name('importSave');
				});
			# END MASTER > DATA GURU

			# START MASTER > DATA KELAS
			Route::controller(DataKelasController::class)
				->prefix('data-kelas')
				->as('dataKelas.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > DATA KELAS

			# START MASTER > DATA SISWA
			Route::controller(DataSiswaController::class)
				->prefix('data-siswa')
				->as('dataSiswa.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
					Route::get('/import', 'import')->name('import');
					Route::post('/import-save', 'importSave')->name('importSave');
					Route::post('/reset-password', 'resetPassword')->name('resetPassword');
				});
			# END MASTER > DATA SISWA

			# START MASTER > DATA KELAS SISWA
			Route::controller(KelasSiswaController::class)
				->prefix('kelas-siswa')
				->as('kelasSiswa.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::get('/cari-siswa', 'cariSiswa')->name('cariSiswa');
					Route::get('/naik-kelas', 'naikKelas')->name('naikKelas');
					Route::get('/naik-kelas-form', 'naikKelasForm')->name('naikKelasForm');
					Route::post('/naik-kelas-save', 'naikKelasSave')->name('naikKelasSave');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
					Route::get('/import-naik-kelas-form', 'importNaikKelasForm')->name('importNaikKelasForm');
					Route::post('/read-excel', 'readExcel')->name('readExcel');
					Route::get('/list-siswa', 'listSiswa')->name('listSiswa');
				});
			# END MASTER > DATA KELAS SISWA

			# START MASTER > MATA PELAJARAN
			Route::controller(MataPelajaranController::class)
				->prefix('mata-pelajaran')
				->as('mataPelajaran.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > MATA PELAJARAN

			# START MASTER > MAPEL PENGAMPU
			Route::controller(MapelPengampuController::class)
				->prefix('mapel-pengampu')
				->as('mapelPengampu.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
				});
			# END MASTER > MAPEL PENGAMPU

			# START MASTER > MATERI ELEARNING
			Route::controller(MateriElearningController::class)
				->prefix('materi-elearning')
				->as('materiElearning.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
				});
			# END MASTER > MATERI ELEARNING

			# START MASTER > RAPOR
			Route::controller(AdminRaporController::class)
				->prefix('rapor')
				->as('rapor.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > RAPOR

			# START MASTER > NILAI SISWA
			Route::controller(AdminNilaiSiswaController::class)
				->prefix('nilai-siswa')
				->as('nilaiSiswa.')
				->group(function () {
					Route::get('/', 'main')->name('main');
				});
			# END MASTER > NILAI SISWA

			# START MASTER > MATERI
			Route::controller(AdminMateriController::class)
				->prefix('materi')
				->as('materi.')
				->group(function () {
					Route::get('/', 'main')->name('main');
				});
			# END MASTER > MATERI

			# START MASTER > SOAL
			Route::controller(SoalController::class)
				->prefix('soal')
				->as('soal.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/preview', 'preview')->name('preview');
				});
			# END MASTER > SOAL

			# START MASTER > DOKUMEN
			Route::controller(AdminDokumenController::class)
				->prefix('dokumen')
				->as('dokumen.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > DOKUMEN
		});
	# END MIDDLEWARE ADMIN

	# START MIDDLEWARE GURU
	Route::middleware(['guru'])
		->prefix('guru')
		->as('guru.')
		->group(function () {
			# START PROFIL GURU
			Route::controller(ProfilGuruController::class)
				->prefix('profil-guru')
				->as('profilGuru.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/save', 'save')->name('save');
					Route::post('ubah-password', 'ubahPassword')->name('ubahPassword');
				});
			# END PROFIL GURU

			# START MATERI
			Route::controller(MateriController::class)
				->prefix('materi')
				->as('materi.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MATERI

			# START JURNAL GURU
			Route::controller(JurnalGuruController::class)
				->prefix('jurnal')
				->as('jurnal.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END JURNAL GURU

			# START SOAL TULIS
			Route::controller(SoalTulisController::class)
				->prefix('soal-tulis')
				->as('soalTulis.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/create-soal', 'createSoal')->name('createSoal');
					Route::post('/pertanyaan-form', 'pertanyaanForm')->name('pertanyaanForm');
					Route::post('/pertanyaan-store', 'pertanyaanStore')->name('pertanyaanStore');
					Route::get('/get-pertanyaan-file', 'getPertanyaanFile')->name('getPertanyaanFile');
					Route::post('/store-pertanyaan-file', 'storePertanyaanFile')->name('storePertanyaanFile');
					Route::post('/show-nilai', 'showNilai')->name('showNilai');
					Route::post('/preview', 'preview')->name('preview');
				});
			# END SOAL TULIS

			# START NILAI SISWA
			Route::controller(NilaiSiswaController::class)
				->prefix('nilai-siswa')
				->as('nilaiSiswa.')
				->group(function () {
					Route::get('/', 'main')->name('main');
				});
			# END NILAI SISWA

			# START RAPOR
			Route::controller(RaporController::class)
				->prefix('rapor')
				->as('rapor.')
				->group(function () {
					Route::get('/', 'main')->name('main');
				});
			# END RAPOR

			# START PRAKTEK BAIK GURU
			Route::controller(PraktekBaikGuruController::class)
				->prefix('praktek-baik-guru')
				->as('praktekBaikGuru.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
					Route::post('/aktif', 'aktif')->name('aktif');
					Route::get('/downloadFile/{id?}', 'downloadFile')->name('downloadFile');
				});
			# END PRAKTEK BAIK GURU

			# START DOKUMEN
			Route::controller(DokumenController::class)
				->prefix('dokumen')
				->as('dokumen.')
				->group(function () {
					Route::get('/', 'main')->name('main');
				});
			# END DOKUMEN

			# START ABSEN GURU
			Route::controller(AbsensiController::class)
				->prefix('absen')
				->as('absen.')
				->group(function () {
					Route::post('/absen-masuk','absenMasuk')->name('absenMasuk');
					Route::post('/absen-pulang','absenPulang')->name('absenPulang');
				});
			# END ABSEN GURU
		});
	# END MIDDLEWARE GURU

	# START MIDDLEWARE GURU PIKET
	Route::middleware(['guruPiket'])
		->prefix('guru-piket')
		->as('guruPiket.')
		->group(function () {

			# JURNAL SEMUA GURU
			Route::prefix('jurnal-guru')
				->as('jurnalGuru.')
				->group(function () {
					Route::controller(AppJurnalGuruController::class)
						->group(function () {
							Route::get('/','main')->name('main');
							Route::post('/add','add')->name('add');
							Route::get('/export-pdf','exportPdf')->name('exportPdf');
						});
				});
			# END JURNAL SEMUA GURU

			# ABSENSI SEMUA GURU
			Route::prefix('absensi-guru')
				->as('absensiGuru.')
				->group(function () {
					Route::controller(AppAbsensiController::class)
						->group(function () {
							Route::get('/','main')->name('main');
							Route::post('/open-map','openMap')->name('openMap');
							Route::get('/export-pdf','exportPdf')->name('exportPdf');
						});
				});
			# END ABSENSI SEMUA GURU
		});
	# END MIDDLEWARE GURU PIKET

	# START MIDDLEWARE SISWA
	Route::middleware(['siswa'])
		->prefix('siswa')
		->as('siswa.')
		->group(function () {
			# START DASHBOARD
			Route::get('/', [DashboardSiswa::class, 'main'])->name('dashboard');
			# END DASHBOARD

			# START KERJAKAN SOAL
			Route::controller(MainController::class)
				->as('kerjakan.')
				->group(function () {
					Route::get('kerjakan', 'kerjakan')->name('main');
					Route::get('contentSoal', 'contentSoal')->name('contentSoal');
					Route::post('store', 'store')->name('store');
					Route::post('selesaikan', 'selesaikan')->name('selesaikan');
				});
			# END KERJAKAN SOAL

			# START MATERI
			Route::controller(MateriSiswaController::class)
				->as('materi.')
				->prefix('materi')
				->group(function () {
					Route::get('main', 'main')->name('main');
					Route::get('download-file/{id_materi}', 'downloadFile')->name('downloadFile');
				});
			# END MATERI

			# START PROFIL SISWA
			Route::controller(ProfilSiswaController::class)
				->as('profil.')
				->prefix('profil')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('save', 'save')->name('save');
					Route::post('ubah-password', 'ubahPassword')->name('ubahPassword');
				});
			# END PROFIL SISWA

			# START DATA NILAI
			Route::controller(DataNilaiController::class)
				->as('dataNilai.')
				->prefix('data-nilai')
				->group(function () {
					Route::get('main', 'main')->name('main');
				});
			# END DATA NILAI

			# START DATA NILAI
			Route::controller(UjiKompetensiController::class)
				->as('ujiKompetensi.')
				->prefix('uji-kompetensi')
				->group(function () {
					Route::get('main', 'main')->name('main');
				});
			# END DATA NILAI
		});
	# END MIDDLEWARE SISWA

	Route::middleware(['kepalaSekolah'])
		->prefix('kepsek')
		->as('kepsek.')
		->group(function () {
			# START JURNAL GURU
			Route::controller(KepsekJurnalGuruController::class)
				->prefix('jurnal')
				->as('jurnal.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::get('/export-pdf','exportPdf')->name('exportPdf');
				});
			# END JURNAL GURU

			# START MASTER > TAHUN AJARAN
			Route::controller(TahunAjaranController::class)
				->prefix('tahun-ajaran')
				->as('tahunAjaran.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > TAHUN AJARAN

			# START MASTER > DATA GURU
			Route::controller(DataGuruController::class)
				->prefix('data-guru')
				->as('dataGuru.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
					Route::get('/import', 'import')->name('import');
					Route::post('/importUpload', 'importUpload')->name('importUpload');
					Route::post('/importSave', 'importSave')->name('importSave');
				});
			# END MASTER > DATA GURU

			# START MASTER > DATA KELAS
			Route::controller(DataKelasController::class)
				->prefix('data-kelas')
				->as('dataKelas.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/add', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > DATA KELAS

			# START MASTER > DATA SISWA
			Route::controller(DataSiswaController::class)
				->prefix('data-siswa')
				->as('dataSiswa.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
					Route::get('/import', 'import')->name('import');
					Route::post('/import-save', 'importSave')->name('importSave');
				});
			# END MASTER > DATA SISWA

			# START MASTER > DATA KELAS SISWA
			Route::controller(KelasSiswaController::class)
				->prefix('kelas-siswa')
				->as('kelasSiswa.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::get('/cari-siswa', 'cariSiswa')->name('cariSiswa');
					Route::get('/naik-kelas', 'naikKelas')->name('naikKelas');
					Route::get('/naik-kelas-form', 'naikKelasForm')->name('naikKelasForm');
					Route::post('/naik-kelas-save', 'naikKelasSave')->name('naikKelasSave');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > DATA KELAS SISWA

			# START MASTER > MATA PELAJARAN
			Route::controller(MataPelajaranController::class)
				->prefix('mata-pelajaran')
				->as('mataPelajaran.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > MATA PELAJARAN

			# START MASTER > MAPEL PENGAMPU
			Route::controller(MapelPengampuController::class)
				->prefix('mapel-pengampu')
				->as('mapelPengampu.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
				});
			# END MASTER > MAPEL PENGAMPU

			# START MASTER > MATERI ELEARNING
			Route::controller(MateriElearningController::class)
				->prefix('materi-elearning')
				->as('materiElearning.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
				});
			# END MASTER > MATERI ELEARNING

			# START MASTER > RAPOR
			Route::controller(AdminRaporController::class)
				->prefix('rapor')
				->as('rapor.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > RAPOR

			# START MASTER > NILAI SISWA
			Route::controller(AdminNilaiSiswaController::class)
				->prefix('nilai-siswa')
				->as('nilaiSiswa.')
				->group(function () {
					Route::get('/', 'main')->name('main');
				});
			# END MASTER > NILAI SISWA

			# START MASTER > MATERI
			Route::controller(AdminMateriController::class)
				->prefix('materi')
				->as('materi.')
				->group(function () {
					Route::get('/', 'main')->name('main');
				});
			# END MASTER > MATERI

			# START MASTER > SOAL
			Route::controller(SoalController::class)
				->prefix('soal')
				->as('soal.')
				->group(function () {
					Route::get('/', 'main')->name('main');
				});
			# END MASTER > SOAL

			# START MASTER > SOAL
			Route::controller(AdminDokumenController::class)
				->prefix('dokumen')
				->as('dokumen.')
				->group(function () {
					Route::get('/', 'main')->name('main');
					Route::post('/', 'add')->name('add');
					Route::post('/save', 'save')->name('save');
					Route::post('/delete', 'delete')->name('delete');
				});
			# END MASTER > SOAL
		});
});
# END MIDDLEWARE AUTH
Route::get('/import', [TestController::class, 'import'])->name('import');
// Route::get('/download', [DashboardController::class,'download'])->download();
Route::controller(PraktekBaikGuruController::class)
	->prefix('praktek-baik-guru')
	->as('praktekBaikGuru.')
	->group(function () {
		Route::get('/downloadFile/{id?}', 'downloadFile')->name('downloadFile');
	});