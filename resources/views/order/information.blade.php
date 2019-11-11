@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 col-lg-6 offset-lg-1">
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

            <div class="card card-body mb-3">

                <legend><i class="fas fa-search-location"></i> Dostawa</legend>
                <hr />

                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="orderDeliver">Wybierz sposób dostawy:</label>
                            <select id="orderDeliver" class="form-control">
                                <option class="d-none" selected></option>
                                <option value="INPOST_LOCKER">InPost - Paczkomat (8.99 zł)</option>
                                <option value="INPOST_COURIER">InPost - Kurier (15.99 zł)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12">

                    </div>

                </div>
            </div>

            <div class="card card-body">

                <legend><i class="fas fa-money-check-alt"></i> Płatność</legend>
                <hr />

                <div class="row">
                    <div class="col-3 d-flex align-items-stretch">
                        <div data-payment="1" class="card card-body text-center h-100 justify-content-center">
                            <img class="img-fluid" alt="PayU" src="{{ asset('img/logos/payu.png') }}" >
                            <h5>PayU</h5>
                        </div>
                    </div>
                    <div class="col-3 d-flex align-items-stretch">
                        <div data-payment="2" class="card card-body text-center h-100 justify-content-center">
                            <img class="img-fluid" alt="PayPal" src="{{ asset('img/logos/paypal.png') }}" >
                            <h5>PayPal</h5>
                        </div>
                    </div>
                    <div class="col-3 d-flex align-items-stretch">
                        <div data-payment="3" class="card card-body text-center h-100 justify-content-center">
                            <img class="img-fluid" alt="PaymentCard" src="{{ asset('img/logos/paymentcard.png') }}" >
                            <h5>Karta kredytowa/debetowa</h5>
                        </div>
                    </div>
                </div>

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
<script src="{{ asset('js/_order.creating.js') }}" charset="utf-8"></script>

<script async src="https://geowidget.easypack24.net/js/sdk-for-javascript.js"></script>
<link rel="stylesheet" href="https://geowidget.easypack24.net/css/easypack.css"/>

@endsection
