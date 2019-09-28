@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body">
                    
                    <fieldset class="form-horizontal">
                        
                        <legend><i class="fas fa-globe"></i> Rejestacja</legend>
                        <hr />
                        
                        <div class="alert d-none" id="alert"></div>
                        
                        <div id="registerBox"></div>
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" id="progressStep"></div>
                                </div>
                            </div>
                        </div>
                        
                        
                        <div class="form-group">
                            <button type="button" id="btnRegBack" class="btn btn-warning d-none"><i class="fas fa-arrow-left"></i> Cofnij</button>
                            <button type="button" id="btnRegNext" class="btn btn-primary float-right">Dalej <i class="fas fa-arrow-right"></i></button>
                        </div>
                        
                    </fieldset>
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.public_key') }}"></script>

<script src="{{ asset('js/_auth.signup.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/jquery-mask-phone-number.js') }}" charset="utf-8"></script>

@endsection
