<div class="col-sm-12 col-md-3 col-lg-2 offset-lg-1">
	<nav class="navbar bg-light">
		<ul class="navbar-nav">
			<li class="nav-item">
				<a class="nav-link" href="{{ route('panel_main') }}">
					<i class="fas fa-solar-panel"></i> Panel główny
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="{{ route('panel_orders') }}">
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