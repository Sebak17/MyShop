@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body">
                    
                    <fieldset class="form-horizontal">
                        
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body text-center">
                                <h4 class="card-title">Nie masz dostępu do tej strony!</h4>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection