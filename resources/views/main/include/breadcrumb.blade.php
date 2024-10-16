<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3 pt-2">
	<nav aria-label="breadcrumb">
		<ol class="breadcrumb mb-0 p-0">
			{{-- @if(Auth::User()->level == '1') <!-- Admin --> --}}
			<li class="breadcrumb-item">
				<a href="{{route('dashboard')}}"><i class="bx bx-home-alt"></i></a>
			</li>
			{{-- @elseif(Auth::User()->level == '2') <!-- Petugas Sekolah -->
			<li class="breadcrumb-item">
				<a href="#"><i class="bx bx-home-alt"></i></a>
			</li>
			@elseif(Auth::User()->level == '3') <!-- Guru Pengajar -->
			<li class="breadcrumb-item">
				<a href="#"><i class="bx bx-home-alt"></i></a>
			</li>
			@endif --}}
			@if(isset($breadCrumb))
			@foreach($breadCrumb as $item)
			<li class="breadcrumb-item active" aria-current="page">{{$item}}</li>
			@endforeach
			@endif
			@isset($title)
			<li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
			@endisset
		</ol>
	</nav>
</div>