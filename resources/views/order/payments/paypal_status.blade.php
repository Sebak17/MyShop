@extends('core.app', ['titleInfo' => "Status płatności"])

@extends('core.menu')

@section('content')

<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12 col-lg-4 offset-lg-4">
            <div class="card card-body mb-3 text-center">
                <legend><i class="fas fa-money-check"></i> Status płatności za zamówienie nr. {{ (isset($results['orderID']) ? $results['orderID'] : "?") }}</legend>
                <hr />
                
                @if(isset($results['success']) && $results['success'] == true)
                <div class="alert alert-success">
                    <h4>
                    <i class="fas fa-check-circle"></i> Płatność została zaakceptowana!
                    </h4>
                    {{ (isset($results['msg']) ? $results['msg'] : "") }}
                </div>
                @else
                <div class="alert alert-danger">
                    <h4>
                    <i class="fas fa-exclamation-circle"></i> Płatność nie została zaakceptowana!
                    </h4>
                    {{ (isset($results['msg']) ? $results['msg'] : "") }}
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

<script>
$(document).ready(function() {
stickFooter();
});
</script>

@endsection