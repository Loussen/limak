@extends('layouts.front_main')

@section('content')
    <section class="success invoice content">
        <div class="container">
            <div class="row">
                <div class="invoice-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-7">
                        <h4>@lang('panel-errors.finish_register')</h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-6">
                        <ol class="breadcrumb web">
                            <li><a href="{{ route('front.main') }}">@lang('register.home_page') </a></li>
                            <li class="active">@lang('panel-errors.finish_register')</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="{{ route('front.main') }}">... </a></li>
                            <li class="active">@lang('panel-errors.finish_register')</li>
                        </ol>
                    </div>
                </div>
                <div class="invoice-body col-xs-12">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="invoice-left-side block">
                                <div class="left-content">
                                    <div class="row relative">
                                        <div class="col-sm-6 col-xs-12">
                                            <h3><?php
                                                if(isset($error_message) and !empty($error_message)){
                                                    echo $error_message;
                                                }
                                            ?>
                                            </h3>
                                            <?php
                                                if($step==2){
                                                    ?>
                                                <h2>@lang('panel-errors.finish_register_code_title')</h2>
                                            <?php
                                                }elseif($step == 1){
                                                    ?>
                                                <h2>@lang('panel-errors.finish_register_title')</h2>
                                            <?php
                                                }
                                            ?>



                                            <form method="POST">
                                                <div class="login-block">


                                                    <?php
                                                        if($step == 2){
                                                            ?>
                                                    <div class="input-group">
                                                        <label for="phone">@lang('panel-errors.code')</label>
                                                        <input name="code" class="form-control" value="" required="" placeholder="kod">
                                                    </div>
                                                    <?php
                                                        }elseif($step == 1){
                                                            ?>
                                                        <div class="input-group">
                                                            <label for="phone">@lang('panel-errors.mobile_number')</label>
                                                            <input name="phone" class="form-control" value="{{$phone}}" required="" placeholder="@lang('panel-errors.mobile_number')">
                                                        </div>
                                                    <?php
                                                    }
                                                    ?>
                                                    <button type="submit" class="btn btn-effect">@lang('common.send')</button>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="success-right col-sm-6 col-xs-12">
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