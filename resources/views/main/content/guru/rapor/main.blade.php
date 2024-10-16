@extends('main.layouts.index')

@section('content')
<div class="row">
	<div class="col-12 mb-1">
		<div class="card">
			<div class="card-body">
				<label class="form-label" for="rapor">E-RAPOR</label>
				<select class="form-select select2" name="id" id="id">
					<option value="">-PILIH-</option>
					@foreach ($rapor as $item)
						<option value="{{$item->id_spreadsheet_share}}">{{$item->judul}}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="p-1">
					<iframe id="raporArea" class="w-100 min-vh-100" src=""></iframe>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('script')
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
	<script>
		var rapor = {{Illuminate\Support\Js::from($rapor)}}
		$(()=>{
			$('.select2').select2({
				theme: 'bootstrap-5',
			});
		})
		$('#id').change((e)=>{
			$('#raporArea').attr('src','')
			setTimeout(() => {
				rapor.forEach(element => {
					if (element.id_spreadsheet_share==$('#id').val()) {
						$('#raporArea').attr('src',element.link)
					}
				});
			}, 1000);
		})
	</script>
@endpush