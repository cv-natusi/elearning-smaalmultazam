@php
if (!isset($title)) {
	$title = '...';
}
@endphp

<div class="sidebar-wrapper sds" data-simplebar="true">
	<div class="sidebar-header">
		<div>
			<img src="{{isset($identitas)?asset('uploads/identitas/'.$identitas->logo_kiri):asset('admin/assets/images/logo-profile.png')}}" width="30" alt="logo icon">
		</div>
		<div>
			<h5 class="logo-text" style="font-size: 14px;">{{isset($identitas)?$identitas->nama_web:'SMAS AL MULTAZAM'}}</h5>
		</div>
		<div class="toggle-icon ms-auto"><i class='bx bx-chevron-left-circle'></i></div>
	</div>
	<ul class="metismenu" id="menu">
		@auth
		@if (Auth::User()->level_user=='4') <!-- Siswa -->
			<li class="{{ ($title == 'Dashboard') ? 'mm-active' : ''}}">
				<a href="{{route('siswa.dashboard')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-home-circle'></i>
					</div>
					<div class="menu-title">Dashboard</div>
				</a>
			</li>
			<li class="{{ ($title == 'Profil Siswa') ? 'mm-active' : ''}}">
				<a href="{{route('siswa.profil.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-user-pin'></i>
					</div>
					<div class="menu-title">Profil Siswa</div>
				</a>
			</li>
			<li class="menu-label">Content</li>
			<li class="{{ ($title == 'Materi Elearning') ? 'mm-active' : ''}}">
				<a href="{{route('siswa.materi.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Materi Elearning</div>
				</a>
			</li>
			<li class="{{ ($title == 'Uji Kompetensi') ? 'mm-active' : ''}}">
				<a href="{{route('siswa.ujiKompetensi.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Uji Kompetensi</div>
				</a>
			</li>
			<li class="{{ ($title == 'Data Nilai') ? 'mm-active' : ''}}">
				<a href="{{route('siswa.dataNilai.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Data Nilai</div>
				</a>
			</li>
		@endif

		@if (in_array(Auth::User()->level_user,[2,3,5]))
			<li class="{{ ($title == 'Dashboard') ? 'mm-active' : ''}}">
				<a href="{{route('dashboard')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-home-circle'></i>
					</div>
					<div class="menu-title">Dashboard</div>
				</a>
			</li>
		@endif

		@if (Auth::User()->level_user=='5')
			<li class="{{ (in_array($title, ['Tahun Ajaran','Data Guru','Data Kelas','Data Siswa','Mata Pelajaran'])) ? 'mm-active' : ''}}">
				<a href="javascript:;" class="has-arrow">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-folder'></i>
					</div>
					<div class="menu-title">Data Master</div>
				</a>
				<ul>
					<li class="{{ ($title == 'Tahun Ajaran') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('kepsek.tahunAjaran.main')}}"><i class="bx bx-radio-circle"></i>Tahun Ajaran</a>
					</li>
					<li class="{{ ($title == 'Mata Pelajaran') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('kepsek.mataPelajaran.main')}}"><i class="bx bx-radio-circle"></i>Mata Pelajaran</a>
					</li>
					<li class="{{ ($title == 'Data Guru') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('kepsek.dataGuru.main')}}"><i class="bx bx-radio-circle"></i>Data Guru</a>
					</li>
					<li class="{{ ($title == 'Data Kelas') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('kepsek.dataKelas.main')}}"><i class="bx bx-radio-circle"></i>Data Kelas</a>
					</li>
					<li class="{{ ($title == 'Data Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('kepsek.dataSiswa.main')}}"><i class="bx bx-radio-circle"></i>Data Siswa</a>
					</li>
					<li class="{{ ($title == 'Data Kelas Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('kepsek.kelasSiswa.main')}}"><i class="bx bx-radio-circle"></i>Data Kelas Siswa</a>
					</li>
					<li class="{{ ($title == 'Data Mapel Pengampu') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('kepsek.mapelPengampu.main')}}"><i class="bx bx-radio-circle"></i>Data Mapel Pengampu</a>
					</li>
				</ul>
			</li>
			<li class="menu-label">Content</li>
			<li class="{{ ($title == 'Materi') ? 'mm-active' : ''}}">
				<a href="{{route('kepsek.materi.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Materi Elearning</div>
				</a>
			</li>
			<li class="{{ ($title == 'Soal') ? 'mm-active' : ''}}">
				<a href="{{route('kepsek.soal.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Soal Elearning</div>
				</a>
			</li>
			<li class="{{ ($title == 'Nilai Siswa') ? 'mm-active' : ''}}">
				<a href="{{route('kepsek.nilaiSiswa.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Nilai Siswa</div>
				</a>
			</li>
			<li class="{{ ($title == 'E-RAPOR') ? 'mm-active' : ''}}">
				<a href="{{route('kepsek.rapor.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-book'></i>
					</div>
					<div class="menu-title"><i>e-</i> Rapor</div>
				</a>
			</li>
			<li class="{{ ($title == 'Dokumen') ? 'mm-active' : ''}}">
				<a href="{{route('kepsek.dokumen.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Dokumen</div>
				</a>
			</li>
			<li class="{{ ($title == 'Jurnal Guru') ? 'mm-active' : ''}}">
				<a href="{{route('kepsek.jurnal.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Jurnal Guru</div>
				</a>
			</li>
		@endif

		@if (in_array(Auth::User()->level_user,[2])) <!-- Admin Elearning -->
			<li class="{{ (in_array($title, ['Tahun Ajaran','Data Guru','Data Kelas','Data Siswa','Mata Pelajaran'])) ? 'mm-active' : ''}}">
				<a href="javascript:;" class="has-arrow">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-folder'></i>
					</div>
					<div class="menu-title">Data Master</div>
				</a>
				<ul>
					<li class="{{ ($title == 'Tahun Ajaran') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.tahunAjaran.main')}}"><i class="bx bx-radio-circle"></i>Tahun Ajaran</a>
					</li>
					<li class="{{ ($title == 'Mata Pelajaran') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.mataPelajaran.main')}}"><i class="bx bx-radio-circle"></i>Mata Pelajaran</a>
					</li>
					<li class="{{ ($title == 'Data Guru') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.dataGuru.main')}}"><i class="bx bx-radio-circle"></i>Data Guru</a>
					</li>
					<li class="{{ ($title == 'Data Kelas') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.dataKelas.main')}}"><i class="bx bx-radio-circle"></i>Data Kelas</a>
					</li>
					<li class="{{ ($title == 'Data Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.dataSiswa.main')}}"><i class="bx bx-radio-circle"></i>Data Siswa</a>
					</li>
					<li class="{{ ($title == 'Data Kelas Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.kelasSiswa.main')}}"><i class="bx bx-radio-circle"></i>Data Kelas Siswa</a>
					</li>
					<li class="{{ ($title == 'Data Mapel Pengampu') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('admin.mapelPengampu.main')}}"><i class="bx bx-radio-circle"></i>Data Mapel Pengampu</a>
					</li>
				</ul>
			</li>
			<li class="menu-label">Content</li>
			<li class="{{ ($title == 'Materi') ? 'mm-active' : ''}}">
				<a href="{{route('admin.materi.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Materi Elearning</div>
				</a>
			</li>
			<li class="{{ ($title == 'Soal') ? 'mm-active' : ''}}">
				<a href="{{route('admin.soal.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Soal Elearning</div>
				</a>
			</li>
			<li class="{{ ($title == 'Nilai Siswa') ? 'mm-active' : ''}}">
				<a href="{{route('admin.nilaiSiswa.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Nilai Siswa</div>
				</a>
			</li>
			<li class="{{ ($title == 'E-RAPOR') ? 'mm-active' : ''}}">
				<a href="{{route('admin.rapor.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-book'></i>
					</div>
					<div class="menu-title"><i>e-</i> Rapor</div>
				</a>
			</li>
			<li class="{{ ($title == 'Dokumen') ? 'mm-active' : ''}}">
				<a href="{{route('admin.dokumen.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Dokumen</div>
				</a>
			</li>
		@endif

		@if (in_array(Auth::User()->level_user,[3])) <!-- Guru -->
			
			<li class="{{ ($title == 'Profil Guru') ? 'mm-active' : ''}}">
				<a href="{{route('guru.profilGuru.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-globe'></i>
					</div>
					<div class="menu-title">Profil Guru</div>
				</a>
			</li>
			<li class="{{ (in_array($title, ['Materi','Soal','Soal Listening','Pengerjaan Siswa','Nilai Siswa'])) ? 'mm-active' : ''}}">
				<a href="javascript:;" class="has-arrow">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-folder'></i>
					</div>
					<div class="menu-title">Elearning</div>
				</a>
				<ul>
					<li class="{{ ($title == 'Materi') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('guru.materi.main')}}"><i class="bx bx-radio-circle"></i>Materi</a>
					</li>
					<li class="{{ ($title == 'Soal') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('guru.soalTulis.main')}}"><i class="bx bx-radio-circle"></i>Soal</a>
					</li>
					{{-- <li class="{{ ($title == 'Soal Listening') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('guru.soalTulis.main')}}"><i class="bx bx-radio-circle"></i>Soal Listening</a>
					</li> --}}
					{{-- <li class="{{ ($title == 'Pengerjaan Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('main.menuUtama.prestasiSiswa.main')}}"><i class="bx bx-radio-circle"></i>Pengerjaan Siswa</a>
					</li> --}}
					<li class="{{ ($title == 'Nilai Siswa') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('guru.nilaiSiswa.main')}}"><i class="bx bx-radio-circle"></i>Nilai Siswa</a>
					</li>
					<li class="{{ ($title == 'E-RAPOR') ? 'mm-active' : ''}}">
						<a style="color: #fff" href="{{route('guru.rapor.main')}}"><i class="bx bx-radio-circle"></i><i>e-</i> Rapor</a>
					</li>
				</ul>
			</li>
			<li class="menu-label">Content</li>
			<li class="{{ ($title == 'Dokumen') ? 'mm-active' : ''}}">
				<a href="{{route('guru.dokumen.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Dokumen</div>
				</a>
			</li>
			<li class="{{ ($title == 'Jurnal Guru') ? 'mm-active' : ''}}">
				<a href="{{route('guru.jurnal.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Jurnal Guru</div>
				</a>
			</li>
			<li class="{{ ($title == 'Praktek Baik Guru') ? 'mm-active' : ''}}">
				<a href="{{route('guru.praktekBaikGuru.main')}}">
					<div class="parent-icon">
						<i style="color: #fff" class='bx bx-file'></i>
					</div>
					<div class="menu-title">Praktek Baik Guru</div>
				</a>
			</li>
			@if (Auth::user()->piket)
				<li class="menu-label">Guru Piket</li>
				<li class="{{ ($title == 'Jurnal Semua Guru') ? 'mm-active' : ''}}">
					<a href="{{route('guruPiket.jurnalGuru.main')}}">
						<div class="parent-icon">
							<i style="color: #fff" class='bx bx-file'></i>
						</div>
						<div class="menu-title">Jurnal Semua Guru</div>
					</a>
				</li>
				<li class="{{ ($title == 'Absensi Semua Guru') ? 'mm-active' : ''}}">
					<a href="{{route('guruPiket.absensiGuru.main')}}">
						<div class="parent-icon">
							<i style="color: #fff" class='bx bx-file'></i>
						</div>
						<div class="menu-title">Absensi Semua Guru</div>
					</a>
				</li>
			@endif
		@endif

		@endauth
	</ul>
	<!--end navigation-->
</div>
