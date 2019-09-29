@section('content')

<div class="container-fluid mt-3">
	<div class="row">
		<div class="col-sm-12 col-md-3 col-lg-2 offset-lg-1">
			<nav class="navbar bg-light">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="{{ route('panel_main') }}">
							<i class="fas fa-solar-panel"></i> Panel główny
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="/panel/mojezakupy">
							<i class="fas fa-credit-card fa-1x"></i> Moje zamówienia
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('panel_settings') }}">
							<i class="fas fa-cog fa-1x"></i> Ustawienia
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

<script src="{{ asset('js/_panel.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection