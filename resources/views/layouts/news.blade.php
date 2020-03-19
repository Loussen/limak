<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <?PHP
    $name = isset($news->name) ? $news->name : null;
    $photo = isset($news->file) ? url(Storage::url($news->file)) : null;
    $description = isset($news->description) ? $news->description : null;
    $keywords = isset($news->keywords) ? $news->keywords : null;
    $url = route('newsIn', ['id' => $news->id, 'slug' => Illuminate\Support\Str::slug($news->name, '-', str_replace('_', '-', app()->getLocale()))]);
    ?>
    <meta charset="UTF-8">
    <title>{{ config('app.name', 'LimarK') }}</title>
    <meta property="fb:page_id" content="603613336749567" />
    <meta name="keywords" content="{{$keywords}}">
    <meta name="author" content="Limak.az">
    <meta property="og:type" content="article">
    <meta property="og:locale" content="az_AZ">
    <meta property="og:site_name" content="Limak.az">
    <meta property="og:url" content="{{$url}}">
    <meta property="og:title" content="​​​​​​​{{$name}}">
    <meta property="og:description" content="{{$description}}">
    <meta property="og:image" content="{{$photo}}">
    <meta property="article:section" content="Yeniliklər və elanlar">
    <meta itemprop="articleSection" content="Yeniliklər və elanlar">
    <meta itemprop="description" content="{{$description}}">
    <meta itemprop="image" content="{{$photo}}">
    <meta name="description" content="{{$description}}">
    <link rel="canonical" href="https://limak.az">
    <link rel="alternate" hreflang="az" href="https://limak.az/az">
    <link rel="alternate" hreflang="ru" href="https://limak.az/ru">
    <link rel="sitemap" type="application/xml" title="Sitemap" href="https://www.limak.az/sitemap.xml" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker.css" rel="stylesheet"
          type="text/css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <link rel="stylesheet" type="text/css" href="{{asset('front/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('front/css/style.css?v=').time()}}">
    <meta name="google-site-verification" content="1JJjP7SRsh2xsh_VzXO8g6DzUsXW3TpwezOJ4ZmErB0" />
    <style>
		#body {
			clear: both;
			position: relative;
			height: 40px;
			margin-top: -40px;
		}
    </style>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-133344274-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-133344274-1');
    </script>
    <!-- Facebook Pixel Code -->
    <script>
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window,document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '2230960543785408');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1"
             src="https://www.facebook.com/tr?id=2230960543785408&ev=PageView
&noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
    @yield('styles')
</head>
<body>
<div class="application-loading-container" id="app-global-offset-sync">
    {{--<img src="{{asset('front/img/loader.svg')}}" alt="">--}}
</div>
    @include('.front.components.header')

    @yield('content')

    @include('.front.components.footer')
    <section id="app-chat">
        <chat-component></chat-component>
    </section>
    <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content first">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('front/img/close.png')}}"
                                                                                                     alt="close"></button>
                    <h4 class="modal-title" id="myModalLabel">@lang('common.login')</h4>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('login') }}" >
                        <div class="input-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" value="{{ old('email') }}" required placeholder="E-mail" id="email">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="input-group">
                            <label for="pass">@lang('register.password')</label>
                            <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="Şifrə" id="pass">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                        <button type="submit" class="btn-effect">@lang('common.log')</button>
                        <div class="checkbox">
                            <label>
                                <input name="remember" id="remember" dirname="check" {{ old('remember') ? 'checked' : '' }} type="checkbox" name="checkbox"><span></span> @lang('common.rememberMe')
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('password.request') }}" class="shifre">@lang('common.forgotThePassword')?</a>
                    <a href="/register">@lang('register.register')</a>
                </div>
            </div>
            <div class="modal-content sec" style="display: none">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('front/img/close.png')}}"
                                                                                                     alt="close"></button>
                    <h4 class="modal-title" id="myModalLabel1">ŞƏXSİ KABİNETƏ GİRİŞ</h4>
                </div>
                <div class="modal-body">
                    <div class="input-group">
                        <label for="email1">E-mail</label>
                        <input type="email" name="email1" class="form-control" placeholder="E-mail" id="email1">
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="shifre">Geri qayıt</a>
                    <a href="#">@lang('register.register')</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade diqqet" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content first">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('front/img/close.png')}}"
                                                                                                     alt="close"></button>
                    <h4 class="modal-title" id="myModalLabe2">DİQQƏT!</h4>
                </div>
                <div class="modal-body">
                    <h4> Hazırda sifarişlərin çoxluğu ilə
                        əlaqədar olaraq sifarişlərin qəbulu
                        müvəqqəti dayandırılıb.</h4>
                    <a href="#" class="btn-effect" data-dismiss="modal">Bağla</a>
                </div>
                <div class="modal-footer">
                    <a href="#" class="shifre">Şifrəni unutmusunuz?</a>
                    <a href="#">@lang('register.register')</a>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade link-url work-time" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('front/img/Forma1.png')}}"
                                                                                                     alt="close"></button>
                </div>
                <div class="modal-body">
                    <img src="{{asset('front/img/time-icon.png')}}" alt="time-icon">
                    <h4>İş vaxtı bitmişdir.</h4>
                    <p> Zəhmət olmasa saat 09:00-dan sonra
                        müraciət edin.</p>
                    <a href="#" class="btn-effect" data-dismiss="modal">Bağla</a>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js" type="text/javascript" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>

    <script type="text/javascript" src="{{asset('front/js/owl.carousel.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/js/main.js?v=').time()}}"></script>
    <script type="text/javascript" src="{{asset('front/js/common.js')}}"></script>
    @yield('scripts')
    {{--<script type="application/javascript" src="{{asset('js/vue/public-chat-app/app.public.js')}}?v={{uniqid()}}" ></script>--}}
<script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="1602646a-c3c7-4814-9fe6-76807bc355ac";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
<script>
    setTimeout(() => {
        $('.application-loading-container').fadeOut('fast');
        $('html, body').animate({
            scrollTop: $('#app-global-offset-sync').offset().top
        }, 1000, 'linear');
    }, 200)
</script>
</body>
</html>
