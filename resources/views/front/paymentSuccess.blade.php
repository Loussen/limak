@extends('layouts.front_main')
@section('styles')
    <style>
        .application-loading-container {
            display: none !important;
        }
    </style>
    @endsection
@section('content')
    <section class="success invoice content">
        <div class="container">
            <div class="row">
                <div class="invoice-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-7">
                        <h4>ÖDƏNİŞ </h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-5">
                        <ol class="breadcrumb web">
                            <li><a href="/">Ana səhifə </a></li>
                            <li class="active">Ödəniş</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="/">... </a></li>
                            <li class="active">Ödəniş</li>
                        </ol>
                    </div>
                </div>
                <div class="invoice-body col-xs-12">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="invoice-left-side block">
                                <div class="left-content text-center">
                                    <div class="row relative">
                                        <div class="success-left col-sm-6 col-xs-12">
                                            <h2>@lang('payment.successHeader')!</h2>
                                            <div class="focus">
                                                <p>{{$message}}</p>
                                            </div>
                                        </div>
                                        <div class=" success-right col-sm-6 col-xs-12">
                                            <div class="success-img">
                                                <img src="/front/new/img/success.png" alt="success" class="img-responsive center-block">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
