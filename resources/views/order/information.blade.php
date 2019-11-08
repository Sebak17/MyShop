@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 col-lg-6 offset-lg-1">
            <div class="card card-body mb-3">
                
                <legend><i class="fas fa-shopping-basket"></i> Lista przedmiotów</legend>
                

            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card card-body">
                <legend><i class="fas fa-balance-scale"></i> Podsumowanie</legend>
                <hr />

                <div class="alert text-center d-none" id="alert"></div>
                
                <div class="row text-center">
                    <div class="col-6 lead">Suma:</div>
                </div>
                
                <hr />
                
                <div class="form-group text-right">
                    <a href="#">
                     <button class="btn btn-success">Potwierdzam <i class="fas fa-check"></i></button>
                    </a>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection