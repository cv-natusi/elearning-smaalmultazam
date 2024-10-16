<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if (isset($dokumen))
				Edit
				@else
				Tambah
				@endif
				Materi
			</div>
			<div class="card-body">
				<form id="formMateri">
					<input type="hidden" name="id" @isset($dokumen) value="{{$dokumen->id_dokumen}}" @endisset>
					<div class="mb-3">
						<label for="judul" class="form-label">Judul Dokumen *</label>
						<input type="text" class="form-control" name="judul" id="judul" placeholder="Judul Materi" @isset($dokumen) value="{{$dokumen->judul}}" @endisset>
					</div>
					<div class="mb-3">
						<label for="file" class="form-label">Upload File Dokumen (Pdf/Doc/PPT) *</label>
						<br>
						<a target="_blank" href="{{url('uploads/dokumen')}}/@isset($dokumen){{$dokumen->file}}@endisset">@isset($dokumen) {{$dokumen->file}} @endisset</a>
						<input type="file" class="form-control" id="file" name="file" accept=".pdf, .doc, .docx, .ppt">
					</div>
					<div class="row mb-3">
						<div class="col-md-12">
							<label for="keterangan">Keterangan</label>
							<textarea class="form-control" id="keterangan" name="keterangan" rows="10">@isset($dokumen) {{$dokumen->keterangan}} @endisset</textarea>
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
		var data = new FormData($('#formMateri')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("admin.dokumen.save")}}',
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