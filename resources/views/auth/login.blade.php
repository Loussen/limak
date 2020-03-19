@extends('layouts.front_main')
@section('style')
    <style>
        .is-invalid {
            color: red;
        }
    </style>
    @endsection
@section('content')
<section class="sign-in invoice content">
    <div class="container">
        @include('front.components.breadcrumb' ,['section' => 'login'])
        <div class="block">
            <form method="POST" action="{{ route('login') }}">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12">
                        <div class="login-block">
                            <div class="input-group border-radius">
                                <label for="email-log">@lang('register.email')</label>
                                <input type="email" name="email" class="form-control inputText {{ $errors->has('email') ? ' error-message' : '' }}" value="{{ old('email') }}" required  placeholder="E-mail" id="email-log">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong style="color: red;" > {{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="input-group border-radius">
                                <label for="password">@lang('panel-errors.password')</label>
                                <input type="password" name="password" class="form-control inputText {{ $errors->has('password') ? ' error-message' : '' }}" placeholder="Şifrə" id="password">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong style="color: red;" > {{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button type="submit" class="btn-effect">@lang('common.log')</button>
                            <div class="checkbox">
                                <label>
                                    <input id="checkbox1" dirname="check" type="checkbox" {{ old('remember') ? 'checked' : '' }} name="remember"><span></span>
                                    @lang('common.rememberMe')
                                </label>
                            </div>
                        </div>
                        <div class="login-footer">
                            <a href="{{ route('password.request') }}" class="shifre">@lang('common.forgotThePassword')?</a>
                            <a href="/register">@lang('register.register')</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
