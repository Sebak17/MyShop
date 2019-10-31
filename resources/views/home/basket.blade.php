@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 col-lg-6 offset-lg-1">
            <div class="card card-body mb-3">
                
                <legend><i class="fas fa-shopping-basket"></i> Lista przedmiotów</legend>
                
                <table class="table table-striped">
                    <thead>
                        <tr class="d-flex">
                            <th class="col-5 col-sm-6 col-md-7 col-lg-6"><i class="fas fa-globe-americas"></i> Nazwa produktu</th>
                            <th class="col-3 col-sm-2 col-md-2 col-lg-2"><i class="fas fa-dollar-sign"></i> Cena przedmiotu</th>
                            <th class="col-2 col-sm-2 col-md-1 col-lg-2"><i class="fas fa-list-ol"></i> Ilość</th>
                            <th class="col-2 col-sm-2 col-md-2 col-lg-2"><i class="fas fa-dollar-sign"></i> Razem</th>
                        </tr>
                    </thead>
                    <tbody id="productsList">
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card card-body">
                <legend><i class="fas fa-balance-scale"></i> Podsumowanie</legend>
                <hr />
                
                <div class="row text-center">
                    <div class="col-6 lead">Suma:</div>
                    <div class="col-6 lead"><strong id="summaryPrice">?</strong></div>
                </div>
                
                <hr />
                
                <div class="form-group text-right">
                    <button class="btn btn-success">Kupuje <i class="fas fa-shopping-cart"></i></button>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_orders.basket.js') }}" charset="utf-8"></script>


@endsection