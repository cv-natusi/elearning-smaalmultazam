@extends('main.layouts.index')

@section('content')

	<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-success">
							<h6 class="my-1">{{$siswa}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Siswa</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-secondary">
							<h6 class="my-1">{{$guru}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Guru</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-primary">
							<h6 class="my-1">{{$rapor}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">e-Rapor</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col">
			<div class="card radius-10">
				<div class="card-body">
					<div class="d-flex align-items-center">
						<div class="widgets-icons bg-light-warning">
							<h6 class="my-1">{{$dokumen}}</h6>
						</div>
						<div class="mx-auto">
							<h6 class="my-1">Dokumen</h6>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection