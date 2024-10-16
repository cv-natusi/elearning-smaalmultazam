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
								<th>Guru</th>
								<th>Mata Pelajaran</th>
								<th>Kelas</th>
								<th>Tahun Ajaran</th>
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
	var routeDatatable = "{{route('admin.mapelPengampu.main')}}";
	var routeDataSiswaAdd = "{{route('admin.mapelPengampu.add')}}";
	var routeDataSiswaDelete = "{{route('admin.mapelPengampu.add')}}";
	var tahunAjaran = {{Illuminate\Support\Js::from($tahun_ajaran)}};
	var kelas = {{Illuminate\Support\Js::from($kelas)}};
	$(document).ready( async () => {
		await dataTable()
	})

	async function filter() {
		await dataTable($('#id_tahun_ajaran').val(),$('#id_kelas').val())
	}
	
	async function dataTable(id_tahun_ajaran='',id_kelas='') {
		const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
		let sDom = `
		<'row mb-2'
		<'col-sm-2 templateTambah'>
		<'col-sm-3 templateTahunAjaran'>
		<'col-sm-3 templateKelas'>
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
				data: {
					id_kelas:id_kelas,
					id_tahun_ajaran:id_tahun_ajaran
				}
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
					return `<p class="m-0 p-1">${data}</p>`
				}},
				{data:'nama_guru', name:'nama_guru'},
				{data:'nama_mata_pelajaran', name:'nama_mata_pelajaran'},
				{data:'nama_kelas', name:'nama_kelas'},
				{data:'nama_tahun_ajaran', name:'nama_tahun_ajaran'},
				{data:'actions', name:'actions'}
			],
		});
			
		const templateTambah = `
			<button onclick="tambahMapel()" class='btn btn-primary p-2 w-100'><i class='bx bx-plus' ></i>Tambah</button>
		`;

		var kelas_option = '';

		kelas.forEach(element => {
			if (element.id_kelas==id_kelas) {
				kelas_option += `<option value="${element.id_kelas}" selected>${element.nama_kelas}</option>`
			} else {
				kelas_option += `<option value="${element.id_kelas}">${element.nama_kelas}</option>`
			}
		});
			
		const templateKelas = `
			<div class="d-inline">
				<label class="my-1 pe-1">Kelas</label>
				<select name="id_kelas" aria-controls="id_kelas" class="form-select form-select-sm d-inline" id="id_kelas" onchange="filter()">
					<option value="">Semua</option>
					${kelas_option}
				</select>
			</div>
		`;

		var tahun_ajaran = '';

		tahunAjaran.forEach(element => {
			if (element.id_tahun_ajaran==id_tahun_ajaran) {
				tahun_ajaran += `<option value="${element.id_tahun_ajaran}" selected>${element.nama_tahun_ajaran}</option>`
			} else {
				tahun_ajaran += `<option value="${element.id_tahun_ajaran}">${element.nama_tahun_ajaran}</option>`
			}
		});
			
		const templateTahunAjaran = `
			<div class="d-inline">
				<label class="my-1 pe-1">Tahun Ajaran</label>
				<select name="id_tahun_ajaran" aria-controls="id_tahun_ajaran" class="form-select form-select-sm d-inline" id="id_tahun_ajaran" onchange="filter()">
					<option value="">Semua</option>
					${tahun_ajaran}
				</select>
			</div>
		`;

		$("div.templateTahunAjaran").html(templateTahunAjaran)
		$("div.templateKelas").html(templateKelas)
		$("div.templateTambah").html(templateTambah)
	}

	function tambahMapel(id='') {
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
	
	function hapusMapel(id) {
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