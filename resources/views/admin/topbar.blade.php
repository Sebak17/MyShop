<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
	
	<button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
	<i class="fa fa-bars"></i>
	</button>
	
	<ul class="navbar-nav ml-auto">
		
		<li class="nav-item dropdown no-arrow d-sm-none">
			<a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<i class="fas fa-search fa-fw"></i>
			</a>
			
			<li class="nav-item dropdown no-arrow">
				<a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::guard('admin')->user()->login }}</span>
					<i class="fas fa-2x fa-user-astronaut fa-border" style="color: gray;"></i>
				</a>

				<div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
					<a class="dropdown-item" href="{{ route('home') }}">
						<i class="fas fa-home fa-sm fa-fw mr-2 text-gray-400"></i>
						Strona główna
					</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="{{ route('logout') }}">
						<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
						Wyloguj
					</a>
				</div>
			</li>
			
		</ul>
		
	</nav>