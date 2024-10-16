@extends('main.layouts.index')

@php
$tambah = true;
@endphp

@push('style')
<link href="{{ asset('admin/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('zoom/css/jquery.pan.css')}}"><!--zoomImage-->
<style>
	.gradient-green-yellow {
		background-color: #45ab73;
		background-image: linear-gradient(74deg, #45ab73 0%, #e4e07f 75%, #ffffff 100%);
		color: #ffffff;
	}
</style>
@endpush

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if ($tambah)
				Tambah
				@else
				Edit
				@endif
				Profil Guru
			</div>
			<div class="card-body">
				<form id="formProfilGuru">
					<div class="row">
						<input type="hidden" name="id_guru" @isset($guru->id_guru) value="{{$guru->id_guru}}" @endisset>
						<div class="col-12 col-md-6">
							<div class="mb-3">
								<label for="nama" class="form-label">Nama Lengkap *</label>
								<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Lengkap" @isset($guru->nama) value="{{$guru->nama}}" @endisset>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3">
								<label for="nip" class="form-label">NIP</label>
								<input type="text" class="form-control" name="nip" id="nip" placeholder="123xxxxx" @isset($guru->nip) value="{{$guru->nip}}" @endisset>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3">
								<label for="no_tlp" class="form-label">No Telepon *</label>
								<input type="text" class="form-control" name="no_tlp" id="no_tlp" @isset($guru->no_tlp) value="{{$guru->no_tlp}}" @endisset>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3">
								<label for="gender" class="form-label">Jenis Kelamin *</label>
								<select class="form-control select2" name="gender" id="gender">
									<option value="">-PILIH-</option>
									<option value="Laki-Laki" @isset($guru->gender) @if($guru->gender=='Laki-Laki') selected @endif @endisset>Laki-Laki</option>
									<option value="Perempuan" @isset($guru->gender) @if($guru->gender=='Perempuan') selected @endif @endisset>Perempuan</option>
								</select>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3">
								<label for="tmp_lahir" class="form-label">Tempat Lahir *</label>
								<input type="text" class="form-control" name="tmp_lahir" id="tmp_lahir" @isset($guru->tmp_lahir) value="{{$guru->tmp_lahir}}" @endisset>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="mb-3">
								<label for="tgl_lahir" class="form-label">Tanggal Lahir *</label>
								<input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" @isset($guru->tgl_lahir) value="{{$guru->tgl_lahir}}" @endisset>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label for="foto" class="form-label">Foto Guru *</label>
								<center class="mb-3">
									<a class="pan" id="btnOutPut" data-big="@isset($guru->foto){!! url('uploads/guru/'.$guru->foto) !!}@endisset">
										<img id="outPut" @isset($guru->foto) src="{!! url('uploads/guru/'.$guru->foto) !!}" @endisset class="rounded mx-auto d-block responsive @isset($guru) img-thumbnail w-50 @endisset">
									</a>
								</center>
								<input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="loadFile(event)">
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label for="alamat" class="form-label">Alamat *</label>
								<textarea class="form-control" name="alamat" id="alamat" cols="30" rows="5">@isset($guru->alamat) {{$guru->alamat}} @endisset</textarea>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="row p-2">
								<div class="col-6 p-2 gradient-green-yellow mb-2">
									<label for="">Tugas Utama</label>
								</div>
								<div class="col-6"></div>
								{{-- <div class="col-5">
									<div class="mb-3">
										<label for="kelas" class="form-label">Kelas</label>
										<select name="kelas[]" id="kelas" class="form-control selectpicker select2 multi-select" multiple>
											<option value="">-PILIH-</option>
										</select>
									</div>
								</div>
								<div class="col-5">
									<div class="mb-3">
										<label for="mapel" class="form-label">Mapel</label>
										<select name="mapel[]" id="mapel" class="form-control selectpicker select2 multi-select" multiple>
											<option value="">-PILIH-</option>
										</select>
									</div>
								</div>
								<div class="col-2 pt-4">
									<button type="button" class="btn btn-success"><i class='bx bx-plus'></i></button>
								</div> --}}
								<div class="p-1">
									<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTableMapel">
										<thead>
											<tr>
												<th>No</th>
												<th>Kelas</th>
												<th>Mapel</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($tugas_utama as $item)
											<tr>
												<td>{{$loop->index+1}}</td>
												<td>{{$item->kelas->nama_kelas}}</td>
												<td>{{$item->mata_pelajaran->nama_mapel}}</td>
												<td>
													-
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="row p-2">
								<div class="col-6 p-2 gradient-green-yellow mb-2">
									<label for="">Tugas Tambahan</label>
								</div>
								<div class="col-6"></div>
								{{-- <div class="col-10">
									<div class="mb-3">
										<label for="tugas" class="form-label">Nama Tugas</label>
										<select name="tugas[]" id="tugas" class="form-control selectpicker select2 multi-select" multiple>
											<option value="">-PILIH-</option>
										</select>
									</div>
								</div>
								<div class="col-2 pt-4">
									<button type="button" class="btn btn-success"><i class='bx bx-plus'></i></button>
								</div> --}}
								<div class="p-1">
									<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTableTambahan">
										<thead>
											<tr>
												<th>No</th>
												<th>Tugas Tambahan</th>
												<th>Aksi</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($tugas_tambahan as $item)
											<tr>
												<td>{{$loop->index+1}}</td>
												<td>{{$item->nama_tugas}}</td>
												<td>
													-
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</div>
							</div>
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
<script src="{{ asset('admin/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{asset('zoom/js/jquery.pan.js')}}"></script><!--zoomImage-->
<script>
	var modalPassword = new bootstrap.Modal(document.getElementById('ubahPasswordModal'), {
		backdrop: 'static',
		keyboard: false
	})
	$('.pan').pan()
	$(document).ready(function () {
		$('.select2').select2({
			theme: 'bootstrap-5',
		});
		$('#dataTableMapel').DataTable()
		$('#dataTableTambahan').DataTable()

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

	$('.btnSimpan').click((e) => {
		e.preventDefault()
		var data = new FormData($('#formProfilGuru')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("guru.profilGuru.save")}}',
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
				url: '{{route("guru.profilGuru.ubahPassword")}}',
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