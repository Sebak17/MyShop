@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2">
            <div class="row">
                <div class="col-12">
                    <div class="page-header">
                        <h2><i class="far fa-heart"></i> Ulubione produkty</h2>
                    </div>
                    
                    <hr>
                </div>

                @if(empty($favoritesData))
                <div class="col-12 text-center">
                    <h3>
                        Nie znaleziono żadnych ulubionych produktów!
                    </h3>
                </div>
                <div class="col-12 text-center">
                    <img src="https://i.imgur.com/WfZ2pj3.gif" style="width: 20%;">
                </div>
                @endif

                @foreach($favoritesData as $product)
                <div class="col-3 mb-3">
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

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection