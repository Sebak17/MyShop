@extends('admin.app')

@section('content_sub')

<div class="container-fluid" data-id="{{ $product->id }}">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Lista towarów</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-md-12 order-md-2 col-xl-8 order-xl-1 mb-3 mt-3 mt-xl-0">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><i class="fas fa-info"></i> Znaleziono <strong>{{ count($items) }}</strong> towarów</li>
			</ol>
			<div class="card card-body">
				
				
				<div class="row">
					<div class="col">
						<div>
							<ul class="pagination" id="pagesList">
								<li class="page-item">
									<a class="page-link" href="#" id="btnItemsPagePrev">&laquo;</a>
								</li>
								<li class="page-item">
									<input type="number" class="page-link" placeholder="Podaj numer strony" id="inp_pageNumber">
								</li>
								<li class="page-item disabled">
									<a class="page-link">/</a>
								</li>
								<li class="page-item disabled">
									<a class="page-link" id="lastSiteNumber">?</a>
								</li>
								<li class="page-item">
									<a class="page-link" href="#" id="btnItemsPageNext">&raquo;</a>
								</li>
							</ul>
						</div>

					</div>
					<div class="col">
						<div class="mb-3 text-right">
							<button class="btn btn-primary" id="btnAddItemModal"><i class="fas fa-plus"></i> Dodaj towar</button>
						</div>
					</div>
				</div>
				
				
				<div class="alert alert-dismissible alert-success text-left d-none" id="alert">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<span></span>
				</div>
				
				
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th></th>
							<th>Kod</th>
							<th>Status</th>
							<th>Data dodania</th>
							<th>Opcje</th>
						</tr>
						
					</thead>
				<tbody id="itemsList"></tbody>
			</table>
			
		</div>
	</div>
	
	<div class="col-12 col-md-12 order-md-1 col-xl-4 order-xl-2">
		<div class="card card-body">
			<legend><i class="fas fa-info"></i> Informacje o produkcie</legend>
			<hr />
			
			<div>
				<img class="mx-auto d-block" style="height: 150px;" src="/storage/products_images/{{ (count($product->images) > 0 ? $product->images[0]->name : null) }}" alt="">
			</div>
			
			<h4>{{ $product->title }}</h4>
			
			<hr />
			<h3 class="text-right">{{ number_format((float)$product->price, 2, '.', '') . ' ' . config('site.currency') }}</h3>
			<hr />
			
		</div>
	</div>
	
</div>

</div>

<!-- Item add modal -->
<div class="modal fade" id="modalItemAdd">
	<div class="modal-dialog">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-plus-circle"></i> Dodawanie towaru</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<div class="alert d-none" id="alert01"></div>
				
				<div class="form-group">
					<label for="inp_addItemCode"><i class="fas fa-barcode"></i> Podaj kod towaru:</label>
					<input type="text" id="inp_addItemCode" class="form-control">
				</div>
				
				<div class="form-group">
					<label for="inp_addItemStatus"><i class="fas fa-question-circle"></i> Podaj status towaru:</label>
					<select id="inp_addItemStatus" class="form-control">
						<option class="d-none" selected></option>
						@foreach(config('site.warehouse_item_status') as $key => $value)
						<option value="{{ $key }}">{{ $value }}</option>
						@endforeach
					</select>
				</div>
				
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
				<button type="button" class="btn btn-success" id="btnAddItem">Dodaj <i class="fas fa-plus"></i></button>
			</div>
		</div>
	</div>
</div>

<!-- Item history modal -->
<div class="modal fade" id="modalItemHistory">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			
			<div class="modal-header">
				<h4 class="modal-title"><i class="fas fa-history"></i> Historia towaru</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			
			<div class="modal-body">
				<table class="table table-striped">
					<tbody id="modalHistoryData"></tbody>
				</table>
			</div>
			
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
			</div>
		</div>
	</div>
</div>


<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_admin.warehouse.product.js') }}" charset="utf-8"></script>
<script>
	var itemStatusName = [];

	@foreach(config('site.warehouse_item_status') as $key => $value)
		itemStatusName["{{ $key }}"] = "{{ $value }}";
	@endforeach
</script>
@endsection