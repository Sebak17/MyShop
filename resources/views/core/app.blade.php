<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>{{ config('app.name') }} {{ isset($titleInfo) ? " - " . $titleInfo : "" }}</title>
		
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

		@yield('content')

		@include('core/footer')
		
	</body>
	
</html>