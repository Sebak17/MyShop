@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Ustawienia</h1>
	</div>
	
	<div class="row">

		<div class="col-12 col-sm-4 offset-sm-2 col-md-4 offset-md-0">
			<div class="card card-body">
				<h4 class="card-title"><i class="fas fa-images"></i> Banery na stronie głównej</h4>

				<hr />

				<div class="form-group text-right">
					<a href="{{ route('admin_settingsBannersPage') }}">
						<button type="button" class="btn btn-primary">Przejdź <i class="fas fa-arrow-right"></i></button>
					</a>
				</div>
			</div>

			<div class="card card-body mt-3">
				<h4 class="card-title"><i class="fas fa-tools"></i> Przerwa techniczna</h4>

				<hr />

				<div class="form-group text-right">
					<a href="{{ route('admin_settingsMaintenancePage') }}">
						<button type="button" class="btn btn-primary">Przejdź <i class="fas fa-arrow-right"></i></button>
					</a>
				</div>
			</div>
		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>

@endsection