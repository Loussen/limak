@extends('layouts.front')
@section('styles')
    <style>
        .application-loading-container {
            display: none !important;
        }
    </style>
@endsection
@section('content')
    <section class="page-block">
        <div class="container">
            <div class="page-head">
                <h1>@lang('payment.failHeader')</h1>
            </div>

            <div style="display: none;" class="alert alert-success" id="invoice-success">
            </div>
            <div class="block">
                <h1>@lang('payment.failHeader')!</h1>
                <span>{{$message}}</span>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        $(".application-loading-container").css('display', 'none');
        $(".application-loading-container").remove();
    </script>
@endsection
