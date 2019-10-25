@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Dodaj ofertę</h1>
	</div>
	
	<div class="row">
		<div class="col-12">
			<div class="card card-body mb-3">
				
				<div class="alert d-none" id="alert"></div>
				
				<div class="form-group">
					<label for="inp_name">Nazwę oferty:</label>
					<input id="inp_name" type="text" class="form-control">
				</div>
				
				<div class="form-group">
					<label for="inp_description">Opis oferty:</label>
					<textarea id="inp_description" class="form-control" rows="6" ></textarea>
				</div>
				
				<div class="form-group">
					<label for="inp_category">Wybierz kategorie:</label>
					<input type="number"  id="inp_category" class="form-control">
				</div>
				
				<label for="offer" class="mb-3"><i class="fas"></i> Zdjęcia oferty</label>
				
				<input type="file" class="d-none" id="offerUpload" accept="image/x-png,image/jpeg" multiple>
				
				<div class="row">

					<div class="offer-photo new" id="offerImageInput">
						Dodaj zdjęcie
					</div>

					<div id="imagesList" class="row ml-2">
						
					</div>
				</div>
				
				
				<div class="form-group">
					<button type="button" id="btnAdd" class="btn btn-primary float-right">Dodaj <i class="fas fa-plus-circle"></i></button>
				</div>
				
			</div>
		</div>
		
		
	</div>
	
</div>

<link rel="stylesheet" href="{{ asset('css/offer-images.css') }}">

<script src="{{ asset('js/sortable.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.products.add.js') }}" charset="utf-8"></script>

@endsection