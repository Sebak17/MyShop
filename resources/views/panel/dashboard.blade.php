@extends('panel.app')
@extends('core.menu')

@section('content')

<div class="page-header">
    <h2><i class="fas fa-solar-panel"></i> Panel główny</h2>
</div>

<div class="card card-body">
    <div class="row">
        
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Zakupów</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalOrders">?</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Produktów do oceny</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalProductsMeasure">?</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comment-medical fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Punktów lojalności</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="_totalLoyality">?</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>

@endsection