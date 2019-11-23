@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-sm-12 col-md-10 offset-md-1">
            
            <div class="row">
                
                <div class="col-12">
                    <div class="card">
                        <div class="card-body main">
                            <div id="main-baner">
                                <img src="{{ asset('img/baners/baner1.png') }}" alt="Baner">
                            </div>
                            
                            <div class="col-12 text-center">
                                <div class="btn-group" role="group" id="baner-btns">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12 col-md-4 mt-3">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><i class="fas fa-list-ul"></i> Kategorie</h4>
                            <hr />
                            
                            <div class="row">
                            <table class="col-10 offset-1">
                                <tbody id="categoriesList">
                                    @foreach($categories as $category)
                                <tr data-href="/oferty/?category={{ $category['id'] }}"><td class="text-center"><i class="fas {{ $category['icon'] }}"></i></td><td>{{ $category['name'] }}</td></tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
            
            <div class="col-sm-12 col-md-8 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fas fa-user"></i> Oferta dla Ciebie</h4>
                        <hr />
                        
                        <!-- TODO:  -->
                        <div class="row" id="proposedOffers">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><i class="fas fa-eye"></i> Ostatnio przeglądane</h4>
                        <hr />
                        
                        <!-- TODO:  -->
                        <div class="row" id="lastSeen">
                            @foreach($productsHistory as $product)
                            <div class="col-6 col-md-4 col-lg-3 col-xl-2 mt-2 mt-md-1"><a href="{{ $product['url'] }}"><div class="card">
                                <img style="height: 200px; width: 100%; display: block;"
                                src="/storage/products_images/{{ $product['image'] }}"
                                alt="{{ $product['name'] }}">
                                <div class="card-body">
                                    <h3 class="card-title">{{ $product['price'] }}</h3>
                                    <h5 class="card-subtitle text-muted">{{ $product['name'] }}</h5>
                                </div></div></a></div>
                                @endforeach
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_home.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection