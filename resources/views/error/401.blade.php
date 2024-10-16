<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--favicon-->
	<link rel="icon" href="{{ url('admin/assets/images/logo-profile.png') }}" type="image/png" />
	<link href="{{ url('admin/assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
	<link href="{{ url('admin/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
	<link href="{{ url('admin/assets/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="{{ url('admin/assets/css/pace.min.css') }}" rel="stylesheet" />
	<!-- Bootstrap CSS -->
	<link href="{{ url('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
	<link href="{{ url('admin/assets/css/bootstrap-extended.css') }}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
	<link href="{{ url('admin/assets/css/app.css') }}" rel="stylesheet">
	<link href="{{ url('admin/assets/css/icons.css') }}" rel="stylesheet">
</head>
<body>
	<div class="error-404 d-flex align-items-center justify-content-center">
		<div class="container">
			<div class="card py-5">
				<div class="row g-0">
					<div class="col col-xl-5">
						<div class="card-body p-4">
							<h1 class="display-1"><span class="text-primary">4</span><span class="text-danger">0</span><span class="text-success">1</span></h1>
							<h2 class="font-weight-bold display-4">Unauthorized</h2>
							<p>Tidak dapat mengakses halaman yang diminta.
								<br>Server tidak dapat memverifikasi bahwa Anda berwenang untuk mengakses dokumen yang diminta.</p>
							<div class="mt-5">
								<a href="{{route('dashboard')}}" class="btn btn-primary btn-lg px-md-5 radius-30">Go Home</a>
								<a href="{{route('auth.logout')}}" class="btn btn-outline-dark btn-lg ms-3 px-md-5 radius-30">Logout</a>
							</div>
						</div>
					</div>
					<div class="col-xl-7">
						{{-- <img src="https://cdn.searchenginejournal.com/wp-content/uploads/2019/03/shutterstock_1338315902.png" class="img-fluid" alt=""> --}}
						<img src="{{asset('admin/assets/images/401.png')}}" class="img-fluid" alt="">
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!-- Bootstrap JS -->
	<script src="{{asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{asset('admin/assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('admin/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('admin/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{asset('admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<script>
	</script>
	<script src="{{asset('admin/assets/js/app.js')}}"></script>
</body>
</html>