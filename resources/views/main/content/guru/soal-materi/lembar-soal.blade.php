<link rel="stylesheet" type="text/css" href="{{asset('zoom/css/jquery.pan.css')}}"><!--zoomImage-->
<style>
	.filled-file {
		background-color: #d6d4ff;
	}
	.filled-pilihan {
		background-color: #f8facd;
	}
	.filled-jawaban {
		background-color: #cefacd;
	}
	.square-box-parent {
		width: 60px;
		height: 60px;
		display: flex;
	}
	.square-box-child {
		width: 20px;
		height: 20px;
		text-align: center;
	}
	#header-side-soal {
		background-color: #a8a8f7;
	}
	.select-pertanyaan-area:hover {
		box-shadow: 0 1rem 1rem rgba(25,255,255,.5)!important;
		transform: scale(1.1);
	}
	.form-check {
		padding-left: 0.5rem !important;
		min-width: 110px;
	}
	.file {
		width: 200px;
	}
	.map-soal {
		padding: 0;
		background-color: azure;
	}
	.btnTambahPilihan {
		color: #0000FF;
		border: dashed 2px #0000FF;
	}
	.spinner-div {
		position: absolute;
		top: 0;
		width: 100%;
		height: 100%;
		display: flex;
		background-color: rgba(255, 255, 255, 0.76);
	}
	.spinner-div span {
		display: ruby-text;
		margin: auto;
	}
	.pertanyaanMap .active {
		background-color: #a8a8f7;
	}
	.pertanyaanMap .done {
		background-color: aquamarine;
	}
	.outputArea {
		min-height: 200px;
	}
	.btnTambahGambar,
	.btnTambahVideo,
	.btnTambahAudio,
	.btnTambahLink {
		color: #0000FF;
		border: dashed 2px #0000FF;
	}
</style>
@php
	$alphabet = range('A','Z');
	function jawabanBenar($pilihan) {
		$alphabet = range('A','Z');
		$j = 'X';
		foreach ($pilihan as $key => $value) {
			if ($value->benar) {
				$j = $alphabet[$key];
			}
		}
		return $j;
	}
@endphp
<div class="row">
	<div class="col-12">
		<form id="formPertanyaan">
			<div class="card">
				<div class="card-body row">
					<div class="col-12">
						<div class="mb-3">
							<label for="judul_soal" class="form-label">Judul Soal</label>
							<input type="text" class="form-control" name="judul_soal" id="judul_soal" placeholder="Judul Materi" @isset($soal) value="{{$soal->judul_soal}}" @endisset disabled>
							<input type="hidden" class="form-control" name="id_soal" id="id_soal" placeholder="Judul Materi" @isset($soal) value="{{$soal->id_soal}}" @endisset>
						</div>
					</div>
					<hr>
					<div class="col-10 position-relative">
						<input type="hidden" name="id_pertanyaan" id="id_pertanyaan" value="">
						<div class="mb-3">
							<label for="pertanyaan_text" class="form-label">Pertanyaan *</label>
							<textarea type="text" name="pertanyaan_text" id="pertanyaan_text"></textarea>
						</div>
						<div class="spinner-div" id="loadingPertanyaan">
							<div class="m-auto">
								<span class="spinner-border" role="status" aria-hidden="true"></span><br> <span>Loading...</span>
							</div>
						</div>
					</div>
					<div class="col-2 map-soal position-relative">
						<div class="w-100 mb-3 shadow p-2 rounded" id="header-side-soal">
							<h5 id="totalSelesai" class="text-white"></h5>
						</div>
						<div class="overflow-y-scroll d-flex flex-wrap gap-1 w-100 pertanyaanMap" style="max-height: 400px;overflow-y:scroll;overflow-x:visible">
							@foreach ($pertanyaans as $p)
								<div class="shadow m-auto bg-white square-box-parent d-flex flex-wrap cursor-pointer select-pertanyaan-area" onclick="getPertanyaan('{{$p->id_pertanyaan}}')">
									<div class="text-center pertanyaan" style="width: 60px;height:40px" id="pertanyaan_{{$loop->index+1}}"><h4 id="nomor_{{$loop->index+1}}">{{$loop->index+1}}</h4></div>
									<div class="square-box-child filled-pilihan" id="pilihan_{{$loop->index+1}}">{{count($p->pilihan_jawaban)}}</div>
									<div class="square-box-child filled-jawaban @if(jawabanBenar($p->pilihan_jawaban)=='X') text-danger @endif" id="jawaban_{{$loop->index+1}}">{{jawabanBenar($p->pilihan_jawaban)}}</div>
									<div class="square-box-child filled-file" id="file_{{$loop->index+1}}">{{count($p->pertanyaan_file)}}</div>
								</div>
							@endforeach
						</div>
						<div class="spinner-div" id="loadingMapNomor">
							<div class="m-auto">
								<span class="spinner-border" role="status" aria-hidden="true"></span><br> <span>Loading...</span>
							</div>
						</div>
					</div>
					<div class="col-12 mb-3">
						<label class="form-label" for="file_penunjang">File Pendukung</label>
						<button class="btn btn-primary btnPendukung ms-3"><i class='bx bx-folder-open'></i> File</button>
					</div>
					<div class="col-12 position-relative">
						<label for="pilihan_jawaban" class="form-label">Pilihan Jawaban</label>
						<div class="mb-3" id="jawaban_area">
							<div class="d-flex align-items-center jawaban">
								<h4 class="m-auto">{{$alphabet[0]}}</h4>
								<textarea class="form-control ms-2" name="pilihan_jawaban[0][pilihan_text]" id="pilihan_text_0" rows="1"></textarea>
								<input class="form-control ms-2 file" type="file" name="pilihan_jawaban[0][file]" id="file_0" accept="image/*">
								<div class="form-check d-flex align-items-center">
									<input class="form-check-input m-auto benar" type="radio" name="benar" value="0" id="benar_0">
									<label class="form-check-label w-fit">
										Jawaban Benar
									</label>
								</div>
							</div>
						</div>
						<button class="btn btn-default btnTambahPilihan"><i class='bx bx-plus-medical'></i> Tambah Pilihan Jawaban</button>
						<div class="spinner-div" id="loadingPilihanJawaban">
							<div class="m-auto">
								<span class="spinner-border" role="status" aria-hidden="true"></span><br> <span>Loading...</span>
							</div>
						</div>
					</div>
					<div class="col-12 mt-3">
						<label for="poin_pertanyaan" class="form-label">Poin Pertanyaan</label>
						<input type="number" min="0" name="poin_pertanyaan" id="poin_pertanyaan" class="form-control" value="0" placeholder="Masukkan angka poin pertanyaan ...">
					</div>
					<div class="col-12 mt-3">
						<a href="{{route('guru.soalTulis.main')}}" class="btn btn-secondary btnKeluar">KELUAR</a>
						<button class="btn btn-primary btnSimpanPertanyaan">SIMPAN PERTANYAAN</button>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="col-12 modalArea"></div>
</div>
<script src="{{asset('zoom/js/jquery.pan.js')}}"></script><!--zoomImage-->
<script>
	var routeGetPertanyaan = "{{route('guru.soalTulis.pertanyaanForm')}}"
	var alphabet = ['A','B','C','D','E']
	$(()=>{
		var pertanyaans = {{Illuminate\Support\Js::from($pertanyaans)}}
		hitungSelesai(pertanyaans)
		if (pertanyaans.length==0) {
			Swal.fire({
				icon: 'warning',
				title: 'Whoops',
				text: 'Data pertanyaan tidak ditemukan',
				showConfirmButton: true,
			})
		} else {
			getPertanyaan(pertanyaans[0].id_pertanyaan)
		}
	})

	$('#poin_pertanyaan').keyup(function(){
		var poinForm = $(this)
		var poinValue = parseInt(poinForm.val())
		if(poinValue > 0){
			poinForm.val(poinValue)
		}else{
			poinForm.val(0)
		}
	})

	$('.btnTambahPilihan').click((e)=>{
		e.preventDefault()
		let jmlJawaban = $('.jawaban').length
		$('#jawaban_area').append(`<div class="d-flex align-items-center jawaban">
								<h4 class="m-auto">${alphabet[jmlJawaban]}</h4>
								<textarea class="form-control ms-2" name="pilihan_jawaban[${jmlJawaban}][pilihan_text]" id="pilihan_text_${jmlJawaban}" rows="1"></textarea>
								<input class="form-control ms-2 file" type="file" name="pilihan_jawaban[${jmlJawaban}][file]" id="file_${jmlJawaban}" accept="image/*">
								<div class="form-check d-flex align-items-center">
									<input class="form-check-input m-auto benar" type="radio" name="benar" value="${jmlJawaban}" id="benar_${jmlJawaban}">
									<label class="form-check-label w-fit">
										Jawaban Benar
									</label>
								</div>
							</div>`)
		if (jmlJawaban>=4) {
			$('.btnTambahPilihan').hide()
		}
	})

	$('.btnSimpanPertanyaan').click((e)=>{
		e.preventDefault()
		console.log('aaa');
		simpanPertanyaan()
	})

	function simpanPertanyaan() {
		var data = new FormData($('#formPertanyaan')[0])
		var pertanyaan_text = CKEDITOR.instances.pertanyaan_text.getData();
		data.append('pertanyaan_text',pertanyaan_text);
		// $.post("{{route('guru.soalTulis.pertanyaanStore')}}",{data:data})
		// $.post("{{route('guru.soalTulis.pertanyaanStore')}}")
		$.ajax({
			type: "POST",
			url: "{{route('guru.soalTulis.pertanyaanStore')}}",
			data: data,
			processData: false,
			contentType: false,
		}).done((data)=>{
			if (data.status=='success') {
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				})
				refreshMapPertanyaan(data.data.pertanyaans)
				hitungSelesai(data.data.pertanyaans)
			} else {
				Swal.fire({
					icon: 'warning',
					title: 'Gagal',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				})
			}
		}).fail(()=>{
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: data.message,
				showConfirmButton: false,
				timer: 1200
			})
		})
	}

	var pertanyaan_text = CKEDITOR.replace('pertanyaan_text', {
		// uiColor: '#CCEAEE'
		toolbarCanCollapse:false,
	});

	function autoSave() {
		
	}

	function hitungSelesai(pertanyaans) {
		let totalSelesai = 0
		pertanyaans.forEach(element => {
			if (element.pilihan_jawaban.map((x) => x.benar==true).length>0) {
				totalSelesai++
			}
		});
		$('#totalSelesai').html('Selesai '+totalSelesai+'/'+pertanyaans.length)
	}

	function getPertanyaan(id_pertanyaan) {
		$('.spinner-div').show()
		$.post(routeGetPertanyaan, {id_pertanyaan:id_pertanyaan,id_soal:{{Illuminate\Support\Js::from($soal->id_soal)}}})
		.done(function(data){
			console.log(data);
			if(data.status == 'success'){
				Swal.fire({
					icon: 'success',
					title: 'Berhasil',
					text: data.message,
					showConfirmButton: false,
					timer: 1200
				})
				$('#id_pertanyaan').val(data.data.pertanyaan.id_pertanyaan)
				$('#poin_pertanyaan').val(data.data.pertanyaan.poin)
				refreshMapPertanyaan(data.data.pertanyaans)
				// $('#pertanyaan_text').val(data.data.pertanyaan.pertanyaan_text)
				CKEDITOR.instances.pertanyaan_text.setData(data.data.pertanyaan.pertanyaan_text)
				hitungSelesai(data.data.pertanyaans)
				if (data.data.pilihan_jawaban.length>0) {
					let pilihan_jawaban = ''
					data.data.pilihan_jawaban.forEach((element,index) => {
						pilihan_jawaban += `<div class="d-flex align-items-center jawaban">
								<h4 class="m-auto">${alphabet[index]}</h4>
								<textarea class="form-control ms-2" name="pilihan_jawaban[${index}][pilihan_text]" id="pilihan_text_${index}" rows="1">${element.pilihan_text}</textarea>
								<input type="hidden" name="pilihan_jawaban[${index}][id_pilihan_jawaban]" value="${element.id_pilihan_jawaban}">
								<input class="form-control ms-2 file" type="file" name="pilihan_jawaban[${index}][file]" id="file_${index}" accept="image/*">
								<div class="form-check d-flex align-items-center">
									<input class="form-check-input m-auto benar" type="radio" name="benar" value="${index}" id="benar_${index}" ${element.benar?'checked':''}>
									<label class="form-check-label w-fit">
										Jawaban Benar
									</label>
								</div>
							</div>`
					});
					$('#jawaban_area').html(pilihan_jawaban)
				} else {
					$('#poin_pertanyaan').val(0)
					$('#jawaban_area').html(`<div class="d-flex align-items-center jawaban">
								<h4 class="m-auto">${alphabet[0]}</h4>
								<textarea class="form-control ms-2" name="pilihan_jawaban[0][pilihan_text]" id="pilihan_text_0" rows="1"></textarea>
								<input class="form-control ms-2 file" type="file" name="pilihan_jawaban[0][file]" id="file_0" accept="image/*">
								<div class="form-check d-flex align-items-center">
									<input class="form-check-input m-auto benar" type="radio" name="benar" value="0" id="benar_0">
									<label class="form-check-label w-fit">
										Jawaban Benar
									</label>
								</div>
							</div>`)
				}
				$('.spinner-div').hide()
			} else {
				Swal.fire({
					icon: 'warning',
					title: 'Whoops',
					text: data.message,
					showConfirmButton: false,
					timer: 1300,
				})
				$('#loadingMapNomor').hide()
			}
		})
		.fail(() => {
			Swal.fire({
				icon: 'error',
				title: 'Whoops..',
				text: 'Terjadi kesalahan silahkan ulangi kembali',
				showConfirmButton: false,
				timer: 1300,
			})
			$('#loadingMapNomor').hide()
		})
	}

	function refreshMapPertanyaan(pertanyaans) {
		$('.pertanyaan').removeClass('active')
		$('.pertanyaan').removeClass('done')
		pertanyaans.forEach((element,index) => {
			$(`#pilihan_${index+1}`).html(element.pilihan_jawaban.length)
			$(`#jawaban_${index+1}`).html(jawabanBenar(element.pilihan_jawaban))
			if ($('#id_pertanyaan').val()==element.id_pertanyaan) {
				$(`#pertanyaan_${index+1}`).addClass('active')
			} else if (element.pilihan_jawaban.length>0&&jawabanBenar(element.pilihan_jawaban)!='X') {
				$(`#pertanyaan_${index+1}`).addClass('done')
			}
		});
	}

	function jawabanBenar(pilihan_jawaban) {
		let j = 'X';
		pilihan_jawaban.forEach((element,index) => {
			if (element.benar) {
				j = alphabet[index];
			}
		});
		return j;
	}

	$('.btnPendukung').click((e)=>{
		e.preventDefault()
		$('.btnPendukung').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.get("{{route('guru.soalTulis.getPertanyaanFile')}}",{id_pertanyaan:$('#id_pertanyaan').val()})
		.done(function(data){
			if (data.status=='success') {
				$('.modalArea').html(data.content)
			} else {
				Swal.fire({
					icon: 'warning',
					title: 'Whoops',
					text: data.message,
					showConfirmButton: false,
					timer: 1300,
				})
			}
			$('.btnPendukung').attr('disabled',false).html("<i class='bx bx-folder-open'></i> File")
		})
		.fail(()=>{
			Swal.fire({
				icon: 'error',
				title: 'Whoops..',
				text: 'Terjadi kesalahan silahkan ulangi kembali',
				showConfirmButton: false,
				timer: 1300,
			})
			$('.btnPendukung').attr('disabled',false).html("<i class='bx bx-folder-open'></i> File")
		})
	})
</script>