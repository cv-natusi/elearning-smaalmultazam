<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if (isset($siswa))
				Edit
				@else
				Tambah
				@endif
				Siswa
			</div>
			<div class="card-body">
				<form id="formDataSiswa">
					<div class="row">
						<input type="hidden" name="id" @isset($siswa) value="{{$siswa->id_siswa}}" @endisset>
						<div class="mb-3 col-12">
							<label for="nama" class="form-label">Nama Siswa *</label>
							<input type="text" class="form-control" name="nama" id="nama" placeholder="Nama Siswa" @isset($siswa) value="{{$siswa->nama}}" @endisset>
						</div>
						<div class="mb-3 col-12 col-md-4">
							<label for="no_induk" class="form-label">No Induk *</label>
							<input type="text" class="form-control" name="no_induk" id="no_induk" placeholder="No Induk" @isset($siswa) value="{{$siswa->no_induk}}" @endisset>
						</div>
						<div class="mb-3 col-12 col-md-4">
							<label for="nisn" class="form-label">NISN *</label>
							<input type="text" class="form-control" name="nisn" id="nisn" placeholder="NISN" @isset($siswa) value="{{$siswa->nisn}}" @endisset>
						</div>
						<div class="mb-3 col-12 col-md-4">
							<label for="tahun_masuk" class="form-label">Tahun Masuk *</label>
							<input type="text" class="form-control" name="tahun_masuk" id="tahun_masuk" placeholder="Tahun Masuk" @isset($siswa) value="{{$siswa->thn_masuk}}" @endisset>
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
						<div class="mb-3 col-12 col-md-6">
							<label class="form-label">Status *</label>
							<select name="status" class="form-select select2" id="status">
								<option value="">-PILIH-</option>
								<option value="1" @isset($siswa) @if($siswa->status=='Siswa Aktif') selected @endif @endisset>Siswa Aktif</option>
								<option value="0" @isset($siswa) @if($siswa->status=='Bukan Siswa Aktif') selected @endif @endisset>Bukan Siswa Aktif</option>
							</select>
						</div>
						<div class="mb-3 col-12">
							<label for="alamat" class="form-label">Alamat *</label>
							<textarea class="form-control" name="alamat" id="alamat" cols="30" rows="10">@isset($siswa) {{$siswa->alamat}} @endisset</textarea>
						</div>
						<hr>
						<div class="d-flex gap-2">
							<button class="btn btn-secondary px-4 btnKembali">KEMBALI</button>
							<button class="btn btn-primary px-4 btnSimpan">SIMPAN</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
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

	$('.btnSimpan').click((e) => {
		e.preventDefault()
		var data = new FormData($('#formDataSiswa')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("admin.dataSiswa.save")}}',
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