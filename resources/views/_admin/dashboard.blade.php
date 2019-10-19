@extends('core.app')

@extends('core.menu')

@extends('admin.main')

@section('content_sub')

<div class="page-header">
    <h2><i class="fas fa-solar-panel"></i> Panel główny</h2>
</div>

<div class="card card-body">
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">
            <a href="#">
                <div class="card-counter bg-warning text-white">
                    <i class="fa fa-shopping-bag"></i>
                    <span class="count-numbers">0</span>
                    <span class="count-name">ofert do akceptacji</span>
                </div>
            </a>
        </div>
        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-4 offset-xl-2">
            <div class="card-counter bg-success text-white">
                <i class="fa fa-exclamation-triangle"></i>
                <span class="count-numbers">0</span>
                <span class="count-name">zgłoszeń</span>
            </div>
        </div>
    </div>
</div>

@endsection