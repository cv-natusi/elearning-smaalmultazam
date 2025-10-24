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
	<div class="container">
		<div class="row">
			<div class="card radius-10">
				<div class="card-body text-center">
					<h5 style="text-align: center;" class="my-1">Grafik Pengunjung 30 Hari Terakhir</h5>
			
					<div style="width: 90%; margin: auto;">
						<canvas id="visitorChart"></canvas>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@push('script')
	<script>
		const labels = @json($labels);
        const guestData = @json($guestData);
        const userData = @json($userData);

        const chartData = {
            labels: labels,
            datasets: [
                {
                    label: 'Pengunjung Login (User)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1.5,
                    data: userData,
                    fill: true,
                    tension: 0.1
                }
            ]
        };

        const config = {
            type: 'line',
            data: chartData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
            }
        };

        const myChart = new Chart(
            document.getElementById('visitorChart'),
            config
        );
	</script>
@endpush