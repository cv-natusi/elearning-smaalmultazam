<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				@if (isset($rapor))
				Edit
				@else
				Tambah
				@endif
				Rapor
			</div>
			<div class="card-body">
				<form id="formDataGuru">
					<input type="hidden" name="id" @isset($rapor) value="{{$rapor->id_rapor}}" @endisset>
					<div class="row">
						<div class="col-md-6 mb-3">
							<label for="judul" class="form-label">Judul *</label>
							<input type="text" class="form-control" name="judul" id="judul" placeholder="Judul" @isset($rapor) value="{{$rapor->judul}}" @endisset>
						</div>
						<div class="col-md-6 mb-3">
							<label for="guru_id" class="form-label">Wali Kelas *</label>
							<select class="form-select select2" name="guru_id" id="guru_id">
								<option value="">-PILIH-</option>
								@foreach ($guru as $item)
									<option value="{{$item->id_guru}}" @isset($rapor) @if($rapor->guru_id==$item->id_guru) selected @endif @endisset>{{$item->nama}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 mb-3">
							<label for="kelas_id" class="form-label">Kelas *</label>
							<select class="form-select select2" name="kelas_id" id="kelas_id">
								<option value="">-PILIH-</option>
								@foreach ($kelas as $item)
									<option value="{{$item->id_kelas}}" @isset($rapor) @if($rapor->kelas_id==$item->id_kelas) selected @endif @endisset>{{$item->nama_kelas}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6 mb-3">
							<label for="tahun_ajaran_id" class="form-label">Tahun Ajaran *</label>
							<select class="form-select select2" name="tahun_ajaran_id" id="tahun_ajaran_id">
								<option value="">-PILIH-</option>
								@foreach ($tahun_ajaran as $item)
									<option value="{{$item->id_tahun_ajaran}}" @isset($rapor) @if($rapor->tahun_ajaran_id==$item->id_tahun_ajaran) selected @endif @endisset>{{$item->nama_tahun_ajaran}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-2 mb-3">
							<label for="semester" class="form-label">Semester *</label>
							<select class="form-select select2" name="semester" id="semester">
								<option value="">-PILIH-</option>
								<option value="1" @isset($rapor) @if($rapor->semester==1) selected @endif @endisset>1</option>
								<option value="2" @isset($rapor) @if($rapor->semester==2) selected @endif @endisset>2</option>
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-12 mb-3">
							<label for="rapor_file" class="form-label">File Rapor (PDF)*</label>
							<input class="form-control" type="file" name="rapor_file" id="rapor_file" accept="application/pdf">
							@isset($rapor)
								@if($rapor->file) {{-- Ganti 'file_path' dengan nama kolom di database Anda --}}
									<div class="mt-2">
										<a href="{{ asset('storage/rapor_files/' . $rapor->file) }}" target="_blank">Lihat File Saat Ini</a>
									</div>
								@endif
							@endisset
						</div>
					</div>
					<div class="row">
						<div class="col-12 mb-3">
							<label for="link" class="form-label">Link Google Spreadsheet</label>
							<textarea class="form-control" name="link" id="link" cols="30" rows="6">@isset($rapor) {{$rapor->link}} @endisset</textarea>
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
				url: '{{route("admin.rapor.save")}}',
				type: 'POST',
				data: data,
				async: true,
				cache: false,
				contentType: false,
				processData: false,
				success: function(data, textStatus, jqXHR){
					if(jqXHR.status==200){
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: data.message,
							showConfirmButton: false,
							timer: 1200
						})
						setTimeout(()=>{
							e.preventDefault()
							$('.other-page').empty()
							$('.main-page').fadeIn()
							table.ajax.reload(null, false);
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
			}).fail((data)=>{
				const errors = data.responseJSON.errors;
				const message = Object.values(errors).flat().join('<br>');

				Swal.fire({
					icon: 'error',
					title: 'Whoops..',
					html: message,
					showConfirmButton: false,
					timer: 1300,
				})
				$('.btnSimpan').attr('disabled',false).html('SIMPAN')
			})
	})

</script>