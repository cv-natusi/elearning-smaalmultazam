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
				<div class="p-1">
					<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTable">
						<thead>
							<tr>
								<th>No</th>
								<th>NISN</th>
								<th>Nama Siswa</th>
								<th>Jenis Kelamin</th>
								<th>Alamat</th>
								{{-- <th>Kelas</th> --}}
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
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
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var routeDatatable = "{{route('admin.dataSiswa.main')}}";
	var routeDataSiswaAdd = "{{route('admin.dataSiswa.add')}}";
	var routeDataSiswaDelete = "{{route('admin.dataSiswa.delete')}}";
	var routeDataSiswaImport = "{{route('admin.dataSiswa.import')}}";
	var routeDataSiswaResetPassword = "{{route('admin.dataSiswa.resetPassword')}}";
	$(document).ready( async () => {
		await dataTable()
	})
	
	async function dataTable() {
		const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
		let sDom = `
		<'row mb-2'
		<'col-sm-2 templateTambah'>
		<'col-sm-2 templateImport'>
		<'col-sm-4 templateTahunAjaran'>
		<'col-sm-4'f>
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
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
					return `<p class="m-0 p-1">${data}</p>`
				}},
				{data:'nisn', name:'nisn'},
				{data:'nama', name:'nama'},
				{data:'gender', name:'gender'},
				{data:'alamat', name:'alamat'},
				// {data:'kelas', name:'kelas'},
				{data:'status', name:'status'},
				{data:'actions', name:'actions'}
			],
		});
			
		const templateTambah = `
			<button onclick="tambahSiswa()" class='btn btn-dark-brown text-white p-2 w-100'><i class='bx bx-plus' ></i>Tambah</button>
		`;
		
		const templateImport = `
			<button onclick="importDataGuru()" class='btn btn-orange-brown text-white p-2 w-100'><i class='bx bx-upload'></i>Import Data</button>
		`;

		const templateTahunAjaran = `
			<div class="d-inline">
				<label class="my-1 pe-1">Tahun Ajaran</label>
				<select name="status" aria-controls="status" class="form-select form-select-sm" id="status" onchange="filter()">
					<option value="">Semua</option>
					<option value="1">Aktif</option>
					<option value="0">Tidak Aktif</option>
				</select>
			</div>
		`;

		// $("div.templateTahunAjaran").html(templateTahunAjaran)
		$("div.templateTambah").html(templateTambah)
		$("div.templateImport").html(templateImport)
	}

	function importDataGuru() {
		// console.log('test');
		$('.main-page').hide();
		var url = routeDataSiswaImport
		$.get(url)
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

	function tambahSiswa(id='') {
		$('.main-page').hide();
		var url = routeDataSiswaAdd
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
	
	function hapusSiswa(id) {
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
				var url = routeDataSiswaDelete
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
							await dataTable()
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

	function resetPassword(id) {
		Swal.fire({
			title: "Apakah Anda Yakin?",
			text: "Password User tersebut akan DIRESET!",
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Reset!"
		}).then((result) => {
			if (result.isConfirmed) {
				var url = routeDataSiswaResetPassword
				$.post(url, {id:id})
				.done(function(data){
					console.log(data);
					if(data.status == 'success'){
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: data.message,
							showConfirmButton: false,
							timer: 1200
						})
						setTimeout(async ()=>{
							await dataTable()
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