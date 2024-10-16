@extends('main.layouts.index')

@push('style')
<link href="{{ asset('admin/assets/plugins/datatable/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body d-flex">
				<img src="{{ Auth::user()->foto ? asset(Auth::user()->foto) : asset('admin/assets/images/avatars/no-avatar.png')}}" class="rounded-circle" alt="profil" width="120" height="120">
				<div class="d-inline-block ms-4">
					<table>
						<tbody>
							<tr>
								<td>Nama Lengkap</td>
								<td>: @isset($bio) {{$bio->nama}} @endisset</td>
							</tr>
							<tr>
								<td>NISN</td>
								<td>: @isset($bio) {{$bio->nisn}} @endisset</td>
							</tr>
							<tr>
								<td>Kelas</td>
								<td>: @isset($bio) @isset($bio->kelas_siswa) {{$bio->kelas_siswa->kelas->nama_kelas}} @endisset @endisset</td>
							</tr>
							<tr>
								<td>Tanggal Lahir</td>
								<td>: @isset($bio) {{$bio->tgl_lahir}} @endisset</td>
							</tr>
						</tbody>
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

@endpush
