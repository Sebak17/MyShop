@extends('core.app', ['titleInfo' => $product->title])

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3" data-id="{{ $product->id }}">
	<div class="row">
		<div class="col-sm-12 col-md-10 offset-md-1">
			<div class="row">
				<div class="col-12">
					<ol class="breadcrumb">
						{!! $categoriesPath !!}
					</ol>
				</div>
			</div>
			<div class="row">
				<div class="col-6">
					<div class="card card-body mb-3">
						<div id="imgCarousel" class="carousel slide" data-ride="carousel">
							
							<div class="carousel-inner" style="height: 400px;">
								@for ($i = 0 ; $i < count($product->images) ; $i++)
								<div class="carousel-item {{ ( $i == 0 ? 'active' : '') }}">
									<img src="/storage/products_images/{{ $product->images[$i]->name }}" alt="Zdjęcie {{ $i }}" class="mx-auto d-block" style="width: 100%; max-height: 400px;">
								</div>
								@endfor
							</div>
							
							<a class="carousel-control-prev" href="#imgCarousel" data-slide="prev">
								<span class="carousel-control-prev-icon"></span>
							</a>
							<a class="carousel-control-next" href="#imgCarousel" data-slide="next">
								<span class="carousel-control-next-icon"></span>
							</a>
						</div>
					</div>
				</div>
				
				<div class="col-6 mb-3">
					<div class="card card-body">
						<legend><i class="far fa-star" id="_favoriteIcon" data-favorite="false"></i> {{ $product->title }}</legend>
						
						<hr />
						
						<p>0 osób kupiło ten produkt</p>
						
						<h2>{{ number_format((float) $product->price, 2, '.', '') . " " . config('site.currency') }}</h2>
						
						<hr />
						
						<p>Status: {!! $status !!}</p>
						
						<hr />
						
						<div class="row">
							<div class="col text-right">
								<div class="alert alert-dismissible alert-success text-left d-none" id="alert">
									<button type="button" class="close" data-dismiss="alert">&times;</button>
									<span></span>
								</div>
								
								<div class="form-group">
									@if($product->status != "INACCESSIBLE")
									@if(Auth::guard('web')->check())
									<button class="btn btn-primary" id="btnShoppingCartAdd"><i class="fas fa-plus"></i> Dodaj do koszyka</button>
									@else
									<button class="btn btn-warning" data-toggle="tooltip" data-placement="right" title="Musisz się zalogować!" disabled><i class="fas fa-plus"></i> Dodaj do koszyka</button>
									@endif
									@else
									<button class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="Produkt nie jest dostępny!" disabled><i class="fas fa-plus"></i> Dodaj do koszyka</button>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row mb-3">
				<div class="col-12">
					<div class="card card-body">
						<legend><i class="fas fa-info"></i> Opis produktu</legend>
						<hr />
						
						<div>
							{!! nl2br($product->description) !!}
						</div>
					</div>
				</div>
			</div>
			
			@if(!empty($params))
			<div class="row mb-3">
				<div class="col-12">
					<div class="card card-body">
						<legend><i class="fas fa-table"></i> Informacje o produkcie</legend>
						<hr />
						
						<table class="table table-striped">
							<tbody>
								@foreach($params as $param)
								<tr>
									<td>{{ $param->name }}</td>
									<td>{{ $param->value }}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
			@endif
			
			<div class="row">
				<div class="col-12">
					<div class="card card-body">
						<legend><i class="fas fa-star"></i> Opinie klientów</legend>
						<hr />
					</div>
				</div>
			</div>
			
		</div>
		
	</div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>
@if(Auth::guard('web')->check())
<script src="{{ asset('js/_product.item.js') }}" charset="utf-8"></script>
@endif

@endsection