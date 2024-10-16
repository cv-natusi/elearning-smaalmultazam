<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if (isset($materi))
				Edit
				@else
				Tambah
				@endif
				Materi
			</div>
			<div class="card-body">
				<form id="formMateri">
					<input type="hidden" name="id" @isset($materi) value="{{$materi->id_materi}}" @endisset>
					<div class="mb-3">
						<label for="judul" class="form-label">Judul Materi *</label>
						<input type="text" class="form-control" name="judul" id="judul" placeholder="Judul Materi" @isset($materi) value="{{$materi->judul}}" @endisset>
					</div>
					<div class="row">
						<div class="col-3">
							<div class="mb-3">
								<label for="kelas_id" class="form-label">Kelas *</label>
								<select name="kelas_id" id="kelas_id" class="form-control selectpicker select2">
									<option value="" disabled>-PILIH-</option>
									@foreach ($kelas as $item)
									<option value="{{$item->id_kelas}}">{{$item->nama_kelas}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="mb-3">
								<label for="tahun_ajaran_id" class="form-label">Tahun Ajaran *</label>
								<select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control selectpicker select2">
									<option value="" disabled>-PILIH-</option>
									@foreach ($tahunAjaran as $item)
									<option value="{{$item->id_tahun_ajaran}}">{{$item->nama_tahun_ajaran}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="mb-3">
								<label for="semester" class="form-label">Semester *</label>
								<select name="semester" id="semester" class="form-control selectpicker select2">
									<option value="" disabled>-PILIH-</option>
									<option value="1">1</option>
									<option value="2">2</option>
								</select>
							</div>
						</div>
						<div class="col-3">
							<div class="mb-3">
								<label for="mapel_id" class="form-label">Mata Pelajaran *</label>
								<select name="mapel_id" id="mapel_id" class="form-control selectpicker select2">
									<option value="" disabled>-PILIH-</option>
									@foreach ($mataPelajaran as $item)
									<option value="{{$item->id_mapel}}">{{$item->nama_mapel}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
					<div class="mb-3">
						<label for="file_materi" class="form-label">Upload File Materi (Pdf/Doc/PPT) *</label>
						<br>
						<a target="_blank" href="{{url('uploads/materi')}}/@isset($materi){{$materi->file_materi}}@endisset">@isset($materi) {{$materi->file_materi}} @endisset</a>
						<input type="file" class="form-control" id="file_materi" name="file_materi" accept=".pdf, .doc, .docx, .ppt">
					</div>
					<div class="row mb-3">
						<div class="col-md-12">
							<label for="deskripsi_materi">Keterangan</label>
							<textarea class="form-control" id="deskripsi_materi" name="deskripsi_materi" rows="10">@isset($materi) {{$materi->deskripsi_materi}} @endisset</textarea>
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
				url: '{{route("guru.materi.save")}}',
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