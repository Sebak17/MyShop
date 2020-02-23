@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Towar</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-md-12 order-md-2 col-xl-8 order-xl-1 mb-3 mt-3 mt-xl-0">
			<div class="card card-body">
				
				
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Opis</th>
							<th>Data</th>
						</tr>
						
					</thead>
				<tbody>
					@foreach($item->history as $his)
					<tr>
						<td></td>
						<td>{{ $his->data }}</td>
						<td>{{ $his->created_at }}</td>
					</tr>
					@endforeach
				</tbody>
			</table>
			
		</div>
	</div>
	
	<div class="col-12 col-md-12 order-md-1 col-xl-4 order-xl-2">
		<div class="card card-body">
			<legend><i class="fas fa-info"></i> Informacje o towarze</legend>
			<hr />

			<div class="row text-left ml-2">
				<div class="col-6">Produkt: </div>
				<div class="col-6">
					<a href="{{ route('admin_productsEditPage', $item->product->id) }}">
						<strong>{{ $item->product->title }}</strong>
					</a>
				</div>
			</div>


			<hr />
			
			<div class="row text-left ml-2">
				<div class="col-6">Kod: </div>
				<div class="col-6"><strong>{{ $item->code }}</strong></div>
			</div>

			<div class="row text-left ml-2">
				<div class="col-6">Status: </div>
				<div class="col-6"><strong>{{ config("site.warehouse_item_status." . $item->status) }}</strong></div>
			</div>

			<hr />

			<div class="row text-left ml-2">
				<div class="col-6">Data stworzenia: </div>
				<div class="col-6"><strong>{{ $item->created_at }}</strong></div>
			</div>

			
		</div>
	</div>
	
</div>

</div>


<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
@endsection