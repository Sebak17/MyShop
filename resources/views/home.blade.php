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
                                <table id="categoriesList" class="col-10 offset-1"></table>
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
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
