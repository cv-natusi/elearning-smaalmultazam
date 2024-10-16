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
					<input type="hidden" name="id" @isset($rapor) value="{{$rapor->id_spreadsheet_share}}" @endisset>
					<div class="row">
						<div class="col-12 col-md-6 mb-3">
							<label for="judul" class="form-label">Judul *</label>
							<input type="text" class="form-control" name="judul" id="judul" placeholder="Judul" @isset($rapor) value="{{$rapor->judul}}" @endisset>
						</div>
						<div class="col-12 col-md-6 mb-3">
							<label for="guru_id" class="form-label">Judul *</label>
							<select class="form-select select2" name="guru_id" id="guru_id">
								<option value="">-PILIH-</option>
								@foreach ($guru as $item)
									<option value="{{$item->id_guru}}" @isset($rapor) @if($rapor->guru_id==$item->id_guru) selected @endif @endisset>{{$item->nama}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-12 mb-3">
							<label for="link" class="form-label">Link Google Spreadsheet*</label>
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