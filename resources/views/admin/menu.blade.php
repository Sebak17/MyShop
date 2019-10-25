<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
	
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
		<div class="sidebar-brand-icon rotate-n-15">
			<i class="fas fa-laugh-wink"></i>
		</div>
		<div class="sidebar-brand-text mx-3">Admin Panel</div>
	</a>
	
	<hr class="sidebar-divider my-0">
	
	<li class="nav-item">
		<a class="nav-link" href="{{ route('admin_dashboardPage') }}">
			<i class="fas fa-fw fa-tachometer-alt"></i>
			<span>Panel główny</span></a>
		</li>
		
		<hr class="sidebar-divider">
		
		<li class="nav-item">
			<a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseProducts" aria-expanded="true" aria-controls="collapseProducts">
				<i class="fas fa-fw fa-shopping-bag"></i>
				<span>Produkty</span>
			</a>
			<div id="collapseProducts" class="collapse" aria-labelledby="headingUtilities" data-parent="#accordionSidebar">
				<div class="bg-white py-2 collapse-inner rounded">
					<a class="collapse-item" href="{{ route('admin_productsListPage') }}"><i class="fas fa-fw fa-list"></i> Lista produktów</a>
					<a class="collapse-item" href="{{ route('admin_productsAddPage') }}"><i class="fas fa-fw fa-plus-circle"></i> Dodaj produkt</a>
				</div>
			</div>
		</li>
		
		<li class="nav-item">
			<a class="nav-link" href="{{ route('admin_categoriesPage') }}">
				<i class="fas fa-fw fa-align-left"></i>
				<span>Kategorie</span></a>
			</li>
			
			<li class="nav-item">
				<a class="nav-link" href="tables.html">
					<i class="fas fa-fw fa-cogs"></i>
					<span>Ustawienia</span></a>
				</li>
				
				<!-- Divider -->
				<hr class="sidebar-divider d-none d-md-block">
				
				<!-- Sidebar Toggler (Sidebar) -->
				<div class="text-center d-none d-md-inline">
					<button class="rounded-circle border-0" id="sidebarToggle"></button>
				</div>
				
			</ul>