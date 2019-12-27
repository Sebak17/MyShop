@extends('core.app')

@extends('core.menu')

@section('content')


<div class="container-fluid mt-3">
	<div class="row">
		<div class="col-sm-12 col-md-10 offset-md-1">
			<div class="row">
				
				<div id="left-column" class=" mt-3 col-sm-12 col-md-12 col-xl-3 offset-xl-1 col-lg-4 offset-lg-0">
					
					<button class="btn w-100 mb-3" data-toggle="collapse" data-target="#left-column-content">
					<i class="fas fa-cog 1x"></i> Pokaż opcje
					</button>
					
					<div id="left-column-content" class="collapse show">
						<div class="card">
							<div class="card-body">
								<h5 class="card-title"><i class="fas fa-sitemap"></i> Kategorie</h5>
								<hr />
								
								<p>
									Aktualna: <strong>{{ $currentCategory['name'] }}</strong>
								</p>
								
								<hr />
								
								<div class="mt-1">
									<table class="col-12">
										@if (isset($overCategory) && $currentCategory['id'] != 0)
										<thead id="categoryBack">
											<tr class="category category-item-back" category="{{ $overCategory['id'] }}"><td class="text-center"><i class="fas fa-level-up-alt fa-1x"></i></td><td>cofnij do <b>{{ $overCategory['name'] }}</b></td></tr>
										</thead>
										@endif
										
										<tbody id="categoriesList2">
											@foreach ($categoriesList as $category)
											<tr class="category category-item" category="{{ $category['id'] }}"><td class="text-center"><i class="fas {{ $category['icon'] }} fa-1x"></i></td><td>{{ $category['name'] }}</td></tr>
											@endforeach
										</tbody>
									</table>
									
								</div>
								
							</div>
						</div>
						
						<div class="card mt-3">
							<div class="card-body">
								<h5 class="card-title"><i class="fas fa-sitemap"></i> Sortuj według</h5>
								<hr />
								
								<div class="sortList">
									<select class="custom-select" id="sortType">
										<option value="1">&nbsp;&nbsp; nazwa produktu od A do Z</option>
										<option value="2">&nbsp;&nbsp; nazwa produktu od Z do A</option>
										<option value="3">&nbsp;&nbsp; popularności</option>
										<option value="4">&nbsp;&nbsp; cena rosnąco</option>
										<option value="5">&nbsp;&nbsp; cena malejąco</option>
									</select>
								</div>
							</div>
						</div>
						
						<div class="card mt-3">
							<div class="card-body">
								<h4 class="card-title"><i class="fas fa-filter"></i> Filtry</h4>
								<hr />
								
								<div class="filters">
									
									<div class="">
										<h5><i class="fas fa-money"></i> Cena</h5>
										<div class="row">
											<div class="col-5">
												<input type="number" class="form-control" placeholder="od" id="fl_price1">
											</div>
											<div class="col-1 mt-4" style="padding-left: 0px; padding-right: 0px;">
												<i class="fas fa-minus fa-1x"></i>
											</div>
											<div class="col-5">
												<input type="number" class="form-control" placeholder="do" id="fl_price2">
											</div>
										</div>
									</div>
									
									<div id="customFilters">
									</div>
									
									<div class="mt-2">
										<hr />
									</div>
									
									<div class="mt-2">
										<div class="row">
											<div class="col-12">
												<button type="button" class="btn btn-primary w-100" id="btnFiltersApply">
												<i class="fas fa-check"></i> Zastosuj
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
				</div>
				
				<div class="mt-3 col-sm-12 col-md-12 col-xl-8 col-lg-8">
					<div class="page-header">
						<h2><i class="fas fa-tasks"></i> Lista ofert</h2>
					</div>
					
					<div class="card">
						<div class="card-body" id="productsList">
							@if (count($productsList) == 0)
							<div class="text-center">
								<h4><i class="fas fa-search"></i> Nie znaleziono żadnych ofert!</h4>
							</div>
							@endif
							
							@foreach ($productsList as $product)
							<a href="/produkt?id={{ $product['id'] }}" class="tdn">
								<div class="product-block row pt-3 pb-3 border-top border-bottom">
									<div class="col-3 col-md-3">
										<img src="/storage/products_images/{{ $product['image'] }}" class="img-thumbnail products-list-image" alt="{{ $product['name'] }}">
									</div>
									<div class="col-5 col-md-6">
										<h4>{{ $product['name'] }}</h4>
										@if ($product['buyers'] > 0)
										<p class="text-muted mb-1">{{ $product['buyers'] }} osób kupiło</p>
										@endif
									</div>
									
									<div class="col-4 col-md-3">
										<h2 class="mt-2">{{ $product['price'] . " " . config('site.currency') }}</h2>
										
									</div>
									
								</div>
							</a>
							@endforeach
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="{{ asset('js/_products.list.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection