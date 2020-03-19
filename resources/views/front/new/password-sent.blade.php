@extends('layouts.front_main')

@section('content')
    <section class="resend-pw invoice content">
        <div class="container">
            <div class="row">
                @include('front.components.breadcrumb' ,['section' => 'forgot_password'])
                <div class="col-xs-12">
                    <div class="forget-body block col-xs-12">
                        <div class="row relative">
                            <div class="forget-left col-sm-6 col-xs-12">
                                <h1>Şifrəniz göndərildi</h1>
                                <p>Əgər şifrə yenilənməsi ilə bağlı e-mail almamısınızsa;</p>
                                <ul class="list-unstyled">
                                    <li>Spam qutusunu yoxlayın</li>
                                    <li>Daxil etdiyiniz e-mail adresinin doğruluğuna əmin olun</li>
                                    <li>Əgər hələ də e-mail almamısınızsa, təkrar yoxlayın</li>
                                </ul>
                                <a href="/password/reset" class="btn-send btn-effect">Yenidən göndər</a>
                            </div>
                            <div class="forget-right col-sm-6 col-xs-12 text-center">
                                <div class="forget-bg">
                                    <img src="{{ asset('front/new/img/forget-ch.png') }}" alt="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection