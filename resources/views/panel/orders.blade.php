@extends('panel.app')
@extends('core.menu')

@section('content')

<div class="page-header">
	<h2><i class="fas fa-receipt"></i> Lista zamówień</h2>
</div>

<hr>

<div class="card card-body">
	<div class="row">
		<div class="col-12">
			
			
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
					@foreach($orders as $order)
					<tr>
						<td class="text-center">
							<a href="{{ route('orderIDPage', $order->id) }}">
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
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
@endsection