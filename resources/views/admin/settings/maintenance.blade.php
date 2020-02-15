@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Ustawienia</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-sm-6 offset-sm-3">
			<div class="card card-body">
						<legend><i class="fas fa-tools"></i> Przerwa techniczna</legend>
						<hr />

						<div class="alert text-center d-none" id="alert10"></div>

						<div class="custom-control custom-switch">
							<input type="checkbox" class="custom-control-input" id="inp_maintenanceMode" {{ ($data['enabled'] ? 'checked' : '') }}>
							<label class="custom-control-label" for="inp_maintenanceMode">Włącz kontrolowany ruch</label>

						</div>

						<hr />

						<div class="form-group mb-0">
							<input type="text" id="inp_maintenanceMsg" class="form-control" placeholder="Podaj wiadomość" value="{{ (isset($data['msg']) ? $data['msg'] : '') }}">
						</div>

						<hr />

						<div class="alert text-center d-none" id="alert11"></div>

						<ul class="list-group" id="maintenanceListIP">
							@foreach($ips as $v)
							<li class="list-group-item">{{ $v }}<button type="button" class="btn btn-danger btn-sm float-right" data-addressIP="{{ $v }}" {{ ($v == '127.0.0.1' ? 'disabled' : '') }}><i class="fas fa-times"></i></li>
							@endforeach
						</ul>

						<hr />

						<div class="alert text-center d-none" id="alert01"></div>

						<fieldset>
							<div class="form-group">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="far fa-address-book"></i></span>
									</div>
									<input id="inp_maintenanceAddIP" type="text" placeholder="Podaj adres IP" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<button type="button" id="btnMaintenanceAddIP" class="btn btn-primary btn-sm float-right">Dodaj <i class="fas fa-plus"></i></button>
							</div>
						</fieldset>
					</div>

		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.settings.maintenance.js') }}" charset="utf-8"></script>

@endsection