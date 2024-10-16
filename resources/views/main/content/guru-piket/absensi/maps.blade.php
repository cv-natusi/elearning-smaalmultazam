<!-- Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="mapModalLabel">ABSEN {{$absen?'PULANG':'DATANG'}}</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="d-flex">
					<div class="d-flex flex-column">
						<span>TANGGAL</span>
						<span>NAMA</span>
						<span>ABSEN</span>
					</div>
					<div class="d-flex flex-column ms-4">
						<span>: {{$absensi->absen_datang}}</span>
						<span>: {{$absensi->guru?$absensi->guru->nama:''}}</span>
						<span>: {{$absen?'ABSEN PULANG':'ABSEN DATANG'}}</span>
					</div>
				</div>
				<hr>
				<div class="d-flex">
					<iframe 
					width="700"
					height="370" 
					frameborder="0" 
					scrolling="no" 
					marginheight="0" 
					marginwidth="0" 
					class="mx-auto"
					src="https://maps.google.com/maps?q={{$absen?$absensi->lokasi_pulang:$absensi->lokasi_datang}}&hl=id&z=14&amp;output=embed"
					>
					</iframe>
				</div>
				<br />
			</div>
		</div>
	</div>
</div>
<script>
	var myModal = new bootstrap.Modal(document.getElementById('mapModal'), {
		backdrop: 'static',
		keyboard: false
	})
	$(document).ready(function () {
		myModal.show()
	});
</script>