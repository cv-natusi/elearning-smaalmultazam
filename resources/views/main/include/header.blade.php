<header>
	<div class="topbar d-flex align-items-center">
		<nav class="navbar navbar-expand">
			<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
			</div>
			<div class="top-menu ms-auto">
				<ul class="navbar-nav align-items-center">
					<li class="nav-item">
						<a href="https://www.smaalmultazam-mjk.sch.id/" target="_blank" class="btn text-white btn-warning"><i class="bx bx-globe"></i>Buka Website</a>
					</li>
					<li class="nav-item dropdown dropdown-large">
						<div class="dropdown-menu dropdown-menu-end">
							<div class="header-notifications-list">
							</div>
						</div>
					</li>
					{{-- <li class="nav-item dropdown dropdown-large">
						<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" onclick="notifklik('item1')" aria-expanded="false"> <span class="alert-count" name="item1"></span>
							<i class='bx bx-bell'></i>
						</a>
						<div class="dropdown-menu dropdown-menu-end">
							<a href="javascript:;">
								<div class="msg-header">
									<p class="msg-header-title">Notifikasi</p>
									<p class="msg-header-clear ms-auto"></p>
								</div>
							</a>
							<div class="header-message-list">
							</div>
						</div>
					</li> --}}
				</ul>
			</div>
			<div class="user-box dropdown">
				<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
					<img src="{{ Auth::user()->foto ? asset(Auth::user()->foto) : asset('admin/assets/images/avatars/no-avatar.png')}}" class="user-img" alt="user avatar">
					<div class="user-info ps-3">
						<p class="user-name mb-0">{{Auth::user()->first_name}}</p>
						<p class="designattion mb-0">
							{{-- @if (Auth::user()->level_user == 1)
							ADMINISTRATOR
							@elseif (Auth::user()->level_user == 2)
							PRTUGAS SEKOLAH
							@elseif (Auth::user()->level_user == 3)
							GURU PENGAJAR
							@endif --}}
						</p>
					</div>
				</a>
				<ul class="dropdown-menu dropdown-menu-end">
					{{-- <li>
						<a class="dropdown-item" href=""><i class="bx bx-user"></i><span>Profile</span></a>
					</li> --}}
					{{-- <li>
						<div class="dropdown-divider mb-0"></div>
					</li> --}}
					<li><a class="dropdown-item" href="{{route('auth.logout')}}"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>