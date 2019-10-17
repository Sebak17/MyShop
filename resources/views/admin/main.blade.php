@section('content')

<div class="container-fluid mt-3">
	<div class="row">
		<div class="col-sm-12 col-md-3 col-lg-2 offset-lg-1">
			<nav class="navbar bg-light">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="{{ route('admin_dashboardPage') }}">
							<i class="fas fa-solar-panel"></i> Panel główny
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="{{ route('admin_categoriesPage') }}">
							<i class="fas fa-list fa-1x"></i> Kategorie
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="/admin/offers">
							<i class="fas fa-shopping-basket fa-1x"></i> Oferty
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="/admin/user">
							<i class="fas fa-users fa-1x"></i> Użytkownicy
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="/admin/settings">
							<i class="fas fa-cog fa-1x"></i> Ustawienia strony
						</a>
					</li>
					
					<li class="nav-item">
						<a class="nav-link" href="{{ route('logout') }}">
							<i class="fas fa-sign-out-alt"></i> Wyloguj się
						</a>
					</li>
				</ul>
			</nav>
			
			
		</div>
		
		<div class="col-sm-12 col-md-9 col-lg-8 mt-3">
			@yield('content_sub')
		</div>
		
	</div>
</div>

<script src="{{ asset('js/_admin.panel.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection