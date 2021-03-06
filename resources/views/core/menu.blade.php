@section('menu')

<nav class="navbar navbar-expand-md">
	<a href="/" class="navbar-brand nav-logo">
		<img src="{{ asset('img/icons/favicon72.png') }}" height="40px" alt="Logo">
		{{ config('app.name') }}
	</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menuMav">
	<span class="navbar-toggler-icon">
		<i class="fa fa-bars fa-2x"></i>
	</span>
	</button>
	<div class="navbar-collapse collapse justify-content-center" id="menuMav">
	<ul class="nav navbar-nav justify-content-end col-sm-0 col-md-1 col-lg-0"></ul>

	<ul class="nav navbar-nav justify-content-center col-md-5 col-lg-6 col-xl-7 mt-0 mt-sm-3" style="padding-right: 0;">
		<fieldset class="w-100">
			<div class="input-group">
				<input type="text" class="form-control" id="searchBox" placeholder="Wpisz podaną frazę">
				<span class="input-group-btn">
					<button class="btn btn-success" type="button" id="searchBtn"><i class="fas fa-search"></i></button>
				</span>
			</div>
		</fieldset>
	</ul>

	<ul class="nav navbar-nav d-flex align-items-center justify-content-end col-md-6 col-lg-5 col-xl-4">
		
		@auth('web')
		<li class="nav-item h-100 col-12 col-md-2 text-center mt-0 mt-sm-3">
			<a href="{{ route('favoritesPage') }}">
				<i class="fas fa-heart fa-2x"></i>
			</a>
		</li>
		
		<li class="nav-item h-100 col-12 col-md-2 text-center mt-0 mt-sm-3">
			<a href="{{ route('shoppingCartPage') }}">
				<i class="fas fa-shopping-basket fa-2x"></i>
			</a>
		</li>
		
		<li class="nav-item h-100 col-12 col-md-8 dropdown mt-0 mt-sm-3 text-center">
			
			<button class="btn btn-primary dropdown-toggle" type="button" id="navbardrop" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="far fa-user-circle"></i> {{ Auth::user()->email }} &nbsp;<i class="fas fa-arrow-down"></i>
			</button>
			
			<div class="dropdown-menu" style="left: 15%; right: 15%;">
				<a class="dropdown-item" href="{{ route('panel_main') }}">
					<i class="fas fa-solar-panel"></i> Panel
				</a>
				<a class="dropdown-item" href="{{ route('panel_orders') }}">
					<i class="fas fa-credit-card"></i> Moje zamówienia
				</a>
				<a class="dropdown-item" href="{{ route('panel_settings') }}">
					<i class="fas fa-cog fa-spin"></i> Ustawienia
				</a>
				<a class="dropdown-item" href="{{ route('logout') }}">
					<i class="fas fa-sign-out-alt"></i> Wyloguj się
				</a>
			</div>
		</li>
		@endauth
		
		@auth('admin')
		<li class="nav-item mt-2">
			<a class="none" href="{{ route('admin_dashboardPage') }}">
				<button type="button" class="btn btn-danger"><i class="fas fa-user-shield"></i> ADMINISTRATOR</button>
			</a>
		</li>
		
		@endauth
		
		@if(!Auth::guard('web')->check() && !Auth::guard('admin')->check())
		<li class="nav-item mt-2">
			<a class="none" href="{{ route('loginPage') }}">
				<button type="button" class="btn btn-primary"><i class="fas fa-sign-in-alt"></i> Zaloguj</button>
			</a>
			<a class="none" href="{{ route('registerPage') }}">
				<button type="button" class="btn btn-primary"><i class="fas fa-globe-europe"></i> Zarejestruj</button>
			</a>
		</li>
		@endif
		
		
	</ul>
</div>
</nav>

@endsection