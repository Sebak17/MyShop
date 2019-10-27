@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Lista ofert</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-sm-8">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><i class="fas fa-info"></i> Znaleziono <strong id="productsAmount">?</strong> produkty/ów</li>
			</ol>
			<div class="card card-body">
				
				
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Nazwa produktu</th>
							<th>Cena</th>
							<th>Opcje</th>
						</tr>
						
					</thead>
					<tbody id="productsList">
					</tbody>
				</table>
				
			</div>
		</div>
		
		<div class="col-12 col-sm-4">
			<div class="card card-body">
				<legend><i class="fas fa-search"></i> Szukaj oferty</legend>
				<hr />
				
				<div class="alert d-none" id="alert"></div>
				
				<div class="form-group">
					<label for="inp_name">Podaj nazwę produktu: </label>
					<input type="text" id="inp_name" class="form-control">
				</div>
				
				<div class="form-group">
					<h5><i class="fas fa-money"></i> Cena</h5>
					<div class="row">
						<div class="col-5">
							<input type="number" class="form-control" placeholder="od" id="inp_price1">
						</div>
						<div class="col-2 mt-2 align-middle text-center">
							<i class="fas fa-minus fa-1x"></i>
						</div>
						<div class="col-5">
							<input type="number" class="form-control" placeholder="do" id="inp_price2">
						</div>
					</div>
				</div>
				
				
				<div class="form-group text-right">
					<button type="button" id="btnSearch" class="btn btn-info"><i class="fas fa-search-plus"></i> Szukaj</button>
				</div>
			</div>
		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.products.list.js') }}" charset="utf-8"></script>

@endsection