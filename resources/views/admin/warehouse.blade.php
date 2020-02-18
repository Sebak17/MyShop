@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Magazyn</h1>
	</div>
	
	<div class="row">

		<div class="col-12 col-sm-4 offset-sm-2 col-md-4 offset-md-0">
			<div class="card card-body">
				<h4 class="card-title"><i class="fas fa-boxes"></i> Lista towarów</h4>

				<hr />

				<div class="form-group text-right">
					<a href="{{ route('admin_warehouseListPage') }}">
						<button type="button" class="btn btn-primary">Przejdź <i class="fas fa-arrow-right"></i></button>
					</a>
				</div>
			</div>
		</div>

		<div class="col-12 col-sm-4 offset-sm-2 col-md-4 offset-md-4">
			<div class="card card-body">
				<h4 class="card-title"><i class="fas fa-pallet"></i> Produkty</h4>

				<hr />

				<p>
					Odśwież statusy dostępności produktów po zmianie na magazynie!
				</p>

				<div class="form-group text-right">
					<button type="button" class="btn btn-success"><i class="fas fa-sync"></i> Odśwież</button>
				</div>
			</div>
		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>

@endsection

