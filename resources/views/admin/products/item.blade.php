@extends('admin.app')

@section('content_sub')

<div class="container-fluid" data-id="{{ $product->id }}">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Produkt nr. {{ $product->id }}</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-md-7">
			<div class="card card-body mb-3">
				<legend><i class="fas fa-chart-bar"></i> Statystyki zakupów</legend>
				<hr />
				
				<div class="row">
					<div class="col-6">
						<div class="row text-left ml-2">
							<div class="col-6">Ilość zakupionych: </div>
							<div class="col-6"><strong>{{ $product->getBoughtItemsTotal() }}</strong></div>
						</div>
					</div>
					<div class="col-6">
						<div class="row text-left ml-2">
							<div class="col-6">??: </div>
							<div class="col-6"><strong>???</strong></div>
						</div>
					</div>

					<div class="col-12 text-right mt-2">
						<button class="btn btn-info" id="btnOrdersListModal"><i class="fas fa-list-ol"></i> Lista ofert</button>
					</div>



				</div>
				
				
			</div>
		</div>
		
		<div class="col-12 col-md-5">
			<div class="card card-body mb-3">
				<legend><i class="fas fa-info"></i> Informacje o produkcie</legend>
				
				<hr />
				
				<div>
					<img class="mx-auto d-block" style="height: 200px;" src="/storage/products_images/{{ (count($product->images) > 0 ? $product->images[0]->name : null) }}" alt="">
				</div>
				
				<hr />
				
				<div class="row text-left ml-2">
					<div class="col-4">Nazwa: </div>
					<div class="col-8"><strong>{{ $product->title }}</strong></div>
				</div>
				
				<div class="row text-left ml-2">
					<div class="col-4">Kategoria: </div>
					<div class="col-8"><strong>{{ $product->getCategory()->name }}</strong></div>
				</div>
				
				<hr />

				<div class="row text-left ml-2">
					<div class="col-4">Status: </div>
					<div class="col-8"><strong>{{ $product->status }}</strong></div>
				</div>

				<hr />
				
				<div class="row text-left ml-2">
					<div class="col-4">Cena aktualna: </div>
					<div class="col-8"><strong>{{ $product->price }}</strong></div>
				</div>
				
				<div class="row text-left ml-2">
					<div class="col-4">Cena promocyjna: </div>
					<div class="col-8"><strong>???</strong></div>
				</div>
				
				<hr />
				
				<div class="row text-justify ml-2">
					<h5>Opis:</h5>
					<div class="">{{ $product->description }}</div>
				</div>
				
				<hr />
				
			</div>
			
			<div class="card card-body mb-3">
				<legend><i class="fas fa-tasks"></i> Status produktu</legend>
				<hr />
				
				<div class="row text-left ml-2">
					<div class="col-6">Ilość towaru na magazynie: </div>
					<div class="col-6"><strong>{{ count($product->items) }}</strong></div>
				</div>
	
				<div class="row text-left ml-2">
					<div class="col-6">Ilość dostępnego towaru: </div>
					<div class="col-6"><strong>{{ count($product->items->where('status', 'AVAILABLE')) }}</strong></div>
				</div>

				<div class="row">
					<div class="col text-right">
						<a href="{{ route('admin_warehouseProductPage', $product->id) }}">
							<button class="btn btn-info">Przejdź do magazynu <i class="fas fa-arrow-right"></i></button>
						</a>
					</div>
				</div>
				
			</div>
			
			<div class="card card-body mb-3">
				<legend><i class="fas fa-clipboard"></i> Parametry produktu</legend>
				<hr />
				
				<table class="table table-striped">
					<tbody >
						@if($product->params != null)
						@foreach(json_decode($product->params, true) as $param)
						<tr>
							<td>{{ $param['name'] }}</td>
							<td>{{ $param['value'] }}</td>
						</tr>
						@endforeach
						@endif
						
					</tbody>
				</table>
				
			</div>
			
			<div class="card card-body mb-3">
				<legend><i class="fas fa-image"></i> Zdjęcia podglądowe</legend>
				
				<div class="row">
					@foreach($product->images as $img)
					<div class="col-12 col-md-6 col-lg-4 p-5 border mb-2">
						<img class="img-fluid" src="/storage/products_images/{{ $img->name }}">
					</div>
					@endforeach
				</div>
			</div>
		</div>
		
	</div>
	
</div>

<!-- Orders list -->
<div class="modal fade" id="modalOrdersList">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-list"></i> Lista zamówień</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<table class="table table-striped">
					<thead>
						<tr>
							<th></th>
							<th>Koszt</th>
							<th>Ilość produktów</th>
						</tr>
					</thead>
					<tbody>
						@foreach($product->getOrders() as $order)
							<tr>
								<td>
									<a href="{{ route('admin_orderPageID', $order->id) }}">
										<h5><span class="badge badge-primary">#{{ $order->id }}</span></h5>
									</a>
								</td>
								<td class="text-center">{{ $order->cost }} {{ config('site.currency') }}</td>
								<td class="text-center">{{ count($order->products) }}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
			</div>
			
		</div>
	</div>
</div>



<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script>
	$(document).ready(function() {
		bindButtons();
	});

	function bindButtons() {
		$("#btnOrdersListModal").click(function(){
			$("#modalOrdersList").modal('show');
		});
	}
</script>


@endsection