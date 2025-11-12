@extends('main.layouts.index')

@section('content')
	<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.min.css" rel="stylesheet" />
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
						<button id="btn-download-excel" class="btn btn-success mt-3"><i class='bx bx-download'></i> Download</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.17.2/dist/sweetalert2.all.min.js"></script>
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
                        beginAtZero: true,
						ticks: {
							stepSize: 1
						}
                    }
                },
                responsive: true,
            }
        };

        const myChart = new Chart(
            document.getElementById('visitorChart'),
            config
        );

		$('#btn-download-excel').on('click', function() {
			// Tangkap tombolnya di sini
			const btn = $(this);

			Swal.fire({
				title: 'Download Laporan',
				html:
					'<select id="swal-input2" class="form-select">' +
					'<option value="">Pilih Kategori</option>' +
					'<option value="harian">Harian (30 Hari Terakhir)</option>' +
					'<option value="bulanan">Bulanan (12 Bulan Terakhir)</option>' +
					'<option value="tahunan">Tahunan (5 Tahun Terakhir)</option>' +
					'</select>',
				icon: 'warning',
				focusConfirm: false,
				showCancelButton: true,
				confirmButtonColor: '#09ce0d',
				cancelButtonColor: '#6c757d',
				confirmButtonText: 'Ya, Download!',
				cancelButtonText: 'Batal',
				preConfirm: () => {
					const kategori = document.getElementById('swal-input2').value;
					if (!kategori) {
						Swal.showValidationMessage('Kategori wajib dipilih!');
						return false;
					}
					return { kategori: kategori };
				}
			}).then((result) => {
				if (result.isConfirmed && result.value) {
					const kategori = result.value.kategori;
					
					// Tampilkan status loading di tombol
					btn.prop('disabled', true).html('<i class="bx bx-loader bx-spin"></i> Memulai...');

					// === INI BAGIAN PENTING YANG BERUBAH ===

					// 1. Hapus form lama jika ada (untuk keamanan)
					$('#download-form-hidden').remove();

					// 2. Buat form dinamis yang tersembunyi
					let form = $('<form>', {
						'id': 'download-form-hidden',
						'method': 'POST', // Sesuai dengan route Anda
						'action': "{{ route('download_visitor_data') }}"
					}).append(
						// 3. Tambahkan CSRF token
						$('<input>', {
							'type': 'hidden',
							'name': '_token',
							'value': "{{ csrf_token() }}"
						})
					).append(
						// 4. Tambahkan input kategori
						$('<input>', {
							'type': 'hidden',
							'name': 'kategori',
							'value': kategori
						})
					);

					// 5. Tambahkan form ke body dan kirim (submit)
					$('body').append(form);
					form.submit();

					// 6. Kembalikan tombol ke state normal setelah beberapa saat.
					// Kita tidak bisa tahu pasti kapan download selesai, 
					// jadi kita reset tombol setelah jeda singkat.
					setTimeout(() => {
						btn.prop('disabled', false).html('<i class="bx bx-download"></i> Download');
					}, 3000); // Reset setelah 3 detik
				}
			});
		});
	</script>
@endpush