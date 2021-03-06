@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body">

                    <fieldset class="form-horizontal">

                        <legend><i class="fas fa-bolt"></i> Aktywuj konto</legend>
                        <hr />

                        <div class="alert d-none" id="alert"></div>

                        <div class="form-group">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-2x fa-at"></i></span>
                                </div>
                                <input id="inp_email" type="email" placeholder="Podaj email" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" id="btnActive" class="btn btn-primary float-right">Wyślij email aktywacyjny <i class="fas fa-arrow-right"></i></button>
                        </div>

                    </fieldset>

                    <hr />

                    <div class="row mt-3">
                        <div class="col text-center">
                            <p class="text-muted">Pamiętaj! Email aktywacyjny jest wysyłany raz na 5 minut!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.public_key') }}"></script>
<script src="{{ asset('js/_auth.account.active.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection
