@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="row">
		<div class="col-12">
			<div class="card text-white bg-danger mb-3">
				<div class="card-body text-center">
					<h4 class="card-title"><i class="fas fa-exclamation-triangle"></i> Użytkownik nie istnieje!</h4>
				</div>
			</div>
		</div>
	</div>
	
</div>

<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>

@endsection