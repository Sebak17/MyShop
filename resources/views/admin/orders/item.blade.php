@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Zamowienie nr. {{ $order->id }}</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-md-8">
			<div class="card card-body mb-3">
				<legend><i class="fas fa-shopping-basket"></i> Uwagi do zamówienia</legend>
				<hr />
				
				<div class="row">
					<div class="col-12" style="font-size: 70%;">
						<p>
							{!! nl2br($order->note) !!}
						</p>
					</div>
				</div>
			</div>
			
			<div class="card card-body mb-3">
				<legend><i class="fas fa-search-location"></i> Dostawa</legend>
				<hr />
				
				<div>
					@if($deliverInfo['type'] == 'COURIER')
					<div class="row text-left ml-2">
						<div class="col-6 lead">Ulica: </div>
						<div class="col-6 lead"><strong>{{ $deliverInfo['address'] }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6 lead">Miejscowość: </div>
						<div class="col-6 lead"><strong>{{ $deliverInfo['city'] }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6 lead">Kod pocztowy: </div>
						<div class="col-6 lead"><strong>{{ $deliverInfo['zipcode'] }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6 lead">Województwo: </div>
						<div class="col-6 lead"><strong>{{ config('site.district_name.'.$deliverInfo['district']) }}</strong></div>
					</div>
					@elseif($deliverInfo['type'] == 'INPOST_LOCKER')
					<div class="row text-left ml-2">
						<div class="col-6 lead">Nazwa paczkomatu: </div>
						<div class="col-6 lead"><strong id="lockerCode">{{ $deliverInfo['lockerName'] }}</strong></div>
					</div>
					<div id="lockerInfo">
					</div>
					@endif
					
					@if($deliverInfo['type'] == 'INPOST_LOCKER')
					<div class="form-group text-right">
						<button class="btn btn-info" id="btnShowLocLocker">Pokaż na mapie <i class="fas fa-map-marker-alt"></i></button>
					</div>
					@endif
				</div>
				
			</div>
			
			<div class="card card-body">
				
				<legend><i class="fas fa-shopping-basket"></i> Lista przedmiotów</legend>
				
				<table class="table table-striped">
					<thead>
					</thead>
					<tbody id="productsList" style="font-size: 80%;">
						@foreach ($productsData as $product)
						<tr>
							<td>
								<a href="/produkt?id={{ $product['id'] }}"><h6>{{ $product['name'] }}</h6></a>
							</td>
							<td>{{ $product['amount'] }} szt.</td>
							<td>{{ $product['fullPrice'] }} {{ config('site.currency') }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
			
			
		</div>
		
		<div class="col-12 col-md-4">
			<div class="card card-body mb-3">
				<legend><i class="fas fa-stream"></i> Status zamówienia</legend>
				
				<hr />
				
				<ul class="timeline">
					<li class="{{ ( in_array($order->status, ['UNPAID','PROCESSING','PAID','REALIZE','SENT','RECEIVE']) ? 'ready' : '') }} {{ ( $order->status == 'CREATED' ? 'active' : '') }}">
						<a>Stworzono zamówienie</a>
					</li>
					<li class="{{ ( in_array($order->status, ['PAID','REALIZE','SENT','RECEIVE']) ? 'ready' : '') }} {{ ( in_array($order->status, ['UNPAID', 'PROCESSING']) ? 'active' : '') }}">
						<a>Płatność internetowa</a>
					</li>
					<li class="{{ ( in_array($order->status, ['SENT','RECEIVE']) ? 'ready' : '') }} {{ ( $order->status == 'REALIZE' ? 'active' : '') }}">
						<a>Realizowanie zamówienia</a>
					</li>
					<li class="{{ ( in_array($order->status, ['RECEIVE']) ? 'ready' : '') }} {{ ( $order->status == 'SENT' ? 'active' : '') }}">
						<a>Wysyłka paczki</a>
					</li>
					<li class="{{ ( $order->status == 'RECEIVE' ? 'active' : '') }}">
						<a>Przesyłka u klienta</a>
					</li>
				</ul>
				
				
				<hr />
				
				<button class="btn btn-info"><i class="fas fa-edit"></i> Nadaj status</button>
				
			</div>
		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>

@endsection