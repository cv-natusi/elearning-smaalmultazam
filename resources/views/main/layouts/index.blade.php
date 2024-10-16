<!DOCTYPE html>
<html lang="en" class="color-sidebar sidebarcolor1">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<meta name="csrf-token" content="{{csrf_token()}}">
    <title>SMAS AL-MULTAZAM</title>
    <link rel="shortcut icon" href="{{isset($identitas)?asset('uploads/identitas/'.$identitas->favicon):asset('assets/img/logo/logo.png')}}" type="image/x-icon">
    @include('main.include.style')
    <style>
        .bg-main-website {
			background-image: url("{{URL::asset('/admin/assets/images/bg-sidebar.png')}}");
		}
    </style>
</head>
<body>
    <!--startWrapper-->
	<div class="wrapper">
		@include('main.include.sidebar')<!--importSidebar-->

		@include('main.include.header')<!--importHeader-->

		<div class="page-wrapper p-3">
			@include('main.include.breadcrumb')<!--importBreadcrumb-->
			@yield('content')<!--includeContent-->
		</div>

		<div class="overlay toggle-icon"></div><!--overlay-->

		<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a><!--backToTopButton-->

		@include('main.include.footer')<!--importFooter-->
	</div>
	<!--endWrapper-->

	@include('main.include.script') <!--importJavaScript-->
</body>
</html>