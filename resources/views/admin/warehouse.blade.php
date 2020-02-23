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

			<div class="card card-body mt-3">
				<h4 class="card-title"><i class="fas fa-search"></i> Znajdź towar</h4>

				<hr />

				<div class="form-group">
					<label for="inp_searchBarcode"><i class="fas fa-barcode"></i> Podaj kod towaru:</label>
					<input type="text" class="form-control" id="inp_searchBarcode">

				</div>

				<div class="form-group text-right">
					<button type="button" class="btn btn-primary" id="btnSearch">Szukaj <i class="fas fa-search-plus"></i></button>
				</div>
			</div>
		</div>

		<!-- <div class="col-12 col-sm-4 offset-sm-2 col-md-4 offset-md-4">
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
		</div> -->
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script>
	$(document).ready(function() {
		bindButtons();
	});

	function bindButtons() {
		$("#btnSearch").click(function (){
			let code = $("#inp_searchBarcode").val();

			code = code.replace(/\s/g, '');

			if(code.length < 4)
				return;

			location.href = "{{ route('admin_warehouseItemSearchPage') }}?code=" + code;
		});
	}
</script>
@endsection

