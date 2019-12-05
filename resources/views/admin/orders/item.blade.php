@extends('admin.app')

@section('content_sub')

<div class="container-fluid" data-id="{{ $order->id }}">
	
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
					<div class="row text-left ml-2">
						<div class="col-6 lead">Sposób dostawy: </div>
						<div class="col-6 lead"><strong>{{ config('site.deliver_name.'.$deliverInfo['type']) }}</strong></div>
					</div>
					
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
				<legend><i class="fas fa-tools"></i> Opcje zamówienia</legend>
				<hr />
				
				<div class="row mb-2">
					<div class="col-12 col-sm-6">
						<button class="btn btn-info" id="btnShowHistory"><i class="fas fa-history"></i> Pokaż historię</button>
					</div>
					<div class="col-12 col-sm-6 text-right">
						<button class="btn btn-info" id="btnChangeStatusModal"><i class="fas fa-edit"></i> Nadaj status</button>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-6">
						<button class="btn btn-info" id="btnChangeDeliverLocModal"><i class="fas fa-search-location"></i> Zmień adres dostawy</button>
					</div>
					<div class="col-12 col-sm-6 text-right">
						<button class="btn btn-info" id="btnChangeCostModal"><i class="fas fa-money-bill-wave-alt"></i> Zmień cenę</button>
					</div>
				</div>
			</div>
			
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
				
			</div>
		</div>
		
	</div>
	
</div>

<!-- Order history -->
<div class="modal fade" id="modalHistory">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-history"></i> Historia zamówienia</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<table class="table table-striped">
					<tbody>
						@foreach($orderHistory as $history)
						<tr>
							<td>{{ $history->created_at }}</td>
							<td>{{ $history->data }}</td>
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

<!-- Order change status -->
<div class="modal fade" id="modalChangeStatus">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-wrench"></i> Zmień status zamówienia</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="alert d-none" id="alert01"></div>
				
				
				<div class="form-group">
					<label for="inp_orderNewStatus">Nazwa statusu:</label>
					<select id="inp_orderNewStatus" class="custom-select">
						@foreach(config('site.order_status') as $key => $value)
						<option value="{{ $key }}" {{ ( $order->status == $key ? 'selected' : '') }}>{{ $value }}</option>
						@endforeach
					</select>
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
				<button type="button" class="btn btn-success" id="btnChangeStatus">Zmień <i class="fas fa-exchange-alt"></i></button>
			</div>
			
		</div>
	</div>
</div>

<!-- Order change deliver location -->
<div class="modal fade" id="modalChangeDeliverLoc">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-wrench"></i> Zmień adres dostawy</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="alert d-none" id="alert02"></div>
				
				<div class="form-group">
					<label for="inp_orderDeliverType">Wybierz sposób dostawy:</label>
					<select id="inp_orderDeliverType" class="form-control">
						<option class="d-none" selected></option>
						@foreach(config('site.deliver_name') as $key => $value)
						<option value="{{ $key }}">{{ $value }}</option>
						@endforeach
					</select>
				</div>
				
				<div class="row">
					<div class="col-12">
						<div id="modalDeliverLoc_Courier" class="d-none">
							
							<hr />
							<h4 class="mb-3"><i class="fas fa-map-marked-alt"></i> Podaj adres dostawy: </h4>
							
							<div class="form-group">
								<label for="inp_data_district"><i class="fas fa-map-marker-alt"></i> Województwo:</label>
								<select class="custom-select" id="inp_data_district">
									<option value="1">Dolnośląskie</option>
									<option value="2">Kujawsko-Pomorskie</option>
									<option value="3">Lubelskie</option>
									<option value="4">Lubuskie</option>
									<option value="5">Łódzkie</option>
									<option value="6">Małopolskie</option>
									<option value="7">Mazowieckie</option>
									<option value="8">Opolskie</option>
									<option value="9">Podkarpackie</option>
									<option value="10">Podlaskie</option>
									<option value="11">Pomorskie</option>
									<option value="12">Śląskie</option>
									<option value="13">Świętokrzyskie</option>
									<option value="14">Warmińsko-Mazurskie</option>
									<option value="15">Wielkopolskie</option>
									<option value="16">Zachodniopomorskie</option>
								</select>
							</div>
							<div class="form-group">
								<label for="inp_data_city"><i class="fas fa-building"></i> Miasto:</label>
								<input type="text" class="form-control" id="inp_data_city" value="">
							</div>
							<div class="form-group">
								<label for="inp_data_zipcode"><i class="fas fa-building"></i> Kod pocztowy:</label>
								<input type="text" class="form-control" id="inp_data_zipcode" value="">
							</div>
							<div class="form-group">
								<label for="inp_data_address"><i class="far fa-id-card"></i> Ulica:</label>
								<input type="text" class="form-control" id="inp_data_address" value="">
							</div>
						</div>
						
						<div id="modalDeliverLoc_Locker" class="d-none">

							<hr />
							<h4 class="mb-3"><i class="fas fa-map-marked-alt"></i> Wybierz paczkomat: </h4>

							<div class="row">
								<div class="col-4 col-sm-3 col-md-2">
									<button class="btn btn-success" id="btnChangeLocker"><i class="fas fa-edit"></i> ZMIEŃ</button>
								</div>
								<div class="col">
									<p>
										Wybrany paczkomat: <strong id="dataLockerName"></strong>
									</p>
									<p>
										Adres paczkomatu: <strong id="dataLockerAddress"></strong>
									</p>
								</div>
							</div>

							<div class="row">
								<div class="col-12">
									<div id="easypack-map"></div>
								</div>
							</div>
	
							<hr />
						</div>
					</div>

				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
				<button type="button" class="btn btn-success" id="btnChangeDeliverLoc">Zmień <i class="fas fa-exchange-alt"></i></button>
			</div>
			
		</div>
	</div>
</div>

<script async src="https://geowidget.easypack24.net/js/sdk-for-javascript.js"></script>
<link rel="stylesheet" href="https://geowidget.easypack24.net/css/easypack.css"/>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.orders.item.js') }}" charset="utf-8"></script>

@endsection