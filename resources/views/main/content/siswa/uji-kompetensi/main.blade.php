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
								<th>Tanggal Mulai</th>
								<th>Tanggal Selesai</th>
								<th>Jumlah Soal</th>
								<th>Nilai KKM</th>
								<th>Jenis Soal</th>
								<th>Aksi</th>
							</tr>
						</thead>
						{{-- <tbody>
							<tr>
								<td>No</td>
								<td>Nama Mata Pelajaran</td>
								<td>Judul Soal</td>
								<td>Tanggal Mulai</td>
								<td>Tanggal Selesai</td>
								<td>Jumlah Soal</td>
								<td>Nilai KKM</td>
								<td>Jenis Soal</td>
								<td><button class="btn btn-primary btnKerjakan"><i class="bx bx-key"></i>Kerjakan</button></td>
							</tr>
						</tbody> --}}
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="pendahuluanModal" tabindex="-1" aria-labelledby="pendahuluanModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="pendahuluanModalLabel">Pendahuluan</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btnMulai" id="btn-kerjakan" onclick="mulaiKerjakan()">MULAI KERJAKAN</button>
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
	var myModal = new bootstrap.Modal(document.getElementById('pendahuluanModal'), {
		backdrop: 'static',
		keyboard: false
	})
	var routeKerjakan = "{{route('siswa.kerjakan.main')}}"
	$(document).ready(()=>{
		$('#dataTable').DataTable({
			ajax: "{{route('siswa.ujiKomtensi.main')}}",
			destroy: true,
			scrollX: true,
			processing: true,
			serverSide: true,
			columns: [
				{
					data: 'DT_RowIndex',
					name: 'DT_RowIndex',
				},
				{
					data: 'mata_pelajaran.nama_mapel',
					name: 'mata_pelajaran.nama_mapel',
				},
				{
					data: 'judul_soal',
					name: 'judul_soal',
				},
				{
					data: 'mulai_pengerjaan',
					name: 'mulai_pengerjaan',
				},
				{
					data: 'selesai_pengerjaan',
					name: 'selesai_pengerjaan',
				},
				{
					data: 'pertanyaan_count',
					name: 'pertanyaan_count',
				},
				{
					data: 'kkm',
					name: 'kkm',
				},
				{
					data: 'DT_RowIndex',
					name: 'DT_RowIndex',
				},
				{
					data: 'actions',
					name: 'actions',
				}
			]
		})
	})

	async function kerjakanSoal(id,pendahuluan){
		await myModal.show()
		$('#btn-kerjakan').data('id',id) // Set id soal untuk di passing ke controller
        $('.modal-body').html(pendahuluan)
	}
	async function mulaiKerjakan(){
		const id = await $('#btn-kerjakan').data('id')
		$.post("{{route('siswa.kerjakan.store')}}",{ids:id}).done((data, status, xhr)=>{
			window.location = routeKerjakan+'?ids='+id+'&idjs='+data.response.id_jawaban_siswa
		}).fail((e)=>{
			console.log(e)
		})
	}
	$('.btn-close').click( _ => {
		$('#btn-kerjakan').data('id','') // Reset data id ketika modal di close
	})
</script>
@endpush