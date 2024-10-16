<script src="{{url('admin/assets/js/bootstrap.bundle.min.js')}}"></script><!--bootstrapJS-->
<!--startPlugins-->
<script src="{{url('admin/assets/js/jquery.min.js')}}"></script>
<script src="{{url('admin/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{url('admin/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
<script src="{{url('admin/assets/plugins/flatpickr/flatpickr.min.js')}}"></script>
<script src="{{url('admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="{{url('admin/assets/js/pace.min.js')}}"></script>
<script src="{{ asset('admin/assets/plugins/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/ckeditor1/ckeditor.js') }}"></script>
<script src="{{ asset('admin/assets/js/ckeditor1/adapters/jquery.js') }}"></script>
<!--endPlugins-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script><!-- Select 2 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> <!-- Datepicker-->
<script src="{{url('admin/assets/js/app.js')}}"></script><!--appJS-->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> <!-- Select2 -->
<script type="text/javascript">
    $.ajaxSetup({
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		}
	})

	// $(function() {
    //     $('#datepicker').datepicker();
    // });
</script>
@stack('script')
