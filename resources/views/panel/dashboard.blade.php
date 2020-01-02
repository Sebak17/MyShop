@extends('panel.app')
@extends('core.menu')

@section('content')

<div class="page-header">
    <h2><i class="fas fa-solar-panel"></i> Panel główny</h2>
</div>

<div class="card card-body">
    <div class="row">
        
        <div class="col-xl-4 col-md-6 mb-4 offset-xl-2">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Zamówień</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalOrders">{{ $summary['orders'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Zakupionych produktów</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalProducts">{{ $summary['products'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    
    <div class="row">
        <div class="col-12">
            <hr />
            
            <p style="font-size: 150%;" >
                Witaj <strong>{{ $user->email }}</strong>!
            </p>
            
            
            <p class="lead">
                Twoje dane:
            </p>
            
            <div class="row">
                <div class="col-6">
                    <div class="row text-left ml-2">
                        <div class="col-6">Imie: </div>
                        <div class="col-6"><strong>{{ $user->personal->firstname }}</strong></div>
                    </div>
                    
                    <div class="row text-left ml-2">
                        <div class="col-6">Nazwisko: </div>
                        <div class="col-6"><strong>{{ $user->personal->surname }}</strong></div>
                    </div>
                    
                    <div class="row text-left ml-2">
                        <div class="col-6">Numer telefonu: </div>
                        <div class="col-6"><strong>{{ $user->personal->phoneNumber }}</strong></div>
                    </div>
                </div>
                
                <div class="col-6">
                    <div class="row text-left ml-2">
                        <div class="col-6">Adres: </div>
                        <div class="col-6"><strong>{{ $user->location->address }}</strong></div>
                    </div>
                    
                    <div class="row text-left ml-2">
                        <div class="col-6">Miasto: </div>
                        <div class="col-6"><strong>{{ $user->location->city }}</strong></div>
                    </div>
                    
                    <div class="row text-left ml-2">
                        <div class="col-6">Kod pocztowy: </div>
                        <div class="col-6"><strong>{{ $user->location->zipcode }}</strong></div>
                    </div>
                    
                    <div class="row text-left ml-2">
                        <div class="col-6">Województwo: </div>
                        <div class="col-6"><strong>{{ config('site.district_name.' . $user->location->district) }}</strong></div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
</div>

<div class="card card-body mt-3">
    <legend><i class="fas fa-list"></i> Ostatnio zakupione produkty</legend>
    
    <hr />
    
    <table class="table table-striped">
        <tbody>
            @foreach($lastProducts as $product)
            <tr>
                <td>
                    <img src="/storage/products_images/{{ $product['image'] }}" style="width: 50px; max-height: 40px;">
                </td>
                <td>
                    <a href="{{ $product['url'] }}"><h5>{{ $product['name'] }}</h5></a>
                </td>
                <td>
                    <h4>{{ $product['price'] }} {{ config('site.currency') }}</h4>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection