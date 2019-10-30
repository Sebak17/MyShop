@extends('panel.app')
@extends('core.menu')

@section('content')

<div class="page-header">
	<h2><i class="fas fa-receipt"></i> Lista zamówień</h2>
</div>

<hr>

<div class="card card-body">
	<div class="row">
		
		<table class="table table-stripped">
			<thead>
				<tr>
					<th><i class="fas fa-list-ol"></i> Numer zamówienia</th>
					<th><i class="fas fa-calendar-alt"></i> Data założenia</th>
					<th><i class="fas fa-shopping-cart"></i> Ilość produktów</th>
					<th><i class="fas fa-eye"></i> Status</th>
					<th><i class="fas fa-dollar-sign"></i> Koszt</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="text-center">
						<a href="/panel/zamowienie/123000">
							<h4>
								<span class="badge badge-primary">123000</span>
							</h4>
						</a>
					</td>
					<td>10-10-2019 16:61</td>
					<td>3</td>
					<td>Wysłane</td>
					<td>10.00 zł</td>
				</tr>
				<tr>
					<td class="text-center">
						<a href="/panel/zamowienie/123000">
							<h4>
								<span class="badge badge-primary">123000</span>
							</h4>
						</a>
					</td>
					<td>10-10-2019 16:61</td>
					<td>3</td>
					<td>Wysłane</td>
					<td>10.00 zł</td>
				</tr>
			</tbody>
		</table>
		
	</div>
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
@endsection