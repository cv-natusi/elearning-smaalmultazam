@extends('main.layouts.index')

@push('style')
<link href="{{ asset('admin/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin/assets/plugins/datatable/css/select.dataTables.min.css') }}" rel="stylesheet">
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
	tbody > .selected {
		background-color: cornflowerblue;
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
								<th>No Induk</th>
								<th>NISN</th>
								<th>Nama Siswa</th>
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
<script src="{{ asset('admin/assets/plugins/datatable/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var routeDatatable = "{{route('admin.kelasSiswa.main')}}";
	var routeNaikKelas = "{{route('admin.kelasSiswa.naikKelasForm')}}";
	var routeImportNaikKelas = "{{route('admin.kelasSiswa.importNaikKelasForm')}}";
	var routeDataKelasAdd = "{{route('admin.kelasSiswa.add')}}";
	var routeDataKelasDelete = "{{route('admin.kelasSiswa.delete')}}";
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
		<'col-sm-2 templateNaikKelas'>
		<'col-sm-2 templateImportNaikKelas'>
		<'col-sm-3 templateTahunAjaranDanKelas'>
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
				data: {
					id_tahun_ajaran:id_tahun_ajaran,
					id_kelas:id_kelas
				}
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
					return `<p class="m-0 p-1">${data}</p>`
				}},
				{data:'no_induk', name:'no_induk'},
				{data:'nisn', name:'nisn'},
				{data:'nama_siswa', name:'nama_siswa'},
				{data:'kelas', name:'kelas'},
				{data:'tahun_ajaran', name:'tahun_ajaran'},
				{data:'actions', name:'actions'}
			],
		});
			
		const templateTambah = `
			<button onclick="tambahDataKelas()" class='btn btn-dark-brown text-white p-2 w-100'><i class='bx bx-plus' ></i>Tambah</button>
		`;
			
		const templateNaikKelas = `
			<button onclick="naikKelas()" class='btn btn-orange-brown text-white p-2 w-100'><i class='bx bx-trending-up' ></i>Naik Kelas</button>
		`;
		const templateImportNaikKelas = `
			<button onclick="importNaikKelas()" class='btn btn-primary text-white p-2 w-100'><i class='bx bx-upload' ></i>Import</button>
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
			<div  class='w-100'>
				<label class="pe-1" style="white-space:nowrap;">Kelas
				</label>
				<select name="id_kelas" aria-controls="id_kelas" class="form-select form-select-sm select2 d-inline-block" id="id_kelas" onchange="filter()">
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

		const templateTahunAjaranDanKelas = `
			<div class='d-flex gap-3'>
				<div class='w-100'>
					<label class="pe-1" style="white-space:nowrap;">Tahun Ajaran
					</label>
					<select name="id_tahun_ajaran" aria-controls="id_tahun_ajaran" class="form-select form-select-sm select2 d-inline-block" id="id_tahun_ajaran" onchange="filter()">
						<option value="">Semua</option>
						${tahun_ajaran}
					</select>
				</div>
				${templateKelas}
			</div>
		`;

		$("div.templateTahunAjaranDanKelas").html(templateTahunAjaranDanKelas)
		// $("div.templateKelas").html(templateKelas)
		$("div.templateTambah").html(templateTambah)
		$("div.templateNaikKelas").html(templateNaikKelas)
		$("div.templateImportNaikKelas").html(templateImportNaikKelas)
	}

	function tambahDataKelas(id='') {
		$('.main-page').hide();
		var url = routeDataKelasAdd
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

	function naikKelas() {
		$('.main-page').hide();
		var url = routeNaikKelas
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

	function importNaikKelas() {
		$('.main-page').hide();
		var url = routeImportNaikKelas
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

	function hapusKelasSiswa(id) {
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
				var url = routeDataKelasDelete
				$.post(url, {id:id})
				.done(function(data){
					console.log(data);
					if(data.metadata.code == 200){
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: data.metadata.message,
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
							text: data.metadata.message,
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