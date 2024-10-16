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
			<div class="card-header bg-main-website text-white">
				Absensi Guru
			</div>
			<div class="card-body">
				<div class="p-1">
					<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTable">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Guru</th>
								<th>Absen Datang</th>
								<th>Lokasi Datang</th>
								<th>Absen Pulang</th>
								<th>Lokasi Pulang</th>
							</tr>
						</thead>
						{{-- <tbody>
							<tr>
								<td>1</td>
								<td>{{date('Y-m-d H:i:s')}}</td>
								<td>1234567</td>
								<td>Ahmad</td>
								<td>2045</td>
								<td>089654415652</td>
								<td class="text-truncate" style="max-width: 150px">Lorem ipsum dolor, sit amet consectetur adipisicing elit. Esse tempora debitis voluptas rem neque officia ut dolorum perferendis molestias. Repudiandae necessitatibus adipisci facilis culpa sint delectus, repellat quam ut earum?</td>
							</tr>
						</tbody> --}}
					</table>
				</div>
			</div>
		</div>
		<div class="other-page"></div>
	</div>
</div>
<div class="modal-page"></div>
@endsection

@push('script')
<script src="{{ asset('admin/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var routeOpenMap = "{{route('guruPiket.absensiGuru.openMap')}}";
	var routeDatatable = "{{route('guruPiket.absensiGuru.main')}}";
	var routeExportPdf = "{{route('guruPiket.absensiGuru.exportPdf')}}";
	$(document).ready( async () => {
		await dataTable($('#tanggal').val())
	})

	async function filter() {
		await dataTable($('#tanggal').val())
	}
	
	async function dataTable(tanggal="{{date('Y-m-d')}}") {
		const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
		let sDom = `
		<'row mb-2'
		<'col-sm-3 d-flex templateTanggal'>
		<'col-sm-6 templateTahunAjaran'>
		<'col-sm-3 d-flex templateExport'>
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
				data: {
					tanggal: tanggal
				},
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
					return `<p class="m-0 p-1">${data}</p>`
				}},
				{data:'nama', name:'nama'},
				{data:'absen_datang', name:'absen_datang'},
				{data:'lokasi_datang', name:'lokasi_datang'},
				{data:'absen_pulang', name:'absen_pulang'},
				{data:'lokasi_pulang', name:'lokasi_pulang'}
			],
		});
			
		const templateTanggal = `
			<div class="input-group me-2">
				<label class="input-group-text" for="tanggal">Tanggal</label>
				<input type="date" class="form-control" id="tanggal" name="tanggal" onchange="filter()" value="${tanggal}">
			</div>
		`;
			
		const templateKelas = `
			<div class="d-inline">
				<label class="my-1 pe-1">Kelas</label>
				<select name="status" aria-controls="status" class="form-select form-select-sm" id="status" onchange="filter()">
					<option value="">Semua</option>
					<option value="1">Aktif</option>
					<option value="0">Tidak Aktif</option>
				</select>
			</div>
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
			
		const templateExport = `
			<div class="btn-group ms-auto">
				<button onclick="exportPdf()" class='text-white btn btn-sm btn-warning p-2 w-100'><i class='bx bx-file-export'></i>Export Pdf</button>
				<button type="button" class="text-white btn btn-sm btn-warning dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
					<span class="visually-hidden">Toggle Dropdown</span>
				</button>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="javascript:void(0)" onclick="exportPdf()">Export Pdf</a>
					<a class="dropdown-item" href="javascript:void(0)" onclick="exportExcel()">Expert Excel</a>
				</div>
			</div>
		`;
		
		// $("div.templateKelas").html(templateKelas)
		// $("div.templateTahunAjaran").html(templateTahunAjaran)
		$("div.templateTanggal").html(templateTanggal)
		$("div.templateExport").html(templateExport)
	}
	
	function openMap(id='',absen='') {
		$('.modal-page').html('');
		var url = routeOpenMap
		$.post(url, {id:id,absen:absen})
		.done(function(data){
			if(data.status == 'success'){
				$('.modal-page').html(data.content).fadeIn();
			} else {
				$('.modal-page').html('');
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
			$('.modal-page').html('');
			Swal.fire({
				icon: 'error',
				title: 'Whoops..',
				text: 'Terjadi kesalahan silahkan ulangi kembali',
				showConfirmButton: false,
				timer: 1300,
			})
		})
	}
	
	function hapusMateri(id) {
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
				var url = routeMateriDelete
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

	function exportPdf() {
		var url = routeExportPdf;
		window.open(url+'?tanggal='+$('#tanggal').val(),'_blank')
	}

	function exportExcel() {
		console.log('excel');
	}
</script>
@endpush