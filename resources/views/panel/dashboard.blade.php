@extends('core.app')

@extends('core.menu')

@extends('panel.main')

@section('content_sub')

<div class="page-header">
    <h2><i class="fas fa-solar-panel"></i> Panel główny</h2>
</div>

<div class="card card-body">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
            <div class="card-counter bg-info text-white">
                <i class="fas fa-shopping-bag"></i>
                <span class="count-numbers">0</span>
                <span class="count-name">Zakupów</span>
            </div>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
            <a href="#">
                <div class="card-counter bg-warning text-white">
                    <i class="fas fa-comment-medical"></i>
                    <span class="count-numbers">0</span>
                    <span class="count-name">Zakupów do oceny</span>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-6 col-xl-4">
            <div class="card-counter bg-success text-white">
                <i class="fas fa-handshake"></i>
                <span class="count-numbers">0</span>
                <span class="count-name">Punktów lojalności</span>
            </div>
        </div>
    </div>
</div>

@endsection