@extends('core.app', ['titleInfo' => "Status płatności"])

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 col-lg-4 offset-lg-4">
            <div class="card card-body mb-3 text-center">
                <legend><i class="fas fa-money-check"></i> Status płatności</legend>
                <hr />
                
                <div class="alert alert-success">
                    <h4>
                    <i class="fas fa-check-circle"></i> Płatność została zaakceptowana!
                    </h4>
                    {{ (isset($results['msg']) ? $results['msg'] : "") }}
                </div>

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

@endsection