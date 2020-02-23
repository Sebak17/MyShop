@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Wyszukiwanie towaru</h1>
	</div>
	
	<div class="row">
		<div class="col-12">
			<div class="card text-white bg-danger mb-3">
				<div class="card-body text-center">
					<h4 class="card-title"><i class="fas fa-exclamation-triangle"></i> Podany towar nie istnieje!</h4>
				</div>
			</div>
		</div>
	</div>
	
</div>

@endsection