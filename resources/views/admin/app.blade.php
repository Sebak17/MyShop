<!DOCTYPE html>
<html>
	<head>
		
		<meta charset="utf-8">
		<title>{{ config('app.name') }} - Admin</title>
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<link href="https://fonts.googleapis.com/css?family=Righteous&display=swap" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
		
		<link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
		<link href="{{ asset('vendor/sb-admin-2/main.css') }}" rel="stylesheet">
		
		<link href="{{ asset('img/icons/favicon.ico') }}" rel="shortcut icon" type="image/x-icon"  />
		
		<script src="{{ asset('js/jquery-3.4.1.min.js') }}" charset="utf-8"></script>
		<script src="{{ asset('js/bootstrap-4.3.1.min.js') }}" charset="utf-8"></script>
		
	</head>
	
	<body id="page-top">
		
		<div id="wrapper">
			
			@include('admin/menu')

			<div id="content-wrapper" class="d-flex flex-column">
				
				<div id="content">
					
					@include('admin/topbar')
					
					@yield('content_sub')
					
				</div>
				
				<footer class="sticky-footer bg-white">
					<div class="container my-auto">
						<div class="copyright text-center my-auto">
							<span>&copy; MójSklep S.A 2019</span>
						</div>
					</div>
				</footer>
				
			</div>
			
		</div>


		<script src="{{ asset('vendor/sb-admin-2/main.min.js') }}" charset="utf-8"></script>

		<script src="{{ asset('js/Chart.min.js') }}"></script>
		<script src="{{ asset('vendor/sb-admin-2/chart-area-demo.js') }}"></script>
		<script src="{{ asset('vendor/sb-admin-2/chart-pie-demo.js') }}"></script>
		
	</body>
	
</html>

