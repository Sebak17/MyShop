@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 order-2 order-lg-1 col-lg-8 col-xl-6 offset-xl-1">
            
            
            <div class="card card-body mb-3">
                
                <legend><i class="fas fa-user-tag"></i> Informacje o odbiorcy</legend>
                <hr />
                
                <fieldset class="mt-3">
                    <div class="form-group">
                        <label for="inp_data_fname"><i class="fas fa-id-card"></i> Imię:</label>
                        <input type="text" class="form-control" id="inp_data_fname" value="{{ Auth::user()->personal->firstname }}">
                    </div>
                    <div class="form-group">
                        <label for="inp_data_sname"><i class="far fa-id-card"></i> Nazwisko:</label>
                        <input type="text" class="form-control" id="inp_data_sname" value="{{ Auth::user()->personal->surname }}">
                    </div>
                    <div class="form-group">
                        <label for="inp_data_phone"><i class="fas fa-mobile-alt"></i> Numer telefonu:</label>
                        <input type="number" class="form-control" id="inp_data_phone" maxlength="9" value="{{ Auth::user()->personal->phoneNumber }}">
                    </div>
                </fieldset>
                
            </div>
            
            <div class="card card-body mb-3">
                
                <legend><i class="fas fa-search-location"></i> Dostawa</legend>
                <hr />
                
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="orderDeliver">Wybierz sposób dostawy:</label>
                            <select id="orderDeliver" class="form-control">
                                <option class="d-none" selected></option>
                                <option value="INPOST_LOCKER">InPost - Paczkomat (8.99 {{ config('site.currency') }})</option>
                                <option value="COURIER">Kurier (15.99 {{ config('site.currency') }})</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div id="deliverForm" class="d-none">
                            
                            <hr />
                            <h4 class="mb-3"><i class="fas fa-map-marked-alt"></i> Podaj adres dostawy: </h4>
                            
                            <div class="form-group">
                                <label for="inp_data_district"><i class="fas fa-map-marker-alt"></i> Województwo:</label>
                                <select class="custom-select" id="inp_data_district">
                                    <option value="1">Dolnośląskie</option>
                                    <option value="2">Kujawsko-Pomorskie</option>
                                    <option value="3">Lubelskie</option>
                                    <option value="4">Lubuskie</option>
                                    <option value="5">Łódzkie</option>
                                    <option value="6">Małopolskie</option>
                                    <option value="7">Mazowieckie</option>
                                    <option value="8">Opolskie</option>
                                    <option value="9">Podkarpackie</option>
                                    <option value="10">Podlaskie</option>
                                    <option value="11">Pomorskie</option>
                                    <option value="12">Śląskie</option>
                                    <option value="13">Świętokrzyskie</option>
                                    <option value="14">Warmińsko-Mazurskie</option>
                                    <option value="15">Wielkopolskie</option>
                                    <option value="16">Zachodniopomorskie</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inp_data_city"><i class="fas fa-building"></i> Miasto:</label>
                                <input type="text" class="form-control" id="inp_data_city" value="{{ Auth::user()->location->city }}">
                            </div>
                            <div class="form-group">
                                <label for="inp_data_zipcode"><i class="fas fa-building"></i> Kod pocztowy:</label>
                                <input type="text" class="form-control" id="inp_data_zipcode" value="{{ Auth::user()->location->zipcode }}">
                            </div>
                            <div class="form-group">
                                <label for="inp_data_address"><i class="far fa-id-card"></i> Ulica:</label>
                                <input type="text" class="form-control" id="inp_data_address" value="{{ Auth::user()->location->address }}">
                            </div>
                        </div>
                        
                        <div id="lockerInfo" class="d-none">
                            <div class="row">
                                <div class="col-4 col-sm-3 col-md-2">
                                    <button class="btn btn-success" id="btnChangeLocker"><i class="fas fa-edit"></i> ZMIEŃ</button>
                                </div>
                                <div class="col">
                                    <p>
                                        Wybrany paczkomat: <strong id="dataLockerName"></strong>
                                    </p>
                                    <p>
                                        Adres paczkomatu: <strong id="dataLockerAddress"></strong>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            <div class="card card-body mb-3">
                
                <legend><i class="fas fa-info"></i> Informacje</legend>
                <hr />
                
                <div class="row">
                    <div class="col-12">
                        <div class="card card-body">
                            <div class="form-group">
                                <label for="orderNote"><h6>Uwagi do zamówienia:</h6></label>
                                <textarea id="orderNote" rows="4" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <div class="card card-body">
                
                <legend><i class="fas fa-money-check-alt"></i> Płatność</legend>
                <hr />
                
                <div class="row">
                    <div class="col-6 col-sm-4 col-md-3 col-xl-3 d-flex align-items-stretch mb-2">
                        <div data-payment="1" class="card card-body text-center h-100 justify-content-center">
                            <img class="img-fluid" alt="PayU" src="{{ asset('img/logos/payu.png') }}" >
                            <h5>PayU</h5>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-3 d-flex align-items-stretch mb-2">
                        <div data-payment="2" class="card card-body text-center h-100 justify-content-center">
                            <img class="img-fluid" alt="PayPal" src="{{ asset('img/logos/paypal.png') }}" >
                            <h5>PayPal</h5>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-3 d-flex align-items-stretch mb-2">
                        <div data-payment="3" class="card card-body text-center h-100 justify-content-center">
                            <img class="img-fluid" alt="PaymentCard" src="{{ asset('img/logos/paymentcard.png') }}" >
                            <h6>Karta kredytowa/<br/>debetowa</h6>
                        </div>
                    </div>
                </div>
                
            </div>
            
        </div>
        
        <div class="col-12 order-1 order-lg-2 col-lg-4 col-xl-4">
            <div class="card card-body mb-3">
                <legend><i class="fas fa-balance-scale"></i> Podsumowanie</legend>
                <hr />
                
                <div class="row">
                    <div class="col-12">
                        <h5 class="ml-3"><i class="fas fa-shopping-bag"></i> Produkty</h5>
                        <table class="table table-striped">
                            <tbody>
                                @foreach ($productsData as $product)
                                <tr>
                                    <td>
                                        <a href="/produkt?id={{ $product['id'] }}"><h5>{{ $product['name'] }}</h5></a>
                                    </td>
                                    <td>{{ $product['amount'] }} szt.</td>
                                    <td>{{ $product['fullPrice'] . " " . config('site.currency') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <hr />
                
                <div class="lead">
                    Dostawa: <strong id="deliverPrice">?</strong><strong> {{ config('site.currency') }}</strong>
                </div>
                
                <hr />
                
                <div class="row text-center">
                    <div class="col-6 lead">Suma:</div>
                    <div class="col-6 lead"><strong id="summaryPrice">{{ $summaryPrice }}</strong><strong> {{ config('site.currency') }}</strong></div>
                </div>
                
                <hr />
                
                <div class="form-group text-right">
                    
                    <div class="alert text-center d-none" id="alert"></div>
                    
                    <button class="btn btn-success" id="btnConfirm">Potwierdzam <i class="fas fa-check"></i></button>
                </div>
                
            </div>
        </div>
    </div>
</div>

<!-- Modal - DELIVER -->
<div class="modal" id="modalDeliver">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            
            <div class="modal-header">
                <h4 class="modal-title">Wybierz paczkomat!</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            
            <div class="modal-body">
                <div id="easypack-map"></div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times"></i> Zamknij</button>
            </div>
            
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_validation.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/_order.creating.js') }}" charset="utf-8"></script>

<script async src="https://geowidget.easypack24.net/js/sdk-for-javascript.js"></script>
<link rel="stylesheet" href="https://geowidget.easypack24.net/css/easypack.css"/>

@endsection