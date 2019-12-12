@extends('admin.app')

@section('content_sub')

<div class="container-fluid" data-id="{{ $user->id }}">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Użytkownik nr. {{ $user->id }}</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-md-8">
			<div class="card card-body mb-3">
				<legend><i class="fas fa-info"></i> Informacje ogólne</legend>
				<hr />
				
				<div>
					<div class="row text-left ml-2">
						<div class="col-6">Email: </div>
						<div class="col-6"><strong>{{ $user->email }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Imie: </div>
						<div class="col-6"><strong>{{ $user->personal->firstname }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Nazwisko: </div>
						<div class="col-6"><strong>{{ $user->personal->surname }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Numer telefonu: </div>
						<div class="col-6"><strong>{{ $user->personal->phoneNumber }}</strong></div>
					</div>
					
					
					<hr />
					
					<div class="row text-left ml-2">
						<div class="col-6">Email: </div>
						<div class="col-6"><strong>{{ $user->location->address }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Imie: </div>
						<div class="col-6"><strong>{{ $user->location->city }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Nazwisko: </div>
						<div class="col-6"><strong>{{ $user->location->zipcode }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Numer telefonu: </div>
						<div class="col-6"><strong>{{ config('site.district_name.' . $user->location->district) }}</strong></div>
					</div>
					
					<hr />
					
					<div class="row text-left ml-2">
						<div class="col-6">Aktywne: </div>
						<div class="col-6"><strong>{{ ($user->active ? "Tak" : "Nie") }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Blokada konta: </div>
						<div class="col-6"><strong>{{ $user->banned_id }}</strong></div>
					</div>
					
				</div>
			</div>
			
			<div class="card card-body mb-3">
				<table class="table table-stripped">
					<thead>
						<tr>
							<th><i class="fas fa-list-ol"></i> Numer zamówienia</th>
							<th><i class="fas fa-calendar-alt"></i> Data złożenia</th>
							<th class="d-none d-sm-table-cell"><i class="fas fa-shopping-cart"></i> Ilość produktów</th>
							<th><i class="fas fa-eye"></i> Status</th>
							<th><i class="fas fa-dollar-sign"></i> Koszt</th>
						</tr>
					</thead>
					<tbody>
						@foreach($user->orders as $order)
						<tr>
							<td class="text-center">
								<a href="{{ route('admin_orderPageID', $order->id) }}">
									<h4>
									<span class="badge badge-primary">{{ $order->id }}</span>
									</h4>
								</a>
							</td>
							<td>{{ $order->created_at }}</td>
							<td class="d-none d-sm-table-cell">{{ count($order->products) }}</td>
							<td><strong>{{ config('site.order_status.' . $order->status) }}</strong></td>
							<td>{{ $order->cost . ' ' . config('site.currency') }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				
			</div>
			
			
		</div>
		
		<div class="col-12 col-md-4">
			<div class="card card-body mb-3">
				<legend><i class="fas fa-tools"></i> Opcje użytkownika</legend>
				<hr />
				
				<div class="row mb-2">
					<div class="col-12 col-sm-6">
						<button class="btn btn-info" id="btnShowHistoryAuth"><i class="fas fa-history"></i> Pokaż historię logowań</button>
					</div>
					
					<div class="col-12 col-sm-6 text-right">
						@if($user->banned_id == null)
						<button class="btn btn-info" id="btnBanModal"><i class="fas fa-gavel"></i> Zablokuj konto</button>
						@else
						<button class="btn btn-info" id="btnUnbanModal"><i class="fas fa-gavel"></i> Odblokuj konto</button>
						@endif

						<button class="btn btn-info mt-2" id="btnChangePersonalModal"><i class="fas fa-edit"></i> Zmień dane osobowe</button>
						
						<button class="btn btn-info mt-2" id="btnChangeLocationModal"><i class="fas fa-edit"></i> Zmień adres</button>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.orders.item.js') }}" charset="utf-8"></script>

@endsection