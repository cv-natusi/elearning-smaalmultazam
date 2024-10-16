<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if (isset($mapel))
				Edit
				@else
				Tambah
				@endif
				Tahun Ajaran
			</div>
			<div class="card-body">
				<form id="formDataGuru">
					<input type="hidden" name="id" @isset($mapel) value="{{$mapel->id_mapel}}" @endisset>
					<div class="row">
						<div class="col-12">
							<div class="mb-3">
								<label for="nama_mapel" class="form-label">Nama Mata Pelajaran *</label>
								<input type="text" class="form-control" name="nama_mapel" id="nama_mapel" placeholder="Judul Materi" @isset($mapel) value="{{$mapel->nama_mapel}}" @endisset>
							</div>
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
		var data = new FormData($('#formDataGuru')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("admin.mataPelajaran.save")}}',
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