@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
	
	<div class="d-sm-flex align-items-center justify-content-between mb-4">
		<h1 class="h3 mb-0 text-gray-800">Ustawienia</h1>
	</div>
	
	<div class="row">
		<div class="col-12 col-sm-5 offset-sm-2">
			<div class="card card-body">
				<legend><i class="fas fa-images"></i> Banery na stronie głównej</legend>
				<hr />
				
				<div class="alert text-center d-none" id="alert"></div>

				<input type="file" class="d-none" id="imageUpload" accept="image/x-png,image/jpeg" multiple>

				<div class="form-group">
					<button type="button" id="btnAddBanner" class="btn btn-info"><i class="fas fa-plus"></i> Dodaj baner</button>
				</div>
				
			</div>
			
		</div>
		
		<div class="col-12 col-sm-3">
			<div class="card card-body">
				<legend><i class="far fa-images"></i> Lista zdjęć</legend>
				
				<table class="table">
					<tbody>
						@foreach($images as $image)
						<tr>
							<td>
								<img class="img-fluid shadow" width="150px" height="100px" src="/storage/banners/{{ $image }}">
							</td>
							<td class="align-middle">
								<button class="btn btn-danger btn-sm" data-btn-remove="{{ $image }}"><i class="fas fa-times"></i></button>
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
<script src="{{ asset('js/_admin.settings.banners.js') }}" charset="utf-8"></script>

@endsection