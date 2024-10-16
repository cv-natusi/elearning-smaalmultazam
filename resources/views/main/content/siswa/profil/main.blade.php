@extends('main.layouts.index')

@php
$tambah = true;
@endphp

@push('style')
<link rel="stylesheet" type="text/css" href="{{asset('zoom/css/jquery.pan.css')}}"><!--zoomImage-->
@endpush

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				Profil Siswa
			</div>
			<div class="card-body">
				<form id="formDataSiswa">
					<div class="row">
						<input type="hidden" name="id" @isset($siswa) value="{{$siswa->id_siswa}}" @endisset>
						<div class="mb-3 col-12">
							<label for="nama" class="form-label">Nama Siswa *</label>
							<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Siswa" @isset($siswa) value="{{$siswa->nama}}" @endisset>
						</div>
						<div class="mb-3 col-12 col-md-6">
							<label for="tempat_lahir" class="form-label">Tempat Lahir *</label>
							<input type="text" class="form-control" name="tempat_lahir" id="tempat_lahir" placeholder="Tempat Lahir" @isset($siswa) value="{{$siswa->tmp_lahir}}" @endisset>
						</div>
						<div class="mb-3 col-12 col-md-3">
							<label for="tanggal_lahir" class="form-label">Tanggal Lahir *</label>
							<input type="date" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Tanggal Lahir" @isset($siswa) value="{{$siswa->tgl_lahir}}" @endisset>
						</div>
						<div class="mb-3 col-12 col-md-3">
							<label class="form-label">Jenis Kelamin *</label>
							<select name="jenis_kelamin" class="form-select select2" id="jenis_kelamin">
								<option value="">-PILIH-</option>
								<option value="Laki-Laki" @isset($siswa) @if($siswa->gender=='Laki-Laki') selected @endif @endisset>Laki-Laki</option>
								<option value="Perempuan" @isset($siswa) @if($siswa->gender=='Perempuan') selected @endif @endisset>Perempuan</option>
							</select>
						</div>
						<div class="mb-3 col-12 col-md-6">
							<label for="nama_ayah" class="form-label">Nama Ayah *</label>
							<input type="text" class="form-control" name="nama_ayah" id="nama_ayah" placeholder="Nama Ayah" @isset($siswa) value="{{$siswa->nama_ayah}}" @endisset>
						</div>
						<div class="mb-3 col-12 col-md-6">
							<label for="nama_ibu" class="form-label">Nama Ibu *</label>
							<input type="text" class="form-control" name="nama_ibu" id="nama_ibu" placeholder="Nama Ibu" @isset($siswa) value="{{$siswa->nama_ibu}}" @endisset>
						</div>
						<div class="mb-3 col-12 col-md-6">
							<label for="no_telp" class="form-label">Nomor Telepon *</label>
							<input type="text" class="form-control" name="no_telp" id="no_telp" placeholder="Nomor Telepon" @isset($siswa) value="{{$siswa->no_tlp}}" @endisset>
						</div>
						<div class="mb-3 col-12">
							<label for="alamat" class="form-label">Alamat *</label>
							<textarea class="form-control" name="alamat" id="alamat" cols="30" rows="10">@isset($siswa) {{$siswa->alamat}} @endisset</textarea>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label for="foto" class="form-label">Foto *</label>
								<center class="mb-3">
									<a class="pan" id="btnOutPut" data-big="@isset($siswa->foto){!! url('uploads/siswa/'.$siswa->foto) !!}@endisset">
										<img id="outPut" @isset($siswa->foto) src="{!! url('uploads/siswa/'.$siswa->foto) !!}" @endisset class="rounded mx-auto d-block responsive @isset($siswa) img-thumbnail w-50 @endisset">
									</a>
								</center>
								<input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="loadFile(event)">
							</div>
						</div>
						<hr>
						<div class="d-flex gap-2">
							<button class="btn btn-primary px-4 btnSimpan">SIMPAN</button>
							<button type="button" class="btn btn-danger btnModalPassword" data-toggle="modal" data-target="#ubahPasswordModal">
								<i class='bx bx-key'></i> 
								Ubah Password
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="ubahPasswordModal" tabindex="-1" aria-labelledby="ubahPasswordModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="ubahPasswordModalLabel">UBAH PASSWORD</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formPassword">
					<div class="row">
						<div class="mb-3 col-12">
							<label for="password_baru" class="form-label">Password Baru *</label>
							<div class="input-group" id="show_hide_password">
								<input type="password" class="form-control border-end-0" id="password_baru" name="password_baru" placeholder="Password"> <a href="javascript:;" class="input-group-text bg-transparent" onclick="ubahPassword(this)"><i class='bx bx-hide'></i></a>
							</div>
						</div>
						<div class="mb-3 col-12">
							<label for="ulangi_password_baru" class="form-label">Ulangi Password Baru *</label>
							<div class="input-group" id="show_hide_password">
								<input type="password" class="form-control border-end-0" id="ulangi_password_baru" name="ulangi_password_baru" placeholder="Password"> <a href="javascript:;" class="input-group-text bg-transparent" onclick="ubahPassword(this)"><i class='bx bx-hide'></i></a>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btnSimpanPassword">SIMPAN</button>
			</div>
		</div>
	</div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('zoom/js/jquery.pan.js')}}"></script><!--zoomImage-->
<script>
	var modalPassword = new bootstrap.Modal(document.getElementById('ubahPasswordModal'), {
		backdrop: 'static',
		keyboard: false
	})
	$(document).ready(function () {
		$('.select2').select2({
			theme: 'bootstrap-5',
		});
	})

	function loadFile(event) {
		var btn = $('#btnOutPut')[0] // html DOM Object
		var outPut = $('#outPut')[0]
		outPut.src = URL.createObjectURL(event.target.files[0])
		outPut.onload = function(){
			URL.revokeObjectURL(outPut.src)
		}
		btn = $('#btnOutPut').attr('data-big',URL.createObjectURL(event.target.files[0]))
		$('#outPut').addClass('img-thumbnail')
	};

	$('.btnKembali').click((e)=>{
		e.preventDefault()
		$('.other-page').empty()
		$('.main-page').fadeIn()
	})

	$('.btnSimpan').click((e) => {
		e.preventDefault()
		var data = new FormData($('#formDataSiswa')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("siswa.profil.save")}}',
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					if(data.status=='success'){
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: data.message,
							showConfirmButton: false,
							timer: 1200
						})
						setTimeout(()=>{
							location.reload()
						}, 1100);
						// location.reload()
					}else{
						Swal.fire({
							icon: 'warning',
							title: 'Whoops',
							text: data.message,
							showConfirmButton: false,
							timer: 1300,
						})
					}
					$('.btnSimpan').attr('disabled',false).html('SIMPAN')
				}
			}).fail(()=>{
				Swal.fire({
					icon: 'error',
					title: 'Whoops..',
					text: 'Terjadi kesalahan silahkan ulangi kembali',
					showConfirmButton: false,
					timer: 1300,
				})
				$('.btnSimpan').attr('disabled',false).html('SIMPAN')
			})
	})

	$('.btnModalPassword').click((e) => {
		modalPassword.show()
	})

	function ubahPassword(ini){
		const type = $(ini).prev().attr('type')
		// const type = $('#input').attr('type')
		if(type==='text'){
			$(ini).prev().attr('type', 'password')
			$(ini).children('i').addClass('bx-hide')
			$(ini).children('i').removeClass('bx-show')
			return // die()
		}
		$(ini).prev().attr('type', 'text')
		$(ini).children('i').removeClass('bx-hide')
		$(ini).children('i').addClass('bx-show')
	}

	$('.btnSimpanPassword').click((e) => {
		e.preventDefault()
		var data = new FormData($('#formPassword')[0])
		$('.btnSimpanPassword').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("siswa.profil.ubahPassword")}}',
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					if(data.status=='success'){
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: data.message,
							showConfirmButton: false,
							timer: 1200
						})
						setTimeout(()=>{
							location.reload()
						}, 1100);
						// location.reload()
					}else{
						Swal.fire({
							icon: 'warning',
							title: 'Whoops',
							text: data.message,
							showConfirmButton: false,
							timer: 1300,
						})
					}
					$('.btnSimpanPassword').attr('disabled',false).html('SIMPAN')
				}
			}).fail(()=>{
				Swal.fire({
					icon: 'error',
					title: 'Whoops..',
					text: 'Terjadi kesalahan silahkan ulangi kembali',
					showConfirmButton: false,
					timer: 1300,
				})
				$('.btnSimpanPassword').attr('disabled',false).html('SIMPAN')
			})
	})

</script>
@endpush