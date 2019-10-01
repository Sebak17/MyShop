@extends('core.app')

@extends('core.menu')

@section('content')

<div class="container mt-3">
    <div class="row">
        <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card">
                <div class="card-body">

                    <fieldset class="form-horizontal">

                        <legend><i class="fas fa-bolt"></i> Aktywacja konta</legend>
                        <hr />

                        @if (isset($error_server) && $error_server != '')
                            <div class="alert alert-danger text-center">
                                <h4><i class="fas fa-times"></i> Wystąpił błąd po stronie serwera! ({{ $error_server }})</h4>
                            </div>
                        @elseif (isset($error) && $error != '')
                            <div class="alert alert-danger text-center">
                                <h4><i class="fas fa-times"></i> Wystąpił błąd!</h4>
                                <h3>{{ $error }}</h3>
                            </div>
                        @elseif (isset($success) && $success)
                            <div class="alert alert-success text-center">
                                <h4><i class="fas fa-check"></i> Konto zostało aktywowane pomyślnie!</h4>
                            </div>
                        @else
                            <div class="alert alert-danger text-center">
                                <h4><i class="fas fa-times"></i> Wystąpił błąd podczas aktywacji konta!</h4>
                            </div>
                        @endif

                    </fieldset>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    window.onload = function() {
        stickFooter();
    };
</script>

@endsection
