<div class="modal fade" id="modalFile" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalFileLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="modalFileLabel">File Pendukung</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formPertanyaanFile">
					<div class="row">
						<input type="hidden" name="id_pertanyaan" value="{{$id_pertanyaan}}">
						<div class="col-12" class="filePenunjang">
							<div class="row areaPenunjang">
								<div class="col-12 col-md-6 mb-3 d-grid">
									<label class="form-label" for="file_gambar">Gambar</label>
									<div class="w-100 outputAreaGambar d-none">
										<center class="mb-3">
											<a class="pan" id="btnOutPut">
												<img id="outPut" class="rounded mx-auto d-block responsive w-50 img-thumbnail">
											</a>
										</center>
									</div>
									<button class="btn btn-default btnTambahGambar mx-auto"><i class='bx bx-plus-medical'></i> Pilih Gambar</button>
									<input type="file" class="form-control d-none" id="file_gambar" name="file_gambar" accept="image/*" onchange="loadFile(event)">
								</div>
								<div class="col-12 col-md-6 mb-3 d-grid">
									<label class="form-label" for="file_audio">Audio</label>
									<div class="w-100 outputAreaAudio d-none d-grid">
										<audio id="audioOutput" class="mx-auto mt-auto" controls src=""></audio>
										<div id="result"></div>
									</div>
									<button class="btn btn-default btnTambahAudio mx-auto mt-auto"><i class='bx bx-plus-medical'></i> Pilih Audio</button>
									<input type="file" class="form-control d-none" name="file_audio" id="file_audio" accept="audio/*" onchange="previewFile()">
								</div>
								<div class="col-12 mb-3">
									<label class="form-label" for="file_video">Link Video</label>
									<div class="mx-auto" id="videoOutput" style="width:fit-content;"></div>
									<input type="text" class="form-control" name="file_video" id="file_video">
								</div>
								<div class="col-12 mb-3">
									<label class="form-label" for="file_link">Link Lainya</label>
									<input type="text" class="form-control" name="file_link" id="file_link">
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary btnSimpanFile">Simpan</button>
			</div>
		</div>
	</div>
</div>
<script>
	var pertanyaan_file = {{Illuminate\Support\Js::from($pertanyaan_file)}}
	$(()=>{
		$('.pan').pan()
		var myModal = new bootstrap.Modal(document.getElementById('modalFile'), {
			keyboard: false,
			backdrop: 'static'
		})
		myModal.show()
		setFile(pertanyaan_file)
	})

	$('.btnSimpanFile').click((e)=>{
		e.preventDefault()
		var formPertanyaanFile = new FormData($('#formPertanyaanFile')[0])
		$.ajax({
			url:"{{route('guru.soalTulis.storePertanyaanFile')}}",
			type:"POST",
			data:formPertanyaanFile,
			contentType:false,
			processData:false
		})
		.done(function(data){
			if (data.status=='success') {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				})
			} else {
				Swal.fire({
					icon: 'warning',
					title: 'Gagal',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				})
			}
		})
		.fail(()=>{
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: "Terjadi Kesalahan Sistem",
				showConfirmButton: false,
				timer: 1200
			})
		})
	})

	$('.btnTambahGambar').click((e)=>{
		e.preventDefault()
		$('#file_gambar').click()
	})
	$('.btnTambahAudio').click((e)=>{
		e.preventDefault()
		$('#file_audio').click()
	})

	function loadFile(event) {
		var btn = $('#btnOutPut')[0] // html DOM Object
		var outPut = $('#outPut')[0]
		outPut.src = URL.createObjectURL(event.target.files[0])
		outPut.onload = function(){
			URL.revokeObjectURL(outPut.src)
		}
		btn = $('#btnOutPut').attr('data-big',URL.createObjectURL(event.target.files[0]))
		$('#outPut').addClass('img-thumbnail')
		$('.outputAreaGambar').removeClass('d-none')
	};

	function previewFile() {
		var preview = document.querySelector('audio');
		var file = document.querySelector('input[type=file][name=file_audio]').files[0];
		var reader = new FileReader();
		
		reader.addEventListener("load", function () {
			preview.src = reader.result;
		}, false);
		
		if (file) {
			reader.readAsDataURL(file);
		}
		$('.outputAreaAudio').removeClass('d-none')
	}

	function setFile(pertanyaan_file) {
		// refreshPenunjang()
		pertanyaan_file.forEach(element => {
			if (element.type_file=='gambar') {
				$('#btnOutPut').attr('data-big',`{!! url('uploads/elearning/pertanyaan') !!}/${element.file}`)
				$('#outPut').attr('src',`{!! url('uploads/elearning/pertanyaan') !!}/${element.file}`)
				$('.outputAreaGambar').removeClass('d-none')
			} else if (element.type_file=='audio') {
				$('#audioOutput').attr('src',`{!! url('uploads/elearning/pertanyaan') !!}/${element.file}`)
				$('.outputAreaAudio').removeClass('d-none')
			} else if (element.type_file=='video') {
				$('#file_video').val(element.file)
				$('#videoOutput').html(element.file)
			} else if (element.type_file=='link') {
				$('#file_link').val(element.file)
			}
		});
	}

	function refreshPenunjang() {
		$('#btnOutPut').data('big',`{!! url('uploads/default.jpg') !!}`)
		$('#outPut').attr('src',`{!! url('uploads/default.jpg') !!}`)
		$('#audioOutput').attr('src',`{!! url('uploads/default.jpg') !!}`)
		$('#videoOutput').html('')
		$('#linkOutput').attr('href','')
	}
</script>