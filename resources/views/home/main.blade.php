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
                                        <tr data-href="{{ route('productsPage') }}/?category={{ $category['id'] }}"><td class="text-center"><i class="fas {{ $category['icon'] }}"></i></td><td>{{ $category['name'] }}</td></tr>
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
                            <div class="row" id="proposedProducts">
                                <div class="col-6 col-md-4 col-lg-3 col-xl-3">
                                    <a href="#" class="tdn">
                                        <div class="card">
                                            <img style="height: 200px; width: 100%; display: block;"
                                            src="data:image/svg+xml;charset=UTF-8,%3Csvg%20width%3D%22318%22%20height%3D%22180%22%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20viewBox%3D%220%200%20318%20180%22%20preserveAspectRatio%3D%22none%22%3E%3Cdefs%3E%3Cstyle%20type%3D%22text%2Fcss%22%3E%23holder_158bd1d28ef%20text%20%7B%20fill%3Argba(255%2C255%2C255%2C.75)%3Bfont-weight%3Anormal%3Bfont-family%3AHelvetica%2C%20monospace%3Bfont-size%3A16pt%20%7D%20%3C%2Fstyle%3E%3C%2Fdefs%3E%3Cg%20id%3D%22holder_158bd1d28ef%22%3E%3Crect%20width%3D%22318%22%20height%3D%22180%22%20fill%3D%22%23777%22%3E%3C%2Frect%3E%3Cg%3E%3Ctext%20x%3D%22129.359375%22%20y%3D%2297.35%22%3EImage%3C%2Ftext%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E"
                                            alt="Produkt 1">
                                            
                                            <div class="card-body">
                                                <h3 class="card-title">10.00 {{ config('site.currency') }}</h3>
                                                <h5 class="card-subtitle text-muted">Produkt 1</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
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
                                <div class="col-6 col-md-4 col-lg-3 col-xl-2 mt-2 mt-md-1">
                                    <a href="{{ $product['url'] }}" class="tdn">
                                        <div class="card">
                                            <img style="height: 200px; width: 100%; display: block;"
                                            src="/storage/products_images/{{ $product['image'] }}"
                                            alt="{{ $product['name'] }}">

                                            <div class="card-body">
                                                <h3 class="card-title">{{ $product['price'] . " " . config('site.currency') }}</h3>
                                                <h5 class="card-subtitle text-muted">{{ $product['name'] }}</h5>
                                            </div>
                                        </div>
                                    </a>
                                </div>
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