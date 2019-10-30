@extends('panel.app')
@extends('core.menu')

@section('content')

<div class="page-header">
	<h2><i class="fas fa-cog"></i> Ustawienia</h2>
</div>

<hr>

<div class="row">
	<div class="col-10 offset-1 col-sm-10 offset-sm-1 col-md-6 offset-md-0 col-lg-6 col-xl-4 mt-3">
		
		<div class="card card-body">
			<h4 class="card-title">
			<i class="fas fa-user-edit"></i> Zmień dane osobowe
			</h4>
			<hr />
			
			<div class="alert text-center d-none" id="alert01"></div>
			
			<fieldset class="mt-3">
				<div class="form-group">
					<label for="inp_data_fname"><i class="fas fa-id-card"></i> Imię:</label>
					<input type="text" class="form-control" id="inp_data_fname" value="{{ Auth::user()->personal->firstname }}">
				</div>
				<div class="form-group">
					<label for="inp_data_sname"><i class="far fa-id-card"></i> Nazwisko:</label>
					<input type="text" class="form-control" id="inp_data_sname" value="{{ Auth::user()->personal->surname }}">
				</div>
				<div class="form-group">
					<label for="inp_data_phone"><i class="fas fa-mobile-alt"></i> Numer telefonu:</label>
					<input type="number" class="form-control" id="inp_data_phone" maxlength="9" value="{{ Auth::user()->personal->phoneNumber }}">
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-primary float-right" id="btn_changeDataPersonal">
					<i class="fa fa-wrench"></i> Zmień dane
					</button>
				</div>
			</fieldset>
		</div>
	</div>
	
	<div class="col-10 offset-1 col-sm-10 offset-sm-1 col-md-6 offset-md-0 col-lg-6 col-xl-4 mt-3">
		
		<div class="card card-body">
			<h4 class="card-title">
			<i class="fas fa-address-card"></i> Zmień adres
			</h4>
			<hr />
			
			<div class="alert text-center d-none" id="alert02"></div>
			
			<fieldset class="mt-3">
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
					<input type="text" class="form-control" id="inp_data_city" value="{{ Auth::user()->location->city }}">
				</div>
				<div class="form-group">
					<label for="inp_data_zipcode"><i class="fas fa-building"></i> Kod pocztowy:</label>
					<input type="text" class="form-control" id="inp_data_zipcode" value="{{ Auth::user()->location->zipcode }}">
				</div>
				<div class="form-group">
					<label for="inp_data_address"><i class="far fa-id-card"></i> Ulica:</label>
					<input type="text" class="form-control" id="inp_data_address" value="{{ Auth::user()->location->address }}">
				</div>
				
				
				<div class="form-group">
					<button type="button" class="btn btn-primary float-right" id="btn_changeDataLocation">
					<i class="fa fa-wrench"></i> Zmień dane
					</button>
				</div>
			</fieldset>
		</div>
	</div>
	
	<div class="col-10 offset-1 col-sm-10 offset-sm-1 col-md-6 offset-md-0 col-lg-6 col-xl-4 mt-3">
		<div class="card card-body ">
			<h4 class="card-title">
			<i class="fa fa-key"></i> Zmień hasło
			</h4>
			<hr />
			
			<div class="alert text-center d-none" id="alert03"></div>
			
			<fieldset>
				<div class="form-group">
					<input type="password" class="form-control" id="inp_pass0" placeholder="Podaj stare hasło">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" id="inp_pass1" placeholder="Podaj nowe hasło">
				</div>
				<div class="form-group">
					<input type="password" class="form-control" id="inp_pass2" placeholder="Powtórz nowe hasło">
				</div>
				<div class="form-group">
					<button type="button" class="btn btn-primary float-right" id="btn_changePass">
					<i class="fa fa-wrench"></i> Zmień
					</button>
				</div>
			</fieldset>
		</div>
		
	</div>
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_panel.settings.js') }}" charset="utf-8"></script>

<script type="text/javascript">
	var _userDisctrict = {{ Auth::user()->location->district }};
</script>

@endsection