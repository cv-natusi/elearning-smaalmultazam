<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-primary text-white">
				@if (isset($soal))
				Edit
				@else
				Tambah
				@endif
				Soal Elearning
			</div>
			<div class="card-body">
				<form id="formSoal" enctype="multipart/form-data">
					@csrf
					<input type="hidden" name="id" id="id" @isset($soal) value="{{$soal->id_soal}}" @endisset>
					<input type="hidden" name="jenis" @if(isset($soal)) value="{{$soal->jenis}}" @else value="1" @endif>
					<div class="row mb-3">
						<div class="col col-md-9">
							<label for="judul_soal" class="form-label">Judul Soal *</label>
							<input type="text" class="form-control" name="judul_soal" id="judul_soal" placeholder="Judul Materi" @isset($soal) value="{{$soal->judul_soal}}" @endisset>
						</div>
						<div class="col col-md-3">
							<label for="jenis_file" class="form-label">Jenis</label>
							<select name="jenis_file" id="jenis_file" class="form-control selectpicker select2">
								<option value="" disabled>-PILIH-</option>
								@foreach ($jenis_file as $item)
								<option value="{{$item->id}}" @isset($soal) @if ($soal->jenis_file==$item->id) selected @endif @endisset>{{$item->nama}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="row">
						<div class="col-2">
							<div class="mb-3">
								<label for="kelas_id" class="form-label">Kelas *</label>
								<select name="kelas_id" id="kelas_id" class="form-control selectpicker select2">
									<option value="" disabled>-PILIH-</option>
									@foreach ($kelas as $item)
									<option value="{{$item->id_kelas}}" @isset($soal) @if ($soal->kelas_id==$item->id_kelas) selected @endif @endisset>{{$item->nama_kelas}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-2">
							<div class="mb-3">
								<label for="tahun_ajaran_id" class="form-label">Tahun Ajaran *</label>
								<select name="tahun_ajaran_id" id="tahun_ajaran_id" class="form-control selectpicker select2">
									<option value="" disabled>-PILIH-</option>
									@foreach ($tahunAjaran as $item)
									<option value="{{$item->id_tahun_ajaran}}" @isset($soal) @if ($soal->tahun_ajaran_id==$item->id_tahun_ajaran) selected @endif @endisset>{{$item->nama_tahun_ajaran}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-2">
							<div class="mb-3">
								<label for="semester" class="form-label">Semester *</label>
								<select name="semester" id="semester" class="form-control selectpicker select2">
									<option value="" disabled>-PILIH-</option>
									<option value="1" @isset($soal) @if ($soal->semester==1) selected @endif @endisset>1</option>
									<option value="2" @isset($soal) @if ($soal->semester==2) selected @endif @endisset>2</option>
								</select>
							</div>
						</div>
						<div class="col-4">
							<div class="mb-3">
								<label for="mapel_id" class="form-label">Mata Pelajaran *</label>
								<select name="mapel_id" id="mapel_id" class="form-control selectpicker select2">
									<option value="" disabled>-PILIH-</option>
									@foreach ($mataPelajaran as $item)
									<option value="{{$item->id_mapel}}" @isset($soal) @if ($soal->mapel_id==$item->id_mapel) selected @endif @endisset>{{$item->nama_mapel}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-2">
							<div class="mb-3">
								<label for="kkm" class="form-label">KKM *</label>
								<input class="form-control" type="number" name="kkm" id="kkm" placeholder="0"  @isset($soal) value="{{$soal->kkm}}" @endisset>
							</div>
						</div>
						<div class="row soal-container">
							<div class="col-3">
								<div class="mb-3">
									<label for="mulai_pengerjaan" class="form-label">Tanggal Mulai *</label>
									<input class="form-control date-time date-start mb-2" type="text" name="mulai_pengerjaan" id="mulai_pengerjaan" @isset($soal) value="{{$soal->mulai_pengerjaan}}" @endisset>
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3">
									<label for="selesai_pengerjaan" class="form-label">Tanggal Selesai *</label>
									<input class="form-control date-time date-end mb-2" type="text" name="selesai_pengerjaan" id="selesai_pengerjaan" @isset($soal) value="{{$soal->selesai_pengerjaan}}" @endisset>
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3">
									<label for="jumlah_soal" class="form-label">Jumlah Soal *</label>
									<input class="form-control" type="number" name="jumlah_soal" id="jumlah_soal" @isset($soal) value="{{$soal->jumlah_soal}}" @endisset>
								</div>
							</div>
							<div class="col-3">
								<div class="mb-3">
									<label for="durasi" class="form-label">Durasi Pengerjaan (Menit) *</label>
									<input class="form-control" type="number" name="durasi" id="durasi" @isset($soal) value="{{$soal->durasi}}" @endisset>
								</div>
							</div>
						</div>
						{{-- <div class="mb-3">
							<label for="pendahuluan" class="form-label">Pendahuluan *</label>
							<textarea name="pendahuluan" id="pendahuluan" cols="30" rows="10">@isset($soal) {{$soal->pendahuluan}} @endisset</textarea>
						</div> --}}
						<div class="mb-3">
							<label class="form-label" for="soal_file">Upload Soal (Hanya .docx, .pdf)</label>
							<input class="form-control" type="file" name="soal_file" id="soal_file" accept=".docx,.pdf">

							@isset($soal)
								@if($soal->file_soal)
									<div class="mt-2">
										<a href="{{ asset('storage/' . $soal->file_soal) }}" target="_blank" class="btn btn-sm btn-secondary">
											Lihat File Saat Ini
										</a>
										<small class="d-block text-muted mt-1">
											*Abaikan input file di atas jika tidak ingin mengubah file.
										</small>
									</div>
								@endif
							@endisset
						</div>
					</div>
					<hr>
					<div class="d-flex gap-2">
						<button class="btn btn-secondary px-4 btnKembali">KEMBALI</button>
						@if(isset($soal))
						{{-- <button class="btn btn-warning px-4 btnLanjutkan text-white me-0 ms-auto"><i class='bx bx-book-content'></i> PEMBUATAN SOAL</button> --}}
						<button class="btn btn-primary px-4 btnUpdate">SIMPAN</button>
						@else
						<button class="btn btn-primary px-4 btnSimpan">@if (isset($soal)) SIMPAN @else BUAT @endif</button>
						@endif
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<script>
	$(document).ready(function () {
		window.updateSoalUrlTemplate = "{{ route('guru.soalTulis.update', ['id_soal' => ':id']) }}";

        function toggleSoalFields() {
            var jenisTerpilih = $('#jenis_file').val();

            if (jenisTerpilih === '1') {
                $('.soal-container').show();
            } else {
                $('.soal-container').hide();
            }
        }

        toggleSoalFields();

        $('#jenis_file').on('change', function() {
            toggleSoalFields();

            if ($(this).val() !== 'soal') {
                $('.soal-container :input').val('');
            }
        });

		var startDate = flatpickr($(".date-start"),{
			enableTime: true,
			minDate: "today",
			dateFormat: "Y-m-d H:i:S",
			onChange: function(selectedDates, dateStr, instance) {
				endDate.set({
					minDate: dateStr,
				})
			},
		})
		var endDate = flatpickr($(".date-end"),{
			enableTime: true,
			minDate: "today",
			dateFormat: "Y-m-d H:i:S",
		})
		$('.select2').select2({
			theme: 'bootstrap-5',
		});

		$(document).on('click', '.btnUpdate', function(e) {
			e.preventDefault();
			
			let form = $('#formSoal')[0];
			
			let formData = new FormData(form);
			
			let soal_id = $('#id').val();
			
			if (!window.updateSoalUrlTemplate) {
				console.error('Error: URL template untuk update soal tidak ditemukan.');
				Swal.fire('Error Konfigurasi', 'URL endpoint tidak terdefinisi.', 'error');
				return; 
			}
			let url = window.updateSoalUrlTemplate.replace(':id', soal_id);
			
			const csrfToken = $('meta[name="csrf-token"]').attr('content');
			
			Swal.fire({
				title: 'Menyimpan perubahan...',
				text: 'Mohon tunggu sebentar.',
				allowOutsideClick: false,
				didOpen: () => {
					Swal.showLoading();
				}
			});
			
			$.ajax({
				url: url,
				type: 'POST',
				data: formData,
				processData: false,
				contentType: false,
				headers: {
					'X-CSRF-TOKEN': csrfToken
				},
				success: function(response) {
					Swal.close();

					if (response.code == 200) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.message,
							showConfirmButton: false,
							timer: 1200
						})
						setTimeout(()=>{
							$('.other-page').fadeOut(()=>{
								$('#datatabel').DataTable().ajax.reload()
								location.reload()
							})
						}, 1100);
						$('#soalModal').modal('hide');
						$('#namaTableAnda').DataTable().ajax.reload();
					} else {
						Swal.fire(
							'Gagal!',
							response.message || 'Terjadi kesalahan.',
							'error'
						);
					}
				},
				error: function(xhr) {
					Swal.close();
					
					if (xhr.status === 422) {
						let errors = xhr.responseJSON.errors;
						let errorMessages = '';
						$.each(errors, function(key, value) {
							errorMessages += `<li>${value[0]}</li>`;
						});
						
						Swal.fire(
							'Validasi Gagal!',
							`<ul class="text-start">${errorMessages}</ul>`,
							'error'
						);
					} else {
						Swal.fire(
							'Error Server!',
							'Gagal menyimpan data. Silakan coba lagi.',
							'error'
						);
					}
					console.error(xhr.responseText);
				}
			});
		});
	})

	// var pendahuluan = CKEDITOR.replace('pendahuluan', {
	// 	// uiColor: '#CCEAEE'
	// 	toolbarCanCollapse:false,
	// });

	$('.btnKembali').click((e)=>{
		e.preventDefault()
		$('.other-page').empty()
		$('.main-page').fadeIn()
	})

	$('.btnSimpan').click((e) => {
		e.preventDefault()
		var data = new FormData($('#formSoal')[0])
		// var pendahuluan = CKEDITOR.instances.pendahuluan.getData();
		// data.append('pendahuluan',pendahuluan);
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("guru.soalTulis.createSoal")}}',
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

	$('.btnLanjutkan').click((e) => {
		e.preventDefault()
		$('.btnLanjutkan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("guru.soalTulis.pertanyaanForm")}}',
				type: 'POST',
				data: {id_soal:$('#id').val()},
				async: true,
				cache: false,
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
							$('.other-page').html(data.content)
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
					$('.btnLanjutkan').attr('disabled',false).html("<i class='bx bx-book-content'></i> PEMBUATAN SOAL")
				}
			}).fail(()=>{
				Swal.fire({
					icon: 'error',
					title: 'Whoops..',
					text: 'Terjadi kesalahan silahkan ulangi kembali',
					showConfirmButton: false,
					timer: 1300,
				})
				$('.btnLanjutkan').attr('disabled',false).html("<i class='bx bx-book-content'></i> PEMBUATAN SOAL")
			})
	})

</script>