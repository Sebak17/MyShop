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
						<div class="col-6">Adres: </div>
						<div class="col-6"><strong>{{ $user->location->address }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Miasto: </div>
						<div class="col-6"><strong>{{ $user->location->city }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Kod pocztowy: </div>
						<div class="col-6"><strong>{{ $user->location->zipcode }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Województwo: </div>
						<div class="col-6"><strong>{{ config('site.district_name.' . $user->location->district) }}</strong></div>
					</div>
					
					<hr />
					
					<div class="row text-left ml-2">
						<div class="col-6">Aktywne: </div>
						<div class="col-6"><strong>{{ ($user->active ? "Tak" : "Nie") }}</strong></div>
					</div>
					
					<div class="row text-left ml-2">
						<div class="col-6">Blokada konta: </div>
						<div class="col-6">
							@if($user->ban == null)
							<strong>Brak</strong>
							@else
							<strong>{{ $user->ban->created_at }}</strong>
							<strong>{{ $user->ban->reason }}</strong>
							@endif
						</div>
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
						<button class="btn btn-info" id="btnShowHistory"><i class="fas fa-history"></i> Pokaż historię</button>
					</div>
					
					<div class="col-12 col-sm-6 text-right">
						@if($user->ban == null)
						<button class="btn btn-info" id="btnBanModal"><i class="fas fa-gavel"></i> Zablokuj konto</button>
						@else
						<button class="btn btn-info" id="btnUnban"><i class="fas fa-gavel"></i> Odblokuj konto</button>
						@endif
						
						<button class="btn btn-info mt-2" id="btnChangePersonalModal"><i class="fas fa-edit"></i> Zmień dane osobowe</button>
						
						<button class="btn btn-info mt-2" id="btnChangeLocationModal"><i class="fas fa-edit"></i> Zmień adres</button>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
</div>

<!-- User history modal -->
<div class="modal fade" id="modalHistory">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-history"></i> Historia użytkownika</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="row">
					<div class="offset-8 col-4">
						<div class="form-group">
							<select id="modalHistoryType" class="form-control">
								@foreach(config('site.user_history') as $key => $value)
								<option value="{{ $key }}">{{ $value }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Akcja</th>
							<th>Rodzaj</th>
							<th>Adres</th>
							<th>Data</th>
						</tr>
					</thead>
					<tbody id="modalHistoryBox">
					</tbody>
				</table>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
			</div>
			
		</div>
	</div>
</div>

<!-- User ban modal -->
<div class="modal fade" id="modalBan">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-gavel"></i> Blokowanie użytkownika</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="alert d-none" id="alert01"></div>
				
				<div class="form-group">
					<label for="inp_BanInfo"><i class="fas fa-clipboard"></i> Podaj powód blokady:</label>
					<textarea id="inp_BanInfo" rows="4" class="form-control"></textarea>
				</div>
				
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
				<button type="button" class="btn btn-success" id="btnBanUser">Zablokuj <i class="fas fa-ban"></i></button>
			</div>
		</div>
	</div>
</div>

<!-- User change personal -->
<div class="modal fade" id="modalChangePersonal">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-wrench"></i> Zmień dane osobowe</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="alert d-none" id="alert02"></div>
				
				
				<div class="form-group">
					<label for="inp_data_fname"><i class="fas fa-id-card"></i> Imię:</label>
					<input type="text" class="form-control" id="inp_data_fname" value="{{ $user->personal->firstname }}">
				</div>
				<div class="form-group">
					<label for="inp_data_sname"><i class="far fa-id-card"></i> Nazwisko:</label>
					<input type="text" class="form-control" id="inp_data_sname" value="{{ $user->personal->surname }}">
				</div>
				<div class="form-group">
					<label for="inp_data_phone"><i class="fas fa-mobile-alt"></i> Numer telefonu:</label>
					<input type="number" class="form-control" id="inp_data_phone" maxlength="9" value="{{ $user->personal->phoneNumber }}">
				</div>

			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
				<button type="button" class="btn btn-success" id="btnChangePersonal">Zmień <i class="fas fa-exchange-alt"></i></button>
			</div>
			
		</div>
	</div>
</div>

<!-- User change location -->
<div class="modal fade" id="modalChangeLocation">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-wrench"></i> Zmień adres</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="alert d-none" id="alert03"></div>
				
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
					<input type="text" class="form-control" id="inp_data_city" value="{{ $user->location->city }}">
				</div>
				<div class="form-group">
					<label for="inp_data_zipcode"><i class="fas fa-building"></i> Kod pocztowy:</label>
					<input type="text" class="form-control" id="inp_data_zipcode" value="{{ $user->location->zipcode }}">
				</div>
				<div class="form-group">
					<label for="inp_data_address"><i class="far fa-id-card"></i> Ulica:</label>
					<input type="text" class="form-control" id="inp_data_address" value="{{ $user->location->address }}">
				</div>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
				<button type="button" class="btn btn-success" id="btnChangeLocation">Zmień <i class="fas fa-exchange-alt"></i></button>
			</div>
			
		</div>
	</div>
</div>


<script>
	var _userDisctrict = {{ $user->location->district }};
	var isBanned = {{ ( $user->ban != null ? "true" : "false" ) }};
	var historyData = JSON.parse(String.raw`{!! $historyData !!}`);
</script>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.users.item.js') }}" charset="utf-8"></script>

@endsection