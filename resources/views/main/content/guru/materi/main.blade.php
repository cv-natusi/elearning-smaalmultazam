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
								<th>Nama Mata Pelajaran</th>
								<th>Judul Materi</th>
								<th>Tanggal Upload</th>
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
	var routeDatatable = "{{route('guru.materi.main')}}";
	var routeMateriAdd = "{{route('guru.materi.add')}}";
	var routeMateriView = "{{route('guru.materi.add')}}";
	var routeMateriDelete = "{{route('guru.materi.delete')}}";
	var tahunAjaran = {{Illuminate\Support\Js::from($tahunAjaran)}};
	var kelas = {{Illuminate\Support\Js::from($kelas)}};
	var mataPelajaran = {{Illuminate\Support\Js::from($mataPelajaran)}};
	$(document).ready( async () => {
		await dataTable($('#status').val())
	})
	
	function filter() {
		dataTable($('#id_kelas').val(),$('#id_tahun_ajaran').val(),$('#id_semester').val())
	}
	
	async function dataTable(id_kelas='',id_tahun_ajaran='',id_semester='') {
		const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
		let sDom = `
		<'row mb-2'
		<'col-sm-2 templateTambah'>
		<'col-sm-2'l>
		<'col-sm-2 templateKelas'>
		<'col-sm-3 templateTahunAjaran'>
		<'col-sm-2 templateSemester'>
		<'col-sm-1'>
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
					id_kelas: id_kelas,
					id_tahun_ajaran: id_tahun_ajaran,
					id_semester: id_semester,
				},
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
					return `<p class="m-0 p-1">${data}</p>`
				}},
				{data:'nama_mapel', name:'nama_mapel'},
				{data:'judul_materi', name:'judul_materi'},
				{data:'tanggal', name:'tanggal'},
				{data:'actions', name:'actions'}
			],
		});
			
		const templateTambah = `
			<button onclick="tambahMateri()" class='btn btn-primary p-2 w-100'><i class='bx bx-plus' ></i>Tambah</button>
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
				<select name="id_kelas" aria-controls="id_kelas" class="form-select form-select-sm" id="id_kelas" onchange="filter()">
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
				<select name="id_tahun_ajaran" aria-controls="id_tahun_ajaran" class="form-select form-select-sm" id="id_tahun_ajaran" onchange="filter()">
					<option value="">Semua</option>
					${tahun_ajaran}
				</select>
			</div>
		`;
			
		const templateSemester = `
			<div class="d-inline">
				<label class="my-1 pe-1">Semester</label>
				<select name="id_semester" aria-controls="id_semester" class="form-select form-select-sm" id="id_semester" onchange="filter()">
					<option value="">Semua</option>
					<option value="1">Semester 1</option>
					<option value="0">Semester 2</option>
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
	
	function aktifMateri(id) {
		var url = routeMateriView
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