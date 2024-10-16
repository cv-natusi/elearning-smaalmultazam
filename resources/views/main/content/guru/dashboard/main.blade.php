@extends('main.layouts.index')

@section('content')
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
		<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
	</symbol>
</svg>
@if ($jurnal=='')
<div class="row">
	<div class="col-12">
		<div class="alert alert-danger d-flex align-items-center" role="alert">
			<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
			<div>
				Anda belum mengisi jurnal hari ini (<a href="{{route('guru.jurnal.main')}}">Klik disini untuk mengisi!</a>)
			</div>
		</div>
	</div>
</div>
@else
<div class="row">
	<div class="col-12">
		<div class="alert alert-success d-flex align-items-center" role="alert">
			<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
			<div>
				Anda sudah mengisi jurnal hari ini, Keren!
			</div>
		</div>
	</div>
</div>
@endif
<div class="d-flex flex-column-reverse">
	<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-success">
							<h6 class="my-1">{{$materi}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Materi</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-secondary">
							<h6 class="my-1">{{$soal}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Soal</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-primary">
							<h6 class="my-1">{{$mapel}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Mapel Diampuh</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-warning">
							<h6 class="my-1">{{$praktek}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Praktek Baik Guru</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="card radius-10">
				<div class="card-header bg-light-primary">
					<h4>Absensi</h4>
				</div>
				<div class="card-body">
					<div class="row d-flex d-flex-reverse">
						<div class="col-12 col-md-6">
							<div class="card">
								<div class="card-body bg-white">
									<div class="d-md-flex align-items-center">
										<div class="d-flex align-items-center">
											<div class="widgets-icons bg-light-success">
												<h6 class="my-1"><i class='bx bx-log-in'></i></h6>
											</div>
											<div class="me-auto ms-4">
												<h6 class="my-1">Kehadiran</h6>
												<h6 class="my-1"><strong>{{ \Carbon\Carbon::parse(date('Y-m-d'))->isoFormat('dddd, DD MMMM YYYY') }}</strong></h6>
												<h6 class="my-1 text-primary"><strong>{{(isset($absensi)&&$absensi->absen_datang)?date('H:i:s',strtotime($absensi->absen_datang)):'--:--:--'}}</strong></h6>
											</div>
										</div>
										<div class="ms-auto">
											<button class="btn {{(isset($absensi)&&$absensi->absen_datang)?'btn-secondary':'btn-success'}} shadow btnHadir" @if (isset($absensi)&&$absensi->absen_datang) disabled @endif>
												Absen Kehadiran
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="card">
								<div class="card-body">
									<div class="d-md-flex align-items-center">
										<div class="d-flex align-items-center">
											<div class="widgets-icons bg-light-danger">
												<h6 class="my-1"><i class='bx bx-log-out'></i></h6>
											</div>
											<div class="me-auto ms-4">
												<h6 class="my-1">Pulang</h6>
												<h6 class="my-1"><strong>{{ \Carbon\Carbon::parse(date('Y-m-d'))->isoFormat('dddd, DD MMMM YYYY') }}</strong></h6>
												<h6 class="my-1 text-primary"><strong>{{(isset($absensi)&&$absensi->absen_pulang)?date('H:i:s',strtotime($absensi->absen_pulang)):'--:--:--'}}</strong></h6>
											</div>
										</div>
										<div class="ms-auto">
											<button class="btn {{((isset($absensi)&&$absensi->absen_datang&&$absensi->absen_pulang)||!isset($absensi))?'btn-secondary':'btn-danger'}} shadow btnPulang" @if ((isset($absensi)&&$absensi->absen_datang&&$absensi->absen_pulang)||!isset($absensi)) disabled @endif>
												Absen Pulang
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('script')
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var routeAbsenMasuk = "{{route('guru.absen.absenMasuk')}}";
	var routeAbsenPulang = "{{route('guru.absen.absenPulang')}}";
	var latitude = "";
	var longitude = "";
	
	$('.btnHadir').click(async(e)=>{
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(absenMasuk,showError)
		} else {
			swalError('Browser tidak support menggunakan lokasi, gunakan browser lain!');
		}
	});

	$('.btnPulang').click(async(e)=>{
		if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(absenPulang,showError)
		} else {
			swalError('Browser tidak support menggunakan lokasi, gunakan browser lain!');
		}
	});
	
	function absenMasuk(position) {
		if (!position.coords.latitude||!position.coords.longitude) {
			swalError("Gagal mengambil lokasi saat ini!")
			return
		}
		Swal.fire({
			title: "Apakah Anda Yakin?",
			text: "Ingin melakukan absen pukul {{date('H:i')}}!",
			icon: "info",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Lakukan!"
		}).then((result) => {
			if (result.isConfirmed) {
				$('.btnHadir').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
				var url = routeAbsenMasuk
				$.post(url,{latitude:position.coords.latitude,longitude:position.coords.longitude})
				.done(function(data){
					console.log(data);
					if(data.status == 'success'){
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: data.message,
							showConfirmButton: false,
							timer: 1200
						})
						setTimeout(async ()=>{
							// await dataTable($('#status').val())
							// $('#dataTabel').DataTable().ajax.reload()
							location.reload()
						}, 1100);
					} else {
						Swal.fire({
							icon: 'warning',
							title: 'Whoops',
							text: data.message,
							showConfirmButton: false,
							timer: 1300,
						})
					}
					$('.btnHadir').attr('disabled',false).html('Absen Kehadiran')
				})
				.fail(() => {
					Swal.fire({
						icon: 'error',
						title: 'Whoops..',
						text: 'Terjadi kesalahan silahkan ulangi kembali',
						showConfirmButton: false,
						timer: 1300,
					})
					$('.btnHadir').attr('disabled',false).html('Absen Kehadiran')
				})
			}
		});
	}

	function absenPulang(position) {
		if (!position.coords.latitude||!position.coords.longitude) {
			swalError("Gagal mengambil lokasi saat ini!")
			return
		}
		Swal.fire({
			title: "Apakah Anda Yakin?",
			text: "Ingin melakukan absen pulang pukul {{date('H:i')}}!",
			icon: "info",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Lakukan!"
		}).then((result) => {
			if (result.isConfirmed) {
				$('.btnPulang').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
				var url = routeAbsenPulang
				$.post(url,{latitude:position.coords.latitude,longitude:position.coords.longitude})
				.done(function(data){
					console.log(data);
					if(data.status == 'success'){
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: data.message,
							showConfirmButton: false,
							timer: 1200
						})
						setTimeout(async ()=>{
							// await dataTable($('#status').val())
							// $('#dataTabel').DataTable().ajax.reload()
							location.reload()
						}, 1100);
					} else {
						Swal.fire({
							icon: 'warning',
							title: 'Whoops',
							text: data.message,
							showConfirmButton: false,
							timer: 1300,
						})
					}
					$('.btnPulang').attr('disabled',false).html('Absen Kehadiran')
				})
				.fail(() => {
					Swal.fire({
						icon: 'error',
						title: 'Whoops..',
						text: 'Terjadi kesalahan silahkan ulangi kembali',
						showConfirmButton: false,
						timer: 1300,
					})
					$('.btnPulang').attr('disabled',false).html('Absen Kehadiran')
				})
			}
		});
	}
	
	function showPosition(position) {
		latitude = position.coords.latitude;
		longitude = position.coords.longitude;
	}
	
	function showError(error) {
		switch(error.code) {
			case error.PERMISSION_DENIED:
			swalError("Mohon menyalakan akses lokasi browser anda!")
			break;
			case error.POSITION_UNAVAILABLE:
			swalError("Informasi lokasi anda tidak diketahui, coba lagi!")
			break;
			case error.TIMEOUT:
			swalError("The request to get user location timed out.")
			break;
			case error.UNKNOWN_ERROR:
			swalError("An unknown error occurred.")
			break;
		}
	}
	
	function swalError(param) {  
		Swal.fire({
			icon: 'error',
			title: 'Gagal melakukan absensi!',
			text: param,
		})
	}
</script>
@endpush