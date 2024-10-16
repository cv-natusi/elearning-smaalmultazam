<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
	<symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
		<path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
	</symbol>
</svg>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-header bg-main-website text-white">
				Import Siswa
			</div>
			<div class="card-body">
				<div class="alert alert-primary d-flex align-items-center" role="alert">
					<svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
					<div>
						Tutorial <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#tutorial">(Klik disini)</a>
					</div>
				</div>
				<form id="formImportGuru">
					<div class="row">
						<div class="col-12 col-md-2 mb-3 p-4 bg-light">
							<label class="form-label">Pilih kolom</label>
							<div class="areaPilihan d-grid gap-1"></div>
						</div>
						<div class="col-12 col-md-10 mb-3 p-4 bg-light">
							<label class="form-label d-block">Urutan kolom di dalam table yang di upload</label>
							<div class="btn-group areaKolom" role="group" aria-label="Kolom">
								<input type="radio" class="d-none no_induk" name="no_induk" id="no_induk" autocomplete="off" checked readonly>
								<label class="btn btn-outline-primary btn-sm no_induk" for="no_induk">No Induk *</label>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12 mb-3">
							<label for="nama" class="form-label">File Excel (.xls, xlsx) *</label>
							<input type="file" class="form-control" name="file" id="file" accept=".xls, .xlsx">
							<input type="hidden" name="urutan" id="urutan">
						</div>
					</div>
					<hr>
					<div class="d-flex gap-2">
						<button class="btn btn-secondary px-4 btnKembali">KEMBALI</button>
						<button class="btn btn-primary px-4 btnUpload">UPLOADS</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="tutorial" tabindex="-1">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tutorial</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row overflow-hidden">
					<p><strong>Perhatikan</strong></p>
					<ul class="ms-4">
						<li>
							File wajib excel (.xls / .xlsx)
						</li>
						<li>
							Tabel harus tanpa header
						</li>
						<img class="img-fluid" src="{{asset('admin/assets/images/tutorial/excelguru.PNG')}}" alt="">
						<li>
							Pilih kolom yang akan diupload (disamakan dengan urutan kolomnya excel)
						</li>
						<img class="img-fluid" src="{{asset('admin/assets/images/tutorial/select.png')}}" alt="">
						<li>
							Kolom yang bertanda (*) wajib ada di excel
						</li>
						<img class="img-fluid" src="{{asset('admin/assets/images/tutorial/wajib.png')}}" alt="">
					</ul>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<script>
	var kolom = [
	{
		id:'nama',
		name:'Nama'
	},
	{
		id:'nisn',
		name:'NISN'
	},
	{
		id:'tmp_lahir',
		name:'Tempat Lahir'
	},
	{
		id:'tgl_lahir',
		name:'Tanggal Lahir'
	},
	{
		id:'gender',
		name:'Jenis Kelamin'
	},
	{
		id:'nama_ayah',
		name:'Nama Ayah'
	},
	{
		id:'nama_ibu',
		name:'Nama Ibu'
	},
	{
		id:'alamat',
		name:'Alamat'
	},
	{
		id:'no_tlp',
		name:'No Telp'
	},
	{
		id:'th _masuk',
		name:'Tahun Masuk'
	},
	// {
	// 	id:'foto',
	// 	name:'Foto'
	// },
	{
		id:'status',
		name:'Status'
	},
	]
	var selected = []
	$(document).ready(function () {
		$('.select2').select2({
			theme: 'bootstrap-5',
		});
		renderPilihan()
	})

	function setUrutan() {
		let selectedId = $.map( selected, function( n, i ) {
				return n.id;
			});
		$('#urutan').val(selectedId.join(','))
	}
	
	function deleteSelectedKolom(kolom) {
		console.log('sss');
		const newSelected = selected.filter((item,index) => item.id != $(kolom).data('id'));
		selected = newSelected
		$('.'+$(kolom).data('id')).hide()
		renderPilihan()
		setUrutan()
	}
	
	function addSelectedKolom(kolom) {
		selected.push({id:$(kolom).data('id'),name:$(kolom).data('name')})
		$('.areaKolom').append(`
		<input type="radio" class="d-none ${$(kolom).data('id')}" name="${$(kolom).data('name')}" id="${$(kolom).data('id')}" checked>
		<label class="btn btn-sm btn-outline-primary ${$(kolom).data('id')}" for="${$(kolom).data('name')}" data-name="${$(kolom).data('name')}" data-id="${$(kolom).data('id')}" onclick="deleteSelectedKolom(this)">${$(kolom).data('name')}</label>
		`)
		renderPilihan()
		setUrutan()
	}
	
	function renderPilihan() {
		$('.areaPilihan').html('')
		kolom.forEach(element => {
			// if ($.inArray(element,selected)) {
				// if (selected.includes(element)) {
					if (selected.some(e => e.id == element.id)) {
						console.log('a');
						return
					}
					$('.areaPilihan').append(`<button type="button" class="btn btn-outline-primary btn-sm" data-id="${element.id}" data-name="${element.name}" onclick="addSelectedKolom(this)">${element.name}</button>`)
				});
			}
			
			$('.btnKembali').click((e)=>{
				e.preventDefault()
				$('.other-page').empty()
				$('.main-page').fadeIn()
			})
			
			$('.btnUpload').click((e) => {
				e.preventDefault()
				var data = new FormData($('#formImportGuru')[0])
				$('.btnUpload').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
				$.ajax({
					url: '{{route("admin.dataSiswa.importSave")}}',
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
							setTimeout(()=>{
								$('.other-page').fadeOut(()=>{
									$('#datatabel').DataTable().ajax.reload()
									location.reload()
								})
							}, 1100);
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
						$('.btnUpload').attr('disabled',false).html('UPLOADS')
					}
				}).fail(()=>{
					Swal.fire({
						icon: 'error',
						title: 'Whoops..',
						text: 'Terjadi kesalahan silahkan ulangi kembali',
						showConfirmButton: false,
						timer: 1300,
					})
					$('.btnUpload').attr('disabled',false).html('UPLOADS')
				})
			})
			
		</script>