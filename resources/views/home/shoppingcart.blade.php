@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 col-lg-7 offset-lg-1">
            <div class="card card-body mb-3">

                <legend><i class="fas fa-shopping-basket"></i> Lista przedmiotów</legend>

                <table class="table table-striped">
                    <thead>
                        <tr class="d-flex">
                            <th class="col-4 col-sm-5 col-md-6 col-lg-5"><i class="fas fa-globe-americas"></i> Nazwa produktu</th>
                            <th class="col-3 col-sm-2 col-md-2 col-lg-2"><i class="fas fa-dollar-sign"></i> Cena przedmiotu</th>
                            <th class="col-2 col-sm-2 col-md-1 col-lg-2"><i class="fas fa-list-ol"></i> Ilość</th>
                            <th class="col-2 col-sm-2 col-md-2 col-lg-2"><i class="fas fa-dollar-sign"></i> Razem</th>
                            <th class="col-1 col-sm-1 col-md-1 col-lg-1"></th>
                        </tr>
                    </thead>
                    <tbody id="productsList">
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-12 col-lg-3">
            <div class="card card-body">
                <legend><i class="fas fa-balance-scale"></i> Podsumowanie</legend>
                <hr />

                <div class="alert text-center d-none" id="alert"></div>

                <div class="row text-center">
                    <div class="col-6 lead">Suma:</div>
                    <div class="col-6 lead"><strong id="summaryPrice">?</strong></div>
                </div>

                <hr />

                <div class="form-group text-right">
                     <button class="btn btn-success" id="btnNext">Kupuje <i class="fas fa-shopping-cart"></i></button>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_orders.shoppingcart.js') }}" charset="utf-8"></script>


@endsection
