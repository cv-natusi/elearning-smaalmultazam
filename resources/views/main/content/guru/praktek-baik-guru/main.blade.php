@extends('main.layouts.index')

@push('style')
	<link href="{{ asset('admin/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
	<style>
		.btn-purple {
			background-color: #9594C3;
			border-color: #9594C3;
		}
		.btn-purple:hover {
			background-color: #9594C3;
			border-color: #9594C3;
			filter:contrast(130%);
		}
	</style>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card main-page">
			<div class="card-body">
				<div class="row mb-3">
					<div class="col-md-3">
						<button type="button" class="btn btn-primary" onclick="tambahBerita()"><i class='bx bx-plus'></i>Tambah Baru</button>
					</div>
				</div>
				<div class="p-1">
					<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTable">
						<thead>
							<tr>
								<th>No</th>
								<th>Penerbitan</th>
								<th>Judul</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						{{-- <tbody>
							<tr>
								<td>1</td>
								<td>{{date('Y-m-d H:i:s')}}</td>
								<td>Perilisan Perdana Buletin SMAS Al-Multazam “BASSAM”</td>
								<td>Aktif</td>
								<td>
									<button class="btn btn-dark btn-purple p-2"><i class='bx bx-edit-alt mx-1'></i></button>
									<button class="btn btn-secondary p-2"><i class='bx bx-power-off mx-1'></i></button>
									<button class="btn btn-danger p-2"><i class='bx bx-trash mx-1'></i></button>
								</td>
							</tr>
						</tbody> --}}
					</table>
				</div>
			</div>
		</div>
		<div class="other-page"></div>
	</div>
</div>
@endsection

@push('script')
	<script src="{{ asset('admin/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('admin/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
	<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
	<script src="{{ asset('admin/assets/js/ckeditor1/ckeditor.js') }}"></script>
	<script src="{{ asset('admin/assets/js/ckeditor1/adapters/jquery.js') }}"></script>
	<script src="{{asset('admin/content/js/main-berita.js')}}"></script>
	<!--Sweetalert -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		var routeDatatable = "{{route('guru.praktekBaikGuru.main')}}"
		var routeBeritaAdd = "{{route('guru.praktekBaikGuru.add')}}"
		var routeBeritaDelete = "{{route('guru.praktekBaikGuru.delete')}}"
		var routeBeritaAktif = "{{route('guru.praktekBaikGuru.aktif')}}"
		$(document).ready( async () => {
			await dataTable($('#status').val())
		})

		function filter() {
			dataTable($('#status').val())
		}

		async function dataTable(status='') {
			const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
			let sDom = `
				<'row mb-2'
					<'col-sm-2'l>
					<'col-sm-2 templateStatus'>
					<'col-sm-3'>
					<'col-sm-2'>
					<'col-sm-3'f>
				>
				<'row mt-2'<'col-sm-12'tr>>
				<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>
			`

			await $('#dataTable').DataTable({
				sDom: sDom,
				stateSave: false,
				scrollX: true,
				serverSide: true,
				processing: true,
				destroy: true,
				language: {
					processing: loading+' '+loading+' '+loading,
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
					search: 'Cari',
					searchPlaceholder: 'Masukkan kata kunci',
				},
				ajax: {
					url: routeDatatable,
					data: {status: status},
				},
				columns: [
					{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
						return `<p class="m-0 p-1">${data}</p>`
					}},
					{data:'tanggal', name:'tanggal'},
					{data:'judul', name:'judul'},
					{data:'status', name:'status'},
					{data:'actions', name:'actions'}
				],
			});

			const templateStatus = `
				<div class="d-flex">
					<label class="my-1 pe-1">Status</label>
					<select name="status" aria-controls="status" class="form-select form-select-sm" id="status" onchange="filter()">
						<option value="">Semua</option>
						<option value="1">Aktif</option>
						<option value="0">Tidak Aktif</option>
					</select>
				</div>
			`;

			$("div.templateStatus").html(templateStatus)
		}

		function tambahBerita(id='') {
			$('.main-page').hide();
			var url = routeBeritaAdd
			$.post(url, {id:id})
			.done(function(data){
				if(data.status == 'success'){
					$('.other-page').html(data.content).fadeIn();
				} else {
					$('.main-page').show();
				}
			})
			.fail(() => {
				$('.other-page').empty();
				$('.main-page').show();
			})
		}

		function aktifBerita(id) {
			var url = routeBeritaAktif
			$.post(url, {id:id})
			.done(function(data){
				console.log(data);
				if(data.code == 200){
					Swal.fire({
						icon: 'success',
						title: 'Berhasil',
						text: data.message,
						showConfirmButton: false,
						timer: 1200
					})
					setTimeout(async ()=>{
						await dataTable($('#status').val())
						// $('#dataTabel').DataTable().ajax.reload()
						// location.reload()
					}, 1100);
				} else {
					Swal.fire({
						icon: 'warning',
						title: 'Whoops',
						text: data.message,
						showConfirmButton: false,
						timer: 1300,
					})
				}
			})
			.fail(() => {
				Swal.fire({
					icon: 'error',
					title: 'Whoops..',
					text: 'Terjadi kesalahan silahkan ulangi kembali',
					showConfirmButton: false,
					timer: 1300,
				})
			})
		}

		function hapusBerita(id) {
			Swal.fire({
				title: "Apakah Anda Yakin?",
				text: "Data Tersebut Akan Dihapus!",
				icon: "warning",
				showCancelButton: true,
				confirmButtonColor: "#3085d6",
				cancelButtonColor: "#d33",
				confirmButtonText: "Ya, Hapus!"
			}).then((result) => {
				if (result.isConfirmed) {
					var url = routeBeritaDelete
					$.post(url, {id:id})
					.done(function(data){
						console.log(data);
						if(data.code == 200){
							Swal.fire({
								icon: 'success',
								title: 'Berhasil',
								text: data.message,
								showConfirmButton: false,
								timer: 1200
							})
							setTimeout(async ()=>{
								await dataTable($('#status').val())
								// $('#dataTabel').DataTable().ajax.reload()
								// location.reload()
							}, 1100);
						} else {
							Swal.fire({
								icon: 'warning',
								title: 'Whoops',
								text: data.message,
								showConfirmButton: false,
								timer: 1300,
							})
						}
					})
					.fail(() => {
						Swal.fire({
							icon: 'error',
							title: 'Whoops..',
							text: 'Terjadi kesalahan silahkan ulangi kembali',
							showConfirmButton: false,
							timer: 1300,
						})
					})
				}
			});
			
		}
	</script>
@endpush