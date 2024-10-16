@extends('main.layouts.index')

@push('style')
<link href="{{ asset('admin/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="p-1">
					<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTable">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Mata Pelajaran</th>
								<th>Judul Soal</th>
								<th>Jml Soal</th>
								<th>Tanggal Dikerjakan</th>
								<th>Nilai KKM</th>
								<th>Nilai</th>
								<th>Status</th>
							</tr>
						</thead>
					</table>
				</div>
			</div>
		</div>
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
	var routeDatatable = "{{route('siswa.dataNilai.main')}}"
	$(document).ready(async ()=>{
		await dataTable()
	})

	async function dataTable() {
		const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
		let sDom = `
		<'row mb-2'
		<'col-sm-2'l>
		<'col-sm-3 templateTahunAjaran'>
		<'col-sm-2 templateSemester'>
		<'col-sm-2 templateMapel'>
		<'col-sm-3'>
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
				// data: {status: status},
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
					return `<p class="m-0 p-1">${data}</p>`
				}},
				{data:'nama_mapel', name:'nama_mapel'},
				{data:'judul', name:'judul'},
				{data:'jumlah_soal', name:'jumlah_soal'},
				{data:'waktu_mulai', name:'waktu_mulai'},
				{data:'kkm', name:'kkm'},
				{data:'nilai', name:'nilai'},
				{data:'status_lulus', name:'status_lulus'}
			],
		})
			
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
		
		// $("div.templateMapel").html(templateKelas)
		// $("div.templateTahunAjaran").html(templateTahunAjaran)
		// $("div.templateSemester").html(templateSemester)
	}

</script>
@endpush