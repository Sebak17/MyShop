@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Wyszukaj użytkownika</h1>
	</div>
	
	<div class="row">
		
		<div class="col-12 col-sm-4 offset-sm-2">
			<div class="card card-body">
				
				<div class="alert d-none" id="alert1"></div>
				
				<div class="form-group">
					<label for="inp_email">Podaj email użytkownika: </label>
					<input type="text" id="inp_email" class="form-control">
				</div>

				<div class="form-group text-right">
					<button type="button" id="btnSearch1" class="btn btn-info"><i class="fas fa-search"></i> Szukaj</button>
				</div>
			</div>
		</div>

		<div class="col-12 col-sm-4">
			<div class="card card-body">
				
				<div class="alert d-none" id="alert2"></div>
				
				<div class="form-group">
					<label for="inp_id">Podaj ID użytkownika: </label>
					<input type="text" id="inp_id" class="form-control">
				</div>

				<div class="form-group text-right">
					<button type="button" id="btnSearch2" class="btn btn-info"><i class="fas fa-search"></i> Szukaj</button>
				</div>
			</div>
		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.users.list.js') }}" charset="utf-8"></script>

@endsection