@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Dodaj produkt</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-md-8">
			<div class="card card-body mb-3">
				<legend><i class="fas fa-info"></i> Informacje o produkcie</legend>
				<hr />
				
				
				<div class="form-group">
					<label for="inp_name">Nazwę produktu:</label>
					<input id="inp_name" type="text" class="form-control">
				</div>
				
				<div class="form-group">
					<label for="inp_price">Cena za produkt:</label>
					<div class="form-group">
						<div class="input-group mb-3">
							<input id="inp_price" type="number" class="form-control">
							<div class="input-group-append">
								<span class="input-group-text">{{ config('site.currency') }}</span>
							</div>
						</div>
					</div>
				</div>
				
				<div class="form-group">
					<label for="inp_description">Opis produktu:</label>
					<textarea id="inp_description" class="form-control" rows="6" ></textarea>
				</div>
				
				<div class="form-group">
					<label for="inp_category">Wybierz kategorie:</label>
					<select id="inp_category" class="custom-select">
					</select>
				</div>
				
			</div>

			<div class="card card-body">
				<div class="row">
					<div class="col-6">
						<legend><i class="fas fa-table"></i> Parametry produktu</legend>
					</div>
					<div class="col-6 text-right">
						<button class="btn btn-primary" id="btnParamAddModal">Dodaj <i class="fas fa-plus"></i></button>
					</div>
				</div>
				<hr />
				
				<table class="table table-striped">
					<tbody id="productParams">
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="col-12 col-md-4">
			<div class="card card-body mb-3">
				<legend><i class="fas fa-eye"></i> Opcje produktu</legend>
				<hr />
				
				<div class="alert d-none" id="alert"></div>
				
				<div class="row">
					<div class="col">
						<div class="form-group">
							<button type="button" id="btn_productAdd" class="btn btn-primary btn-block">Dodaj <i class="fas fa-plus-circle"></i></button>
						</div>
					</div>
				</div>
				
			</div>
			
			<div class="card card-body mb-3">
				<legend><i class="fas fa-image"></i> Zdjęcia podglądowe</legend>
				
				<input type="file" class="d-none" id="offerUpload" accept="image/x-png,image/jpeg" multiple>
				
				<div class="row mb-2">
					<div class="col">
						<button class="btn btn-success" id="btnImageInput"><i class="fas fa-plus"></i> Dodaj zdjęcie</button>
					</div>
				</div>
				
				<table class="table table-hover">
					<tbody id="tableImageList">
					</tbody>
				</table>
			</div>
		</div>
		
	</div>
	
</div>

<!-- Product param add modal -->
<div class="modal fade" id="modalParamAdd">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-plus-square"></i> Dodawanie parametru do produktu</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="alert d-none" id="alert02"></div>
				
				<div class="form-group">
					<label for="fmParamAddName">Nazwę parametru:</label>
					<input id="fmParamAddName" type="text" class="form-control">
				</div>
				
				<div class="form-group">
					<label for="fmParamAddValue">Wartość:</label>
					<input id="fmParamAddValue" type="text" class="form-control">
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
				<button type="button" class="btn btn-success" id="btnParamAdd">Dodaj <i class="fas fa-plus-circle"></i></button>
			</div>
			
		</div>
	</div>
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.products.add.js') }}" charset="utf-8"></script>

@endsection