@extends('admin.app')

@section('content_sub')

<div class="container-fluid">
    
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Panel główny</h1>
    </div>
    
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Zarobki (Ogólne)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalEarningsAll">?</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Zarobki (Miesiąc)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalEarningsMonth">?</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Produktów</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalProducts">?</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shipping-fast fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Zamówień</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalOrders">?</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-bag fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <div class="row">

        <div class="col-xl-8 offset-xl-2 col-lg-10 offset-lg-1">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Zarobki na rok <strong>{{ date("Y") }}</strong></h6>
                </div>
                

                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="earningsByMonth"></canvas>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    
</div>

<script src="{{ asset('js/_admin.panel.js') }}" charset="utf-8"></script>

<script src="{{ asset('js/Chart.min.js') }}" charset="utf-8"></script>

@endsection