@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container mt-3">
	<div class="row">
		<div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
			<div class="card">
				<div class="card-body">

					<fieldset class="form-horizontal">

						<legend><i class="fas fa-key"></i> Utwórz nowe hasło</legend>
						<hr />

						 @if (isset($success) && $success)

							<div class="alert d-none" id="alert"></div>

							<div class="form-group">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-2x fa-key"></i></span>
									</div>
									<input id="inp_password1" type="password" placeholder="Podaj hasło" class="form-control">
								</div>
							</div>
							<div class="form-group">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-2x fa-key"></i></span>
									</div>
									<input id="inp_password2" type="password" placeholder="Powtórz hasło" class="form-control">
								</div>
							</div>

							<div class="form-group">
								<button type="button" id="btnChange" class="btn btn-primary float-right">Zmień hasło <i class="fas fa-arrow-up"></i></button>
							</div>
						@else
							<div class="alert alert-danger text-center">
								<h4><i class="fas fa-times"></i> Kod autoryzacyjny jest już nie ważny! Zresetuj hasło jeszcze raz!</h4>
							</div>
						@endif

					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>


<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.public_key') }}"></script>
<script src="{{ asset('js/_auth.password_reset.change.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection
