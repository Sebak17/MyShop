@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body">
                    
                    <fieldset class="form-horizontal">
                        
                        <legend><i class="fas fa-sign-in-alt"></i> Logowanie</legend>
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
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-2x fa-key"></i></span>
                                </div>
                                <input id="inp_password" type="password" placeholder="Podaj hasło" class="form-control">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="button" id="btnLogin" class="btn btn-primary float-right">Zaloguj <i class="fas fa-arrow-up"></i></button>
                        </div>
                        
                        
                        
                    </fieldset>
                    
                    <hr />
                    
                    <div class="row mt-3">
                        <div class="col text-center">
                            <a href="/panel/aktywujkonto"><i class="fas fa-bolt"></i> Aktywuj konto</a>
                        </div>
                        
                        <div class="col text-center">
                            <a href="/panel/resetujhaslo"><i class="fas fa-key"></i> Zresetuj hasło</a>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.public_key') }}"></script>
<script src="/assets/js/_auth.signin.js" charset="utf-8"></script>
<script src="/assets/js/_validation.js" charset="utf-8"></script>

@endsection
