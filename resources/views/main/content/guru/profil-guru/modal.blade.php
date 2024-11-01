{{-- Modal tambah tugas tambahan --}}
<div class="modal fade" id="tambahTugasTambahan" tabindex="-1" aria-labelledby="tambahTugasTambahanModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="tambahTugasTambahanModalLabel">{{ $data ? 'EDIT' : 'TAMBAH' }} TUGAS TAMBAHAN</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
                <form id="formSaveTugas">
                    <input type="hidden" name="id_tugas_tambahan" value="{{ $data ? $data->id_tugas_tambahan : '' }}">
                    <div class="row">
                        <div class="col">
                            <label for="password_baru" class="form-label">Tugas Tambahan *</label>
							<div class="input-group">
								<input type="text" class="form-control border-end-0" id="nama_tugas_tambahan" name="nama_tugas_tambahan" placeholder="Nama Tugas" value="{{ $data ? $data->nama_tugas_tambahan : '' }}">
							</div>
                        </div>
                    </div>
                </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary btnSimpanTugas">SIMPAN</button>
			</div>
		</div>
	</div>
</div>

<script>
    $('.btnSimpanTugas').click((e) => {
		e.preventDefault()
		var data = new FormData($('#formSaveTugas')[0])
		$('.btnSimpanTugas').attr('disabled',true).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>LOADING...')
		$.ajax({
				url: '{{route("guru.profilGuru.saveTugasTambahan")}}',
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
							location.reload()
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
					$('.btnSimpanTugas').attr('disabled',false).html('SIMPAN')
				}
			}).fail(()=>{
				Swal.fire({
					icon: 'error',
					title: 'Whoops..',
					text: 'Terjadi kesalahan silahkan ulangi kembali',
					showConfirmButton: false,
					timer: 1300,
				})
				$('.btnSimpanTugas').attr('disabled',false).html('SIMPAN')
			})
	})
</script>
