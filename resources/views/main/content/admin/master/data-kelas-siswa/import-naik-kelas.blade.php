<div class="row">
	<form id="formDataKelas">
		<div class="col-12">
			<div class="card">
				<div class="card-header bg-main-website text-white">
					Naik Kelas Siswa
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-12 col-md-4 mb-3">
							<label for="nama" class="form-label">File Excel (.xls, xlsx) *</label>
							<input type="file" class="form-control" name="file" id="file" accept=".xls, .xlsx">
							<input type="hidden" name="id_siswa" id="id_siswa" onchange="filter()">
						</div>
						<div class="col-12 col-md-4 mb-3">
							<label for="nama" class="form-label">Kolom nomor induk *</label>
							<input type="number" min="1" class="form-control" name="kolom" id="kolom" value='1'>
						</div>
						<div class="col-12 col-md-4 mb-3">
							<button class="btn btn-primary px-4 mt-4 btnUpload">UPLOADS</button>
						</div>
						<div class="col-12 col-md-6 mb-3">
							<label for="id_tahun_ajaran_new" class="form-label">Tahun Ajaran *</label>
							<select name="id_tahun_ajaran_new" aria-controls="id_tahun_ajaran_new" class="form-select select2" id="id_tahun_ajaran_new" required>
								<option value="">-PILIH-</option>
								@foreach ($tahun_ajaran as $item)
								<option value="{{$item->id_tahun_ajaran}}">{{$item->nama_tahun_ajaran}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-12 col-md-6 mb-3">
							<label for="id_kelas_new" class="form-label">Kelas *</label>
							<select name="id_kelas_new" aria-controls="id_kelas_new" class="form-select select2" id="id_kelas_new" required>
								<option value="">-PILIH-</option>
								@foreach ($kelas as $item)
								<option value="{{$item->id_kelas}}">{{$item->nama_kelas}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<hr>
					<div class="d-flex gap-2">
						<button class="btn btn-secondary px-4 btnKembali">KEMBALI</button>
						<button class="btn btn-primary px-4 btnSimpan">SIMPAN</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-12 col-md-8">
					<div class="card">
						<div class="card-body">
							<div class="p-1">
								<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTableSiswa">
									<thead>
										<tr>
											{{-- <th></th> --}}
											<th>No</th>
											<th>No Induk</th>
											<th>Nama Siswa</th>
										</tr>
									</thead>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-md-4">
					<div class="card">
						<div class="card-body">
							<div class="p-1">
								<table class="" style="width:100%" id="dataTableTidak">
									<thead>
										<tr>
											{{-- <th></th> --}}
											<th>No</th>
											<th>No Induk</th>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
{{-- <script src="https://cdn.datatables.net/fixedcolumns/5.0.0/js/dataTables.fixedColumns.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/5.0.0/js/fixedColumns.dataTables.js"></script>
<script src="https://cdn.datatables.net/select/2.0.0/js/dataTables.select.js"></script>
<script src="https://cdn.datatables.net/select/2.0.0/js/select.dataTables.js"></script> --}}
<script>
	var routeDatatable = "{{route('admin.kelasSiswa.listSiswa')}}";
	$(document).ready(async function() {
		$('.select2').select2({
			theme: 'bootstrap-5',
		});

		// await dataTable($('#id_siswa').val())
	})

	async function filter() {
		await dataTable($('#id_siswa').val())
	}
	

	$('.btnUpload').click((e)=>{
		e.preventDefault()
		if ($('#file').get(0).files.length == 0) {
			Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Harap pilih file excel',
			})
			return
		}
		if (!$('#kolom').val()) {
			Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Harap pilih kolom excel yang mengandung nomor induk',
			})
			return
		}
		var data = new FormData($('#formDataKelas')[0])
		$('.btnUpload').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
			url: '{{route("admin.kelasSiswa.readExcel")}}',
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
					$('#id_siswa').val(data.content.id_siswa).trigger('change')
					renderTidakAda(data.content.no_induk,data.content.no_induk_ada)
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
				$('.btnUpload').attr('disabled',false).html('UPLOADS')
			}
		}).fail(()=>{
			Swal.fire({
				icon: 'error',
				title: 'Whoops..',
				text: 'Terjadi kesalahan silahkan ulangi kembali',
				showConfirmButton: false,
				timer: 1300,
			})
			$('.btnUpload').attr('disabled',false).html('UPLOADS')
		})
	})

	// $('.btnCari').click(async(e)=>{
	// 	e.preventDefault()
	// 	// console.log($('#id_tahun_ajaran_old').val());
	// 	await dataTable($('#id_tahun_ajaran_old').val(),$('#id_kelas_old').val())
	// })

	async function dataTable(id_siswa) {
		const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
		let sDom = `
		<'row mb-2'>
		<'row mt-2'<'col-sm-12'tr>>
		<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>
		`

		await $('#dataTableSiswa').DataTable({
			// layout: {
			// 	topStart: {
			// 		buttons: ['selectAll', 'selectNone']
			// 	}
			// },
			// language: {
			// 	buttons: {
			// 		selectAll: 'Select all items',
			// 		selectNone: 'Select none'
			// 	}
			// },
			sDom: sDom,
			stateSave: false,
			// select:true,
			scrollX: true,
			serverSide: true,
			processing: true,
			destroy: true,
			paging: false,
			language: {
				processing: loading + ' ' + loading + ' ' + loading,
				// lengthMenu: `
				// 	Display<br>
				// 	<select name="dataTable_length" aria-controls="dataTable" class="form-select form-select-sm">
				// 		<option value="10">10</option>
				// 		<option value="20">20</option>
				// 		<option value="30">30</option>
				// 		<option value="40">40</option>
				// 		<option value="50">50</option>
				// 	</select>
				// `,
				// search: 'Cari',
				// searchPlaceholder: 'Masukkan kata kunci',
				// buttons: {
				// 	selectAll: 'Select all items',
				// 	selectNone: 'Select none'
				// }
			},
			ajax: {
				url: routeDatatable,
				data: {
					id_siswa: id_siswa
				}
			},
			columns: [{
					data: 'DT_RowIndex',
					name: 'DT_RowIndex',
					render: (data, type, row) => {
						return `<p class="m-0 p-1">${data}</p>`
					}
				},
				{
					data: 'no_induk',
					name: 'no_induk'
				},
				{
					data: 'nama_siswa',
					name: 'nama_siswa'
				},
			],
			// columnDefs: [{
			// 	orderable: false,
			// 	render: DataTable.render.select(),
			// 	targets: 0
			// }],
			// fixedColumns: {
			// 	start: 2
			// },
			
			// order: [
			// 	[1, 'asc']
			// ],
			// select: {
			// 	style: 'os',
			// 	selector: 'td:first-child'
			// }
		});
	}

	$('.btnKembali').click((e) => {
		e.preventDefault()
		$('.other-page').empty()
		$('.main-page').fadeIn()
	})

	$('.btnSimpan').click((e) => {
		alert('Masih dalam pengembangan!');
		return
		e.preventDefault()
		var data = new FormData($('#formDataKelas')[0])
		$('.btnSimpan').attr('disabled', true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
			url: '{{route("admin.kelasSiswa.naikKelasSave")}}',
			type: 'POST',
			data: data,
			async: true,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data) {
				if (data.status == 'success') {
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: data.message,
						showConfirmButton: false,
						timer: 1200
					})
					setTimeout(() => {
						$('.other-page').fadeOut(() => {
							$('#datatabel').DataTable().ajax.reload()
							location.reload()
						})
					}, 1100);
					// location.reload()
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Whoops',
						text: data.message,
						showConfirmButton: false,
						timer: 1300,
					})
				}
				$('.btnSimpan').attr('disabled', false).html('SIMPAN')
			}
		}).fail(() => {
			Swal.fire({
				icon: 'error',
				title: 'Whoops..',
				text: 'Terjadi kesalahan silahkan ulangi kembali',
				showConfirmButton: false,
				timer: 1300,
			})
			$('.btnSimpan').attr('disabled', false).html('SIMPAN')
		})
	})

	function renderTidakAda(noInduk,noIndukAda) {
		console.log(noInduk);
		var dataHtml = '';
		var nomor = 1;
		noInduk.split(',').forEach(element => {
			if (!noIndukAda.includes(element)) {
				dataHtml += `<tr>
					<td>${nomor}</td>
					<td>${element}</td>
				</tr>`;
				nomor+=1;
			}
		});
		$('#dataTableTidak tbody').html(dataHtml)
	}
</script>