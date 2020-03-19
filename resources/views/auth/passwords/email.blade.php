@extends('layouts.front_main')

@section('content')
    <section class="forget-pw invoice content">
        <div class="container">
            <div class="row">
                @include('front.components.breadcrumb' ,['section' => 'forgot_password'])
                <div class="col-xs-12">
                    <div class="forget-body block col-xs-12">
                        <div class="row relative">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                                </div>
                            @endif
                            <div class="forget-left col-sm-6 col-xs-12">
                                <form method="POST" action="{{ route('password.email') }}">
                                    <h1>@lang('passwords.password_recovery')</h1>
                                    <p>@lang('passwords.recovery_email')</p>
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="email" class="{{ $errors->has('email') ? 'error-message ' : '' }}form-control inputText" placeholder=" ">
                                            <span>@lang('passwords.email')</span>
                                        </label>
                                        @if ($errors->has('email'))
                                            <span class="error-text">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                    <button type="submit" class="btn-forget btn-effect">@lang('passwords.send_password')</button>
                                </form>
                            </div>
                            <div class="forget-right col-sm-6 col-xs-12 text-center">
                                <div class="forget-bg">
                                    <img src="{{ asset('front/new/img/forget-lock.png') }}" alt="forget-lock">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--<section class="forget-pw invoice content">--}}
    {{--<div class="container">--}}
    {{--<div class="row">--}}
    {{--<div class="invoice-head col-xs-12">--}}
    {{--<div class="col-md-4 col-sm-5 col-xs-6">--}}
    {{--<h4>ŞİFRƏNİ  <b>UNUTTUM</b></h4>--}}
    {{--</div>--}}
    {{--<div class="col-md-8 col-sm-7 col-xs-6">--}}
    {{--<ol class="breadcrumb web">--}}
    {{--<li><a href="index.html">Ana səhifə </a></li>--}}
    {{--<li class="active">Şifrəni unuttum</li>--}}
    {{--</ol>--}}
    {{--<ol class="breadcrumb mobile">--}}
    {{--<li><a href="index.html">... </a></li>--}}
    {{--<li class="active">Şifrəni unuttum</li>--}}
    {{--</ol>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--<div class="block">--}}
    {{--<form method="POST" action="{{ route('password.email') }}">--}}
    {{--<div class="row">--}}
    {{--<div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12">--}}
    {{--@if (session('status'))--}}
    {{--<div class="alert alert-success" role="alert">--}}
    {{--{{ session('status') }}--}}
    {{--</div>--}}
    {{--@endif--}}
    {{--<div class="login-block">--}}
    {{--<div class="input-group">--}}
    {{--<label for="email-log">E-mail</label>--}}
    {{--<input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" required  placeholder="E-mail" id="email-log">--}}
    {{--@if ($errors->has('email'))--}}
    {{--<span class="invalid-feedback" role="alert">--}}
    {{--<strong style="color: red;" > {{ $errors->first('email') }}</strong>--}}
    {{--</span>--}}
    {{--@endif--}}
    {{--</div>--}}

    {{--<button type="submit" class="btn-effect">Daxil ol</button>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</form>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</section>--}}
@endsection
