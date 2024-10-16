<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if (isset($kelas_mapel))
				Edit
				@else
				Tambah
				@endif
				Mapel Pengampu
			</div>
			<div class="card-body">
				<form id="formDataSiswa">
					<div class="row">
						<input type="hidden" name="id" @isset($kelas_mapel) value="{{$kelas_mapel->id_kelas_mapel}}" @endisset>
						<div class="mb-3 col-12 col-md-6">
							<label class="form-label">Guru *</label>
							<select name="guru_id" class="form-select select2" id="guru_id">
								<option value="">-PILIH-</option>
								@foreach ($guru as $item)
									<option value="{{$item->id_guru}}" @isset($kelas_mapel) @if($kelas_mapel->guru_id==$item->id_guru) selected @endif @endisset>{{$item->nama}}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3 col-12 col-md-6">
							<label class="form-label">Mata Pelajaran *</label>
							<select name="mapel_id" class="form-select select2" id="mapel_id">
								<option value="">-PILIH-</option>
								@foreach ($mapel as $item)
									<option value="{{$item->id_mapel}}" @isset($kelas_mapel) @if($kelas_mapel->mapel_id==$item->id_mapel) selected @endif @endisset>{{$item->nama_mapel}}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3 col-12 col-md-6">
							<label class="form-label">Kelas *</label>
							<select name="kelas_id" class="form-select select2" id="kelas_id">
								<option value="">-PILIH-</option>
								@foreach ($kelas as $item)
									<option value="{{$item->id_kelas}}" @isset($kelas_mapel) @if($kelas_mapel->kelas_id==$item->id_kelas) selected @endif @endisset>{{$item->nama_kelas}}</option>
								@endforeach
							</select>
						</div>
						<div class="mb-3 col-12 col-md-6">
							<label class="form-label">Tahun Ajaran *</label>
							<select name="tahun_ajaran_id" class="form-select select2" id="tahun_ajaran_id">
								<option value="">-PILIH-</option>
								@foreach ($tahun_ajaran as $item)
									<option value="{{$item->id_tahun_ajaran}}" @isset($kelas_mapel) @if($kelas_mapel->tahun_ajaran_id==$item->id_tahun_ajaran) selected @endif @endisset>{{$item->nama_tahun_ajaran}}</option>
								@endforeach
							</select>
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
				url: '{{route("admin.mapelPengampu.save")}}',
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