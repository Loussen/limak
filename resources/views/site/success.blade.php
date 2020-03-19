@extends('layouts.front_main')

@section('content')
    <section class="success invoice content">
        <div class="container">
            <div class="row">
                <div class="invoice-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-7">
                        <h4>@lang('register.user_register')</h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-6">
                        <ol class="breadcrumb web">
                            <li><a href="{{ route('front.main') }}">@lang('register.home_page') </a></li>
                            <li class="active"> @lang("register.register")</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="{{ route('front.main') }}">... </a></li>
                            <li class="active">@lang("register.register")</li>
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
                                            <h2>@lang("panel-errors.finished_register")</h2>

                                        </div>
                                        <div class=" success-right col-sm-6 col-xs-12">
                                            <div class="success-img">
                                                <img src="{{ asset('front/new/img/success.png') }}" alt="success" class="img-responsive center-block">
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
        let route = '{{ route('new-user-panel') }}'
        setTimeout(function(){
            window.location.replace(route);
        }, 5000);
    </script>
@endsection