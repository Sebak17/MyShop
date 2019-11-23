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
                    </thead>
                    <tbody id="productsList">
                        @foreach ($productsData as $product)
                            <tr>
                                <td>
                                    <a href="/produkt?id={{ $product['id'] }}"><h5>{{ $product['name'] }}</h5></a>
                                </td>
                                <td>{{ $product['amount'] }} szt.</td>
                                <td>{{ $product['fullPrice'] }} zł</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            <div class="card card-body mb-3">
                <legend><i class="fas fa-balance-scale"></i> Podsumowanie</legend>
                <hr />

                <div class="row text-center">
                    <div class="col-6 lead">Suma:</div>
                    <div class="col-6 lead"><strong>{{ $summaryPrice }} zł</strong></div>
                </div>
                
                <hr />
                
                <div class="form-group text-right">
                    <div class="alert text-center d-none" id="alert"></div>
                    
                    <button class="btn btn-success" id="btnConfirm">Potwierdzam <i class="fas fa-check"></i></button>
                </div>
                
            </div>
            <div class="card card-body">
                <legend><i class="fas fa-search-location"></i> Dostawa</legend>
                <hr />

                
                <div class="row text-left ml-2">
                    <div class="col-6 lead">Ulica: </div>
                    <div class="col-6 lead"><strong></strong></div>
                </div>

                <div class="row text-left ml-2">
                    <div class="col-6 lead">Miejscowość: </div>
                    <div class="col-6 lead"><strong></strong></div>
                </div>

                <div class="row text-left ml-2">
                    <div class="col-6 lead">Kod pocztowy: </div>
                    <div class="col-6 lead"><strong></strong></div>
                </div>

                <div class="row text-left ml-2">
                    <div class="col-6 lead">Województwo: </div>
                    <div class="col-6 lead"><strong></strong></div>
                </div>
                
                <hr />
                
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection