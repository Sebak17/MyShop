@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Lista towarów</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-md-12 order-md-2 col-xl-8 order-xl-1 mb-3 mt-3 mt-xl-0">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><i class="fas fa-info"></i> Znaleziono <strong>{{ count($items) }}</strong> towarów</li>
			</ol>
			<div class="card card-body">
				
				
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Produkt</th>
							<th>Ilość</th>
							<th>Opcje</th>
						</tr>
						
					</thead>
					<tbody id="warehouseItemsList">
					</tbody>
				</table>
				
			</div>
		</div>
		
		<div class="col-12 col-md-12 order-md-1 col-xl-4 order-xl-2">
			<div class="card card-body">
				<legend><i class="fas fa-info"></i> Informacje o produkcie</legend>
				<hr />

				<div>
					<img class="mx-auto d-block" style="height: 150px;" src="/storage/products_images/{{ (count($product->images) > 0 ? $product->images[0]->name : null) }}" alt="">
				</div>
				
				<h4>{{ $product->title }}</h4>

				<hr />
				<h3 class="text-right">{{ number_format((float)$product->price, 2, '.', '') . ' ' . config('site.currency') }}</h3>
				<hr />

			</div>
		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>

@endsection