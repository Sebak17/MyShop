@extends('core.app', ['titleInfo' => "Zamówienie nr " . $order->id])

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3" data-order-id="{{ $order->id }}">
    <div class="row">
        <div class="col-12 col-lg-6 offset-lg-1">
            <div class="card card-body mb-3">
                <legend><i class="fas fa-shopping-basket"></i> Uwagi do zamówienia</legend>
                <hr />
                
                <div class="row">
                    <div class="col-12">
                        <p>
                        {!! nl2br($order->note) !!}
                        </p>
                    </div>
                </div>
                
            </div>
            
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
                            <td>{{ $product['fullPrice'] }} {{ config('site.currency') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="col-12 col-lg-4">
            @if(in_array($order->status, ['PAID','REALIZE','SENT']))
            <div class="alert alert-success"><i class="fas fa-check"></i> Zamówienie zostało opłacone!</div>
            @elseif(in_array($order->status, ['PROCESSING']))
            <div class="alert alert-warning"><i class="fas fa-spinner fa-spin"></i> Płatność jest przetwarzana...</div>
            @elseif(in_array($order->status, ['CREATED','UNPAID']))
            <div class="alert alert-danger"><i class="fas fa-exclamation"></i> Zamówienie nie zostało opłacone!</div>
            @endif
            
            <div class="card card-body mb-3">
                <legend><i class="fas fa-balance-scale"></i> Podsumowanie</legend>
                <hr />
                
                <div data-status="{{ $order->status }}"></div>
                
                <div class="row text-center">
                    <div class="col-6 lead">Rodzaj płatności:</div>
                    <div class="col-6 lead"><strong>{{ config('site.payment_name.' . $order->payment) }}</strong></div>
                </div>
                
                <div class="row text-center">
                    <div class="col-6 lead">Suma:</div>
                    <div class="col-6 lead"><strong>{{ $order->cost }} {{ config('site.currency') }}</strong></div>
                </div>
                
                <hr />
                
                @if(in_array($order->status, ['CREATED','UNPAID']))
                <button class="btn btn-success" id="btnPay" {{ ( $order->status == 'PROCESSING' ? "disabled" : "") }}>Zapłać <i class="far fa-money-bill-alt"></i></button>
                @elseif(in_array($order->status, ['PROCESSING']))
                <div class="row">
                    <div class="col-6 text-center">
                        <button class="btn btn-danger" id="btnPayCancel">Anuluj płatność <i class="far fa-money-bill-alt"></i></button>
                    </div>
                    <div class="col-6 text-center">
                        <button class="btn btn-success" id="btnPay" disabled>Zapłać <i class="far fa-money-bill-alt"></i></button>
                    </div>
                </div>
                @endif
                
            </div>
            
            <div class="card card-body mb-3">
                <legend><i class="fas fa-stream"></i> Status zamówienia</legend>
                
                <hr />
                
                <ul class="timeline">
                    <li class="{{ ( in_array($order->status, ['UNPAID','PROCESSING','PAID','REALIZE','SENT','RECEIVE']) ? 'ready' : '') }} {{ ( $order->status == 'CREATED' ? 'active' : '') }}">
                        <a>Stworzono zamówienie</a>
                    </li>
                    <li class="{{ ( in_array($order->status, ['PAID','REALIZE','SENT','RECEIVE']) ? 'ready' : '') }} {{ ( in_array($order->status, ['UNPAID', 'PROCESSING']) ? 'active' : '') }}">
                        <a>Płatność internetowa</a>
                    </li>
                    <li class="{{ ( in_array($order->status, ['SENT','RECEIVE']) ? 'ready' : '') }} {{ ( $order->status == 'REALIZE' ? 'active' : '') }}">
                        <a>Realizowanie zamówienia</a>
                    </li>
                    <li class="{{ ( in_array($order->status, ['RECEIVE']) ? 'ready' : '') }} {{ ( $order->status == 'SENT' ? 'active' : '') }}">
                        <a>Wysyłka paczki</a>
                    </li>
                    <li class="{{ ( $order->status == 'RECEIVE' ? 'active' : '') }}">
                        <a>Przesyłka u klienta</a>
                    </li>
                </ul>
            </div>
            
            <div class="card card-body">
                <legend><i class="fas fa-search-location"></i> Dostawa</legend>
                <hr />
                
                @if($deliverInfo['type'] == 'COURIER')
                <div class="row text-left ml-2">
                    <div class="col-6 lead">Ulica: </div>
                    <div class="col-6 lead"><strong>{{ $deliverInfo['address'] }}</strong></div>
                </div>
                
                <div class="row text-left ml-2">
                    <div class="col-6 lead">Miejscowość: </div>
                    <div class="col-6 lead"><strong>{{ $deliverInfo['city'] }}</strong></div>
                </div>
                
                <div class="row text-left ml-2">
                    <div class="col-6 lead">Kod pocztowy: </div>
                    <div class="col-6 lead"><strong>{{ $deliverInfo['zipcode'] }}</strong></div>
                </div>
                
                <div class="row text-left ml-2">
                    <div class="col-6 lead">Województwo: </div>
                    <div class="col-6 lead"><strong>{{ config('site.district_name.'.$deliverInfo['district']) }}</strong></div>
                </div>
                @elseif($deliverInfo['type'] == 'INPOST_LOCKER')
                <div class="row text-left ml-2">
                    <div class="col-6 lead">Nazwa paczkomatu: </div>
                    <div class="col-6 lead"><strong id="lockerCode">{{ $deliverInfo['lockerName'] }}</strong></div>
                </div>
                <div id="lockerInfo">
                </div>
                @endif
                
                <hr />
                
                @if($deliverInfo['type'] == 'INPOST_LOCKER')
                <div class="form-group text-right">
                    <button class="btn btn-info" id="btnShowLocLocker">Pokaż na mapie <i class="fas fa-map-marker-alt"></i></button>
                </div>
                @endif
                
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_order.item.js') }}" charset="utf-8"></script>

@endsection