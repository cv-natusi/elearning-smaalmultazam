<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				Kelas Siswa
			</div>
			<div class="card-body">
				<form id="formDataKelas">
					<div class="row">
						<div class="col-12 col-md-6 mb-3">
							<label for="id_tahun_ajaran" class="form-label">Tahun Ajaran *</label>
							<select name="id_tahun_ajaran" aria-controls="id_tahun_ajaran" class="form-select select2" id="id_tahun_ajaran" required>
								<option value="">-PILIH-</option>
								@foreach ($tahun_ajaran as $item)
									<option value="{{$item->id_tahun_ajaran}}">{{$item->nama_tahun_ajaran}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-12 col-md-6 mb-3">
							<label for="id_kelas" class="form-label">Kelas *</label>
							<select name="id_kelas" aria-controls="id_kelas" class="form-select select2" id="id_kelas" required>
								<option value="">-PILIH-</option>
								@foreach ($kelas as $item)
									<option value="{{$item->id_kelas}}">{{$item->nama_kelas}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-12 mb-3">
							<label for="siswa_id" class="form-label">Siswa *</label>
							<select name="siswa_id[]" aria-controls="siswa_id" class="form-select selectSiswa" id="siswa_id" required multiple>
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
		$('.selectSiswa').select2({
			theme: 'bootstrap-5',
			width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' : 'style',
			allowClear: Boolean($(this).data('allow-clear')),
			minimumInputLength: 3,
			placeholder: 'Cari Siswa',
			ajax: {
				url: '{{route("admin.kelasSiswa.cariSiswa")}}',
				dataType: 'json',
				data: function (params) {
					var query = {
						term: params.term,
						q: params.q,
					}
					return query;
				},
				delay: 250,
				processResults: function (data) {
					// Transforms the top-level key of the response object from 'items' to 'results'
					var results = [];
					$.each(data.response, function(k, v) {
						results.push({
							id: v.id_siswa,
							text: v.nama,
							nama_siswa: v.nama,
							nisn: v.nisn,
							no_induk: v.user?v.user.no_induk:'-',
						});
					});

					return {
						results: results,
					};
				},
				cache: true,
			},
			// data:[nm_obat['TV_NM_OBAT']],
			templateResult: formatRepo,
			// templateSelection: formatRepoSelection,
		})
	})

	function formatRepo (repo) {
		if (repo.loading) {
			return 'Loading...';
		}

		var $container = $(
			"<div class='select2-result-repository clearfix'>" +
				repo.text + ' (NISN: <span>' + repo.nisn + '</span>, No.Induk: <span>' + repo.no_induk + '</span>)' + 
			"</div>"
		);

		return $container;
	}

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
				url: '{{route("admin.kelasSiswa.save")}}',
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