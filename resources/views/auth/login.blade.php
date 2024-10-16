<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{csrf_token()}}">
	<title>Login</title>
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
<body class="bg-login">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
			<div class="container">
				<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
					<div class="col mx-auto">
						<div class="card mb-0">
							<div class="card-body">
								<div class="p-4">
									<div class="mb-3 text-center">
										<img src="{{asset('assets/img/logo/logo.png')}}" width="60" alt="" />
									</div>
									<div class="text-center mb-4">
										<h5 class="">SMA AL - MULTAZAM</h5>
										<p class="mb-0">Silahkan masuk dengan akun Anda!</p>
									</div>
									<div class="form-body">
										<form class="row g-3 formLogin">
											<div class="col-12">
												<label for="email" class="form-label">Username *</label>
												<input type="text" class="form-control" id="email" name="email" placeholder="Username">
											</div>
											<div class="col-12">
												<label for="password" class="form-label">Password *</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" id="password" name="password" placeholder="Password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
													<label class="form-check-label" for="flexSwitchCheckChecked">Ingat Saya</label>
												</div>
											</div>
											<div class="col-md-6 text-end"><a href="auth-basic-forgot-password.html">Lupa Password ?</a>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary btnLogin">Login</button>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{asset('admin/assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{asset('admin/assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('admin/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('admin/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{asset('admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<!--Sweetalert -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<script>
		const doLogin = '{{route("auth.doLogin")}}'
		const routeDashboard = '{{route("dashboard")}}'
	</script>
	<!--app JS-->
	<script src="{{asset('admin/assets/js/app.js')}}"></script>
	<script src="{{asset('admin/content/js/auth/login.js')}}"></script>
</body>
</html>