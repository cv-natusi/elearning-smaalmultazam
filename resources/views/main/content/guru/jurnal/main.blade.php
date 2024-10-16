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
<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
		<path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
	</symbol>
</svg>
<div class="row">
	<div class="col-sm-12">
		<div class="card main-page">
			<div class="card-header bg-main-website text-white">
				Jurnal Guru
			</div>
			<div class="card-body">
				<div class="alert alert-warning d-flex align-items-center" role="alert">
					<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
					<div>
						Hanya dapat mengisi, mengedit dan menghapus di hari yang sama! (1 per hari)
					</div>
				</div>
				<div class="p-1">
					<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTable">
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal Upload</th>
								<th>Isi Jurnal</th>
								<th>Aksi</th>
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

@endsection

@push('script')
<script src="{{ asset('admin/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var routeDatatable = "{{route('guru.jurnal.main')}}";
	var routeMateriAdd = "{{route('guru.jurnal.add')}}";
	var routeMateriDelete = "{{route('guru.jurnal.delete')}}";
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
		<'col-sm-2 templateTambah'>
		<'col-sm-2'>
		<'col-sm-2 templateKelas'>
		<'col-sm-3 templateTahunAjaran'>
		<'col-sm-3 templateSemester'>
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
				{data:'jurnal', name:'jurnal'},
				{data:'actions', name:'actions'}
			],
		});
			
		const templateTambah = `
			<button onclick="tambahMateri()" class='btn btn-primary p-2 w-100'><i class='bx bx-plus' ></i>Tambah</button>
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
			
		const templateSemester = `
			<div class="d-inline">
				<label class="my-1 pe-1">Semester</label>
				<select name="status" aria-controls="status" class="form-select form-select-sm" id="status" onchange="filter()">
					<option value="">Semua</option>
					<option value="1">Aktif</option>
					<option value="0">Tidak Aktif</option>
				</select>
			</div>
		`;
		
		$("div.templateKelas").html(templateKelas)
		$("div.templateTambah").html(templateTambah)
		$("div.templateTahunAjaran").html(templateTahunAjaran)
		$("div.templateSemester").html(templateSemester)
	}
		
	function tambahMateri(id='') {
		$('.main-page').hide();
		var url = routeMateriAdd
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
</script>
@endpush