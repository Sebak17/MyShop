@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body">
                    
                    <fieldset class="form-horizontal">
                        
                        <div class="card text-white bg-danger mb-3">
                            <div class="card-body text-center">
                                <h4 class="card-title">Wystąpił błąd podczas ładowania strony!</h4>
                                <h5 class="card-subtitle">Zaloguj się, aby przejść dalej!</h5>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/_search.engine.js') }}" charset="utf-8"></script>
<script type="text/javascript">
    window.onload = function () {
        stickFooter();
        
        setTimeout(function () {
                window.location.href = "{{ route('loginPage') }}";
        }, 2500);
    }
</script>

@endsection