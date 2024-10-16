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
	<div class="col-12">
		<div class="card main-page">
			<div class="card-body">
				<div class="p-1">
					<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTable">
						<thead>
							<tr>
								<th>No</th>
								<th>Judul Soal</th>
								<th>Nama Mata Pelajaran</th>
								<th>Guru Pengampu</th>
								<th>Tanggal Berlaku Soal</th>
								<th>Jumlah Soal</th>
								<th>Nilai KKM</th>
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
<div class="modal-page"></div>
@endsection

@push('script')
<script src="{{ asset('admin/assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<!--Sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
	var routeDatatable = "{{route('admin.soal.main')}}"
	var routePreview = "{{route('admin.soal.preview')}}"
	var tahunAjaran = {{Illuminate\Support\Js::from($tahun_ajaran)}};
	var kelas = {{Illuminate\Support\Js::from($kelas)}};
	var mataPelajaran = {{Illuminate\Support\Js::from($mataPelajaran)}};
	$(document).ready(async()=>{
        await dataTable()
	})

	async function filter() {
		await dataTable($('#id_tahun_ajaran').val(),$('#id_kelas').val(),$('#id_semester').val(),$('#id_mapel').val())
	}

	async function dataTable(id_tahun_ajaran='',id_kelas='',id_semester='',id_mapel='') {
		const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
		let sDom = `
		<'row mb-2'
		<'col-sm-2'l>
		<'col-sm-3 templateTahunAjaran'>
		<'col-sm-2 templateKelas'>
		<'col-sm-2 templateMataPelajaran'>
		<'col-sm-1'>
		>
		<'row mt-2'<'col-sm-12'tr>>
		<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>
		`
		await $('#dataTable').DataTable({
            sDom: sDom,
			stateSave: false,
			scrollX:true,
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
					id_tahun_ajaran: id_tahun_ajaran,
					id_kelas: id_kelas,
					id_semester: id_semester,
					id_mapel: id_mapel,
				},
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
					return `<p class="m-0 p-1">${data}</p>`
				}},
				{data:'judul_soal', name:'judul_soal'},
				{data:'nama_mapel', name:'nama_mapel'},
				{data:'nama_guru', name:'nama_guru'},
				{data:'tanggal', name:'tanggal'},
				{data:'jumlah_soal', name:'jumlah_soal'},
				{data:'kkm', name:'kkm'},
				{data:'actions', name:'actions'}
			],
		})
        // const templateTambah = `
		// 	<button onclick="tambahSoal()" class='btn btn-primary p-2 w-100'><i class='bx bx-plus' ></i>Tambah</button>
		// `;
			
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
			
		var mata_pelajaran = '';

		mataPelajaran.forEach(element => {
			if (element.id_mapel==id_mapel) {
				mata_pelajaran += `<option value="${element.id_mapel}" selected>${element.nama_mapel}</option>`
			} else {
				mata_pelajaran += `<option value="${element.id_mapel}">${element.nama_mapel}</option>`
			}
		});

		const templateMataPelajaran = `
			<div class="d-inline">
				<label class="my-1 pe-1">Mata Pelajaran</label>
				<select name="id_mapel" aria-controls="id_mapel" class="form-select form-select-sm" id="id_mapel" onchange="filter()">
					<option value="">Semua</option>
					${mata_pelajaran}
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
		// $("div.templateTambah").html(templateTambah)
		$("div.templateTahunAjaran").html(templateTahunAjaran)
		$("div.templateMataPelajaran").html(templateMataPelajaran)
		// $("div.templateSemester").html(templateSemester)
	}
	
	$('.btnKerjakan').click((e)=>{
		e.preventDefault()
	})
	
	$('.btnMulai').click((e)=>{
		e.preventDefault()
		window.location = routeKerjakan
		// console.log(routeKerjakan);
	})
    
	function tambahSoal(id='') {
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
    
	function previewSoal(id='') {
		// $('.main-page').hide();
		var url = routePreview
		$.post(url, {id:id})
		.done(function(data){
			if(data.status == 'success'){
				$('.modal-page').html(data.content).fadeIn();
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
				title: 'Whoops',
				text: "Terjadi Kesalahan Sistem",
				showConfirmButton: false,
				timer: 1300,
			})
		})
	}

	function hiddenNilai(id='') {
		var url = routeShowNilai
		$.post(url, {id:id})
		.done(function(data){
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
				title: 'Whoops',
				text: "Terjadi Kesalahan Sistem",
				showConfirmButton: false,
				timer: 1300,
			})
		})
	}

</script>
@endpush