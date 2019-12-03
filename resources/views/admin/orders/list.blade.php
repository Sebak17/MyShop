@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Lista zamówień</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-sm-8">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><i class="fas fa-info"></i> Znaleziono <strong>{{ count($orders) }}</strong> zamówień</li>
			</ol>
			<div class="card card-body">
				
				
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Numer zamówienia</th>
							<th>Ilość produktów</th>
							<th>Koszt</th>
							<th>Status</th>
							<th>Opcje</th>
						</tr>
					</thead>
					<tbody id="orderslist">
						@foreach($orders as $order)
						<tr>
							<td class="text-center">
								<h5>
								<span class="badge badge-primary">{{ $order->id }}</span>
								</h5>
							</td>
							<td>{{ count($order->products) }}</td>
							<td>{{ $order->cost }}</td>
							<td>{{ config('site.order_status.'.$order->status) }}</td>
							<td>
								<a href="{{ route('admin_orderPageID', $order->id) }}" target="_blank">
									<button class="btn btn-sm btn-primary"><i class="fas fa-eye"></i> Pokaż</button>
								</a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
				
			</div>
		</div>
		
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>

@endsection