<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if (isset($kelas))
				Edit
				@else
				Tambah
				@endif
				Guru
			</div>
			<div class="card-body">
				<form id="formDataKelas">
					<input type="hidden" name="id" @isset($kelas) value="{{$kelas->id_kelas}}" @endisset>
					<div class="row">
						<div class="col-12 col-md-8 mb-3">
							<label for="nama_kelas" class="form-label">Nama Kelas *</label>
							<input type="text" class="form-control" name="nama_kelas" id="nama_kelas" placeholder="Judul Materi" @isset($kelas) value="{{$kelas->nama_kelas}}" @endisset>
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="kelas_tingkat" class="form-label">Kelas *</label>
							<select name="kelas_tingkat" aria-controls="kelas_tingkat" class="form-select select2" id="kelas_tingkat" required>
								<option value="">-PILIH-</option>
								<option value="1" @isset($kelas) @if ($kelas->kelas_tingkat==1) selected @endif @endisset>X</option>
								<option value="2" @isset($kelas) @if ($kelas->kelas_tingkat==2) selected @endif @endisset>XI</option>
								<option value="3" @isset($kelas) @if ($kelas->kelas_tingkat==3) selected @endif @endisset>XII</option>
							</select>
						</div>
						<div class="col-12 mb-3">
							<label for="guru_id" class="form-label">Wali Kelas *</label>
							<select name="guru_id" aria-controls="guru_id" class="form-select select2" id="guru_id" required>
								<option value="">-PILIH-</option>
								@foreach ($guru as $item)
									<option value="{{$item->id_guru}}" @isset($kelas) @if($kelas->guru_id==$item->id_guru) selected @endif @endisset>{{$item->nama}}</option>
								@endforeach
							</select>
						</div>
						{{-- <div class="col-12 col-md-6 mb-3">
							<label for="tahun_ajaran_id" class="form-label">Tahun Ajaran *</label>
							<select name="tahun_ajaran_id" aria-controls="tahun_ajaran_id" class="form-select select2" id="tahun_ajaran_id" required>
								<option value="">-PILIH-</option>
								@foreach ($tahun_ajaran as $item)
									<option value="{{$item->id_tahun_ajaran}}" @isset($kelas) @if($kelas->tahun_ajaran_id==$item->id_tahun_ajaran) selected @endif @endisset>{{$item->nama_tahun_ajaran}}</option>
								@endforeach
							</select>
						</div> --}}
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
		var data = new FormData($('#formDataKelas')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("admin.dataKelas.save")}}',
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data){
					if(data.code==200){
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