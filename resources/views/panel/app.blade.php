<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>{{ config('app.name') }}</title>
		
		<meta name="author" content="Sebastian Krysa">
		<meta name="description" content="Zakupy online">
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<link href="https://fonts.googleapis.com/css?family=Righteous&display=swap" rel="stylesheet">
		
		<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
		<link href="{{ asset('css/bootstrap-4.3.1.min.css') }}" rel="stylesheet">
		<link href="{{ asset('css/_main.css') }}" rel="stylesheet">
		
		<link href="{{ asset('img/icons/favicon.ico') }}" rel="shortcut icon" type="image/x-icon"  />
		
		<script src="{{ asset('js/jquery-3.4.1.min.js') }}" charset="utf-8"></script>
		<script src="{{ asset('js/bootstrap-4.3.1.min.js') }}" charset="utf-8"></script>
		
		<script src="{{ asset('js/_lang.js') }}" charset="utf-8"></script>
		<script src="{{ asset('js/_utils.js') }}" charset="utf-8"></script>
		
	</head>
	<body>

		@yield('menu')
		
		<div class="container-fluid mt-3">
			<div class="row">
				
				@include('panel/menu')
				
				<div class="col-sm-12 col-md-9 col-lg-8 mt-3">
					@yield('content')
				</div>
				
			</div>
		</div>

		<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>
		
		
		<nav class="navbar navbar-expand-sm justify-content-center mt-5 bg-dark navbar-dark" id="footer">
			<ul class="navbar-nav">
				<li class="nav-item">
					<p class="nav-link">&copy; MójSklep S.A 2019</p>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://sebak.ovh/">Autor: Sebastian Krysa</a>
				</li>
			</ul>
		</nav>
		
	</body>
	
</html>