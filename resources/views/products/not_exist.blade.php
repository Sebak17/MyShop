@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container-fluid mt-5">
    <div class="row">
        <div class="col-12 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card text-white bg-danger mb-3">
  <div class="card-body text-center">
    <h2 class="card-title"><i class="fas fa-exclamation-triangle"></i> Błąd 404</h2>
    <p class="card-text">Nie znaleziono podanego produktu!</p>
  </div>
</div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>

<script>
    $(document).ready(function () {
        stickFooter();
    });
</script>

@endsection