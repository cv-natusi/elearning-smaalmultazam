@extends('main.layouts.index')

@push('style')
<link href="{{ asset('admin/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('zoom/css/jquery.pan.css')}}"><!--zoomImage-->
<style>
	.gradient-green-yellow {
		background-color: #45ab73;
		background-image: linear-gradient(74deg, #45ab73 0%, #e4e07f 75%, #ffffff 100%);
		color: #ffffff;
	}
</style>
@endpush

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card main-page">
			<div class="card-header bg-main-website text-white">
				Nilai Siswa
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-4 mb-3">
						<label class="form-label" for="tahun_ajaran_id">Pilih Tahun Ajaran</label>
						<select name="tahun_ajaran_id" aria-controls="tahun_ajaran_id" class="form-select select2" id="tahun_ajaran_id" onchange="filter()">
							<option value="">Semua</option>
							@foreach ($tahun_ajaran as $item)
								<option value="{{$item->id_tahun_ajaran}}">{{$item->nama_tahun_ajaran}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-12 col-md-4 mb-3">
						<label class="form-label" for="semester">Pilih Semester</label>
						<select name="semester" aria-controls="semester" class="form-select select2" id="semester" onchange="filter()">
							<option value="">Semua</option>
							<option value="1">SEMESTER 1</option>
							<option value="2">SEMESTER 2</option>
						</select>
					</div>
					<div class="col-12 col-md-4 mb-3">
						<label class="form-label" for="kelas_id">Pilih Kelas</label>
						<select name="kelas_id" aria-controls="kelas_id" class="form-select select2" id="kelas_id" onchange="filter()">
							<option value="">Semua</option>
							@foreach ($kelas as $item)
								<option value="{{$item->id_kelas}}">{{$item->nama_kelas}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-12 col-md-4 mb-3">
						<label class="form-label" for="mapel_id">Pilih Mata Pelajaran</label>
						<select name="mapel_id" aria-controls="mapel_id" class="form-select select2" id="mapel_id" onchange="filter()">
							<option value="">Semua</option>
							@foreach ($mata_pelajaran as $item)
								<option value="{{$item->id_mapel}}">{{$item->nama_mapel}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-12 col-md-4 mb-3">
						<label class="form-label" for="soal_id">Pilih Soal</label>
						<select name="soal_id" aria-controls="soal_id" class="form-select select2" id="soal_id" onchange="filter()">
							<option value="">Semua</option>
							@foreach ($soal as $item)
								<option value="{{$item->id_soal}}">{{$item->judul_soal}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<table class="table-responsive table table-striped table-bordered stripe row-border order-column" style="width:100%" id="dataTable">
							<thead>
								<tr>
									<th>No</th>
									<th>NISN</th>
									<th>Nama Siswa</th>
									<th>KKM</th>
									<th>Nilai</th>
									<th>Status</th>
									{{-- <th>Aksi</th> --}}
								</tr>
							</thead>
						</table>
					</div>
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
<script src="{{asset('zoom/js/jquery.pan.js')}}"></script><!--zoomImage-->
<script>
	var routeDatatable = "{{route('guru.nilaiSiswa.main')}}"
	$('.pan').pan()
	$(document).ready(async function () {
		$('.select2').select2({
			theme: 'bootstrap-5',
		});
		await dataTable()
	})

	async function filter() {
		await dataTable()
	}

	async function dataTable() {
		const loading = '<div class=spinner-grow text-primary" role="status"> <span class="visually-hidden">Loading...</span></div>'
		let sDom = `
		<'row mb-2'
		<'col-sm-2'l>
		<'col-sm-6'>
		<'col-sm-4'f>
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
					tahun_ajaran_id: $('#tahun_ajaran_id').val(),
					semester: $('#semester').val(),
					kelas_id: $('#kelas_id').val(),
					mapel_id: $('#mapel_id').val(),
					soal_id: $('#soal_id').val(),
				},
			},
			columns: [
				{data:'DT_RowIndex', name:'DT_RowIndex', render: (data, type, row)=>{
					return `<p class="m-0 p-1">${data}</p>`
				}},
				{data:'nisn', name:'nisn'},
				{data:'nama_siswa', name:'nama_siswa'},
				{data:'kkm', name:'kkm'},
				{data:'nilai', name:'nilai'},
				{data:'status_lulus', name:'status_lulus'},
				// {data:'actions', name:'actions'}
			],
		})
        
	}

	function loadFile(event) {
		var btn = $('#btnOutPut')[0] // html DOM Object
		var outPut = $('#outPut')[0]
		outPut.src = URL.createObjectURL(event.target.files[0])
		outPut.onload = function(){
			URL.revokeObjectURL(outPut.src)
		}
		btn = $('#btnOutPut').attr('data-big',URL.createObjectURL(event.target.files[0]))
		$('#outPut').addClass('img-thumbnail')
	};

	$('.btnSimpan').click((e) => {
		e.preventDefault()
		var data = new FormData($('#formProfilGuru')[0])
		$('.btnSimpan').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("guru.profilGuru.save")}}',
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
					$('.btnSimpan').attr('disabled',false).html('SIMPAN')
				}
			}).fail(()=>{
				Swal.fire({
					icon: 'error',
					title: 'Whoops..',
					text: 'Terjadi kesalahan silahkan ulangi kembali',
					showConfirmButton: false,
					timer: 1300,
				})
				$('.btnSimpan').attr('disabled',false).html('SIMPAN')
			})
	})
</script>
@endpush