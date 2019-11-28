@extends('panel.app')
@extends('core.menu')

@section('content')

<div class="page-header">
	<h2><i class="fas fa-cog"></i> Zgłoszenia</h2>
</div>

<hr>

<div class="row">
	<div class="col-12">
		
		<div class="card card-body">
			<div class="row mb-3">
				<div class="col-6">
					<h4 class="card-title mt-1">
					<i class="fas fa-list"></i> Lista zgłoszeń
					</h4>
				</div>
				<div class="col-6 text-right">
					<button class="btn btn-primary">Dodaj zgłoszenie <i class="fas fa-plus"></i></button>
				</div>
			</div>
			
			
			<table class="table table-striped">
				<thead>
					<tr>
						<th class="d-none d-sm-table-cell">Numer zgłoszenia</th>
						<th>Tytuł</th>
						<th>Ostatnia odpowiedź</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@for($i = 0 ; $i < 5 ; $i++)
					<tr>
						<td class="d-none d-sm-table-cell">{{ $i }}</td>
						<td>ASADYDGBSAUDBUSADAS</td>
						<td>2000-20-20 20:20:20</td>
						<td>
							<button class="btn btn-sm btn-success">Zobacz <i class="fas fa-eye"></i></button>
						</td>
					</tr>
					@endfor
				</tbody>
			</table>
			
		</div>
	</div>
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>

@endsection