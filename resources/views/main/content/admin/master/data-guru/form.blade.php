<link rel="stylesheet" type="text/css" href="{{asset('zoom/css/jquery.pan.css')}}"><!--zoomImage-->
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
		<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
	</symbol>
</svg>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if (isset($data_guru))
				Edit
				@else
				Tambah
				@endif
				Guru
			</div>
			<div class="card-body">
				@if (!isset($data_guru))
				<div class="alert alert-primary d-flex align-items-center" role="alert">
					<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
					<div>
						Password user baru sama seperti email
					</div>
				</div>
				@endif
				<form id="formDataGuru">
					<input type="hidden" name="id" @isset($data_guru) value="{{$data_guru->id_guru}}" @endisset>
					<div class="row">
						<div class="col-12 col-md-8 mb-3">
							<label for="email" class="form-label">Email *</label>
							<input type="text" class="form-control" name="email" id="email" placeholder="Email" @isset($data_guru->users) value="{{$data_guru->users->email}}" disabled @endisset>
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="no_induk" class="form-label">No Induk *</label>
							<input type="text" class="form-control" name="no_induk" id="no_induk" placeholder="No Induk" @isset($data_guru->users) value="{{$data_guru->users->no_induk}}" disabled @endisset>
						</div>
						<div class="col-12 col-md-8 mb-3">
							<label for="nama" class="form-label">Nama Guru</label>
							<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Guru" @isset($data_guru) value="{{$data_guru->nama}}" @endisset>
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="nip" class="form-label">NIP</label>
							<input type="text" class="form-control" name="nip" id="nip" placeholder="NIP" @isset($data_guru) value="{{$data_guru->nip}}" @endisset>
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="tmp_lahir" class="form-label">Tempat Lahir</label>
							<input type="text" class="form-control" name="tmp_lahir" id="tmp_lahir" placeholder="Tempat Lahir" @isset($data_guru) value="{{$data_guru->tmp_lahir}}" @endisset>
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
							<input type="date" class="form-control" name="tgl_lahir" id="tgl_lahir" @isset($data_guru) value="{{$data_guru->tgl_lahir}}" @endisset>
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="gender" class="form-label">Jenis Kelamin</label>
							<select class="form-select select2" name="gender" id="gender">
								<option value="">-PILIH-</option>
								<option value="Laki-Laki" @isset($data_guru) @if ($data_guru->gender == 'Laki-Laki') selected @endif @endisset>Laki-Laki</option>
								<option value="Perempuan" @isset($data_guru) @if ($data_guru->gender == 'Perempuan') selected @endif @endisset>Perempuan</option>
							</select>
						</div>
						<div class="col-12 mb-3">
							<label for="no_tlp" class="form-label">No Telepon</label>
							<input type="text" class="form-control" name="no_tlp" id="no_tlp" placeholder="No Telp" @isset($data_guru) value="{{$data_guru->no_tlp}}" @endisset>
						</div>
						<div class="col-12 mb-3">
							<label for="alamat" class="form-label">Alamat</label>
							<textarea class="form-control" name="alamat" id="alamat" rows="5">@isset($data_guru) {{$data_guru->alamat}} @endisset</textarea>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label for="foto" class="form-label">Foto Guru</label>
								<center class="mb-3">
									<a class="pan" id="btnOutPut" data-big="@isset($data_guru->foto){!! url('uploads/guru/'.$data_guru->foto) !!}@endisset">
										<img id="outPut" @isset($data_guru->foto) src="{!! url('uploads/guru/'.$data_guru->foto) !!}" @endisset class="rounded mx-auto d-block w-25 responsive @isset($data_guru) img-thumbnail @endisset">
									</a>
								</center>
								<input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="loadFile(event)">
							</div>
						</div>
						<div class="col-12 mb-3">
							<label for="no_tlp" class="form-label">Tugas Tambahan</label>
							<select class="form-select select2" name="tugas_tambahan[]" id="tugas_tambahan" multiple>
								<option value="">-PILIH-</option>
								<option value="piket" @isset($data_guru) @if ($data_guru->is_piket) selected @endif @endisset>Guru Piket</option>
							</select>
						</div>
					</div>
					<hr>
					<div class="d-flex gap-2">
						<button class="btn btn-secondary px-4 btnKembali">KEMBALI</button>
						<button class="btn btn-primary px-4 btnSimpan">SIMPAN</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{asset('zoom/js/jquery.pan.js')}}"></script><!--zoomImage-->
<script>
	$(document).ready(function () {
		$('.select2').select2({
			theme: 'bootstrap-5',
		});
	})

	$('.btnKembali').click((e)=>{
		e.preventDefault()
		$('.other-page').empty()
		$('.main-page').fadeIn()
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
		var data = new FormData($('#formDataGuru')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("admin.dataGuru.save")}}',
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
							$('.other-page').fadeOut(()=>{
								$('#datatabel').DataTable().ajax.reload()
								location.reload()
							})
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

</script>