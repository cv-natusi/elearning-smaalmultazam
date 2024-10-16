<div class="modal fade" id="modalPreview" data-bs-backdrop="static" tabindex="-1" aria-labelledby="modalPreview" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h1 class="modal-title fs-5" id="modalPreviewSoal">Soal - {{$soal->judul_soal}}</h1>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="formSoall">
					<div class="row">
						<div class="col-12" class="filePenunjang">
							@foreach ($pertanyaan as $item)
								<div class="d-flex">
									<div class="p-2">{{$item->nomor}}.</div>
									<div class="p-2 flex-grow-1">
										{!! $item->pertanyaan_text !!}
										<div class="row">
										@foreach ($item->pertanyaan_file as $item_file)
											@if ($item_file->type_file=='gambar')
											<div class="col-4">
												<img src="{{url('uploads/elearning/pertanyaan')}}/{{$item_file->file}}" alt="gambar_{{$item_file->id_pertanyaan_file}}" class="w-100">
											</div>
											<div class="col-8"></div>
											@endif
										@endforeach
										@foreach ($item->pertanyaan_file as $item_file)
										@if ($item_file->type_file=='audio')
											<div class="col-4">
												<audio id="audioOutput" class="mx-auto mt-auto w-100" controls src="{{url('uploads/elearning/pertanyaan')}}/{{$item_file->file}}"></audio>
											</div>
											<div class="col-8"></div>
											@endif
										@endforeach
										@foreach ($item->pertanyaan_file as $item_file)
											@if ($item_file->type_file=='link')
											<div class="col-4">
												<a class="btn btn-primary" href="{{$item_file->file}}" target="_blank">Buka Link</a>
											</div>
											<div class="col-8"></div>
											@endif
										@endforeach
										@foreach ($item->pertanyaan_file as $item_file)
											@if ($item_file->type_file=='video')
											<div class="col-4">
												{!! $item_file->file !!}
											</div>
											<div class="col-8"></div>
											@endif
										@endforeach
										</div>
										@foreach ($item->pilihan_jawaban as $item_pilihan)
										<div class="row">
											<label for="jawaban{{ $item_pilihan->id_pilihan_jawaban }}" id="labelJawaban{{ $item_pilihan->id_pilihan_jawaban }}" class="@if($item_pilihan->benar) text-success @endif">
												{{$item_pilihan->prefix_pilihan}}. {{$item_pilihan->pilihan_text}} @if($item_pilihan->benar) {{"(BENAR)"}} @endif
											</label>
										</div>
										@endforeach
									</div>
								</div>
								<hr>
							@endforeach
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script>
	var myModal = new bootstrap.Modal(document.getElementById('modalPreview'))
	var myModalEl = document.getElementById('modalPreview')
	$(()=>{
		myModal.show()
	})
	myModalEl.addEventListener('hidden.bs.modal', function (event) {
		$('.modal-page').html('')
	})
</script>