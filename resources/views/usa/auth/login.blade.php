@extends('layouts.tr_main')
@section('style')
    <style>
        .is-invalid {
            color: red;
        }
    </style>
    @endsection
@section('content')
<section class="page-block login">
    <div class="container">
        <div class="page-head">
            <img src="{{asset('front/img/qeydiyyat-page.png')}}" alt="qeydiyyat">
            <h1>LOGIN</h1>
        </div>
        <div class="block">
            <form method="POST" action="/login">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3 col-sm-10 col-sm-offset-1 col-xs-12">
                        <div class="login-block">
                            <div class="input-group">
                                <label for="email-log">E-mail</label>
                                <input name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" required  placeholder="E-mail" id="email-log">
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong style="color: red;" > {{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="input-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Şifrə" id="password">
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong style="color: red;" > {{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <button type="submit" class="btn-effect">Login</button>
                            <div class="checkbox">
                                <label>
                                    <input id="checkbox1" dirname="check" type="checkbox" {{ old('remember') ? 'checked' : '' }} name="remember"><span></span> Məni xatırla
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
