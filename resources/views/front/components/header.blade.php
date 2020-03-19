<style>
    .px-0{
        padding-left: 0;
        padding-right: 0;
    }
    .navbar-nav.top li{
        text-transform: uppercase;
    }
    .navbar-brand{
        margin-left: 15px!important;
    }
    .collapse.navbar-collapse{
        min-width: 106%;
    }
</style>
<header>
    <nav class="navbar navbar-default nav-top">
        <div class="container">
            <ul class="mobile-tel">
                <li class="tel"><a href="tel:*9595"><i class="fa fa-phone"></i> *9595</a></li>
            </ul>
            <ul class="nav navbar-nav top">
                @php($staticPage = staticPage('how', Lang::getLocale()))
                @if(!empty($staticPage))
                    <li><a href="/{{Lang::getLocale()}}/{{$staticPage->slug}}">{{$staticPage->name}}</a></li>
                @endif
                @php($staticPage = staticPage('order', Lang::getLocale()))
                @if(!empty($staticPage))
                    <li><a href="/{{Lang::getLocale()}}/{{$staticPage->slug}}">{{$staticPage->name}}</a></li>
                @endif
                <a style="display: none" class="btn btn-info" href="{{route('notify')}}">Notification check</a>
                {{--<li><a href="{{url('/')}}">TARİFLƏR</a></li>--}}
                <li><a href="/countries">@lang('country/index.countries')</a></li>
                <li><a href="/">@lang('home.calculator')</a></li>
                {{--<li><a href="kuryer.html">KURYER</a></li>--}}
                <li><a href="/">@lang('common.shops')</a></li>
                <li><a href="/">@lang('common.contact')</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="tel"><a href="tel:*9595"><i class="fa fa-phone"></i> *9595</a></li>
                <li class="social-icons"><a href="#"><i class="fa fa-facebook"></i></a></li>
                <li class="social-icons"><a href="#"><i class="fa fa-instagram"></i></a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                       aria-expanded="false">{{ str_replace('_', '-', app()->getLocale()) }} <img src="{{asset('front/img/arrow-bottom-w.png')}}" class="img-responsive"
                                                                                                  alt="arrow-bottom"><img src="{{asset('front/img/arrow-bottom-b.png')}}"
                                                                                                                          class="img-responsive"
                                                                                                                          alt="arrow-bottom-black"
                                                                                                                          style="display: none"></a>
                    <ul class="dropdown-menu">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            <li>
                                <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                    {{ $properties['native'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <nav class="navbar navbar-default nav-bottom {{Auth::check()? 'logined':''}}">
        <div class="container">
            <div class="navbar-header">
                @if(!Auth::check())
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <img src="{{asset('front/img/Menu.png')}}" alt="menu">
                        <img src="{{asset('front/img/closephone.png')}}" alt="close" style="display: none">
                    </button>
                @endif
                <div class="open-header">
                    @if(!Auth::check())
                    <button type="button" class="btn" data-toggle="modal"
                            data-target=".bs-example-modal-sm"><img src="{{asset('front/img/user.png')}}" alt="user">
                    </button>
                    <a href="/register" class="registration"><img src="{{asset('front/img/registration.png')}}" alt="registration"></a>
                    <a class="navbar-brand" href="{{route('main')}}"><img src="{{asset('front/img/Limak-Logo.png')}}" alt="logo"></a>
                        @else
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <img src="{{asset('front/img/Menu.png')}}" alt="menu">
                            <img src="{{asset('front/img/closephone.png')}}" alt="close" style="display: none">
                        </button>
                        <ul class="logined-user">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                   aria-haspopup="true"
                                   aria-expanded="false"><img src="{{asset('front/img/user.png')}}" class="img-responsive"
                                                              alt="user"></a>
                                <ul class="dropdown-menu">
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/foreign-address">@lang('panel-errors.foreign_addresses')</a></li>
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/orders">@lang('panel-errors.orders')</a></li>
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/balance">AZN @lang('panel-errors.balance')</a></li>
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/balanceTl">TL @lang('panel-errors.balance')</a></li>
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/track">@lang('panel-errors.track_order')</a></li>
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/messenger">@lang('panel-errors.messages')</a></li>
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/invoices">@lang('panel-errors.invoices')</a></li>
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/settings">@lang('panel-errors.settings')</a></li>
                                    <li><a href="/{{Lang::getLocale()}}/user-panel#/courier">@lang('panel-errors.kuryer')</a></li>
                                    <li><a href="/logout">@lang('common.logout')</a></li>
                                </ul>
                            </li>
                        </ul>
                        <a href="#" class="message"><img src="{{asset('front/img/message.png')}}" alt="message"></a>
                        <a class="navbar-brand" href="/"><img src="{{asset('front/img/Limak-Logo.png')}}" alt="logo"></a>
                        @endif
                </div>
                <div class="open-header" style="display: none">
                    <ul class="social-icons">
                        <li class="social-icons"><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li class="social-icons"><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li class="social-icons"><a href="#"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
            {{--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">--}}

                    {{--<ul class="nav navbar-nav navbar-right registration-buttons">--}}
                        {{--<li class="xs"><a href="/register" class="btn-effect">Qeydiyyat</a></li>--}}
                        {{--<li class="xs">--}}
                            {{--<button type="button" class="btn btn-effect" data-toggle="modal"--}}
                                    {{--data-target=".bs-example-modal-sm">@lang('common.cabinet')--}}
                            {{--</button>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                    {{--<ul class="nav navbar-nav navbar-right login-list">--}}
                        {{--<li><a href="#"><img src="{{asset('front/img/message.png')}}" alt="message"><span>10</span></a></li>--}}
                        {{--<li class="dropdown">--}}
                            {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"--}}
                               {{--aria-expanded="false"><img src="{{asset('front/img/user.png')}}" class="img-responsive"--}}
                                                          {{--alt="user"> {{Auth::user()->name}} {{Auth::user()->surname}}<img src="{{asset('front/img/arrow-bottom-b.png')}}"--}}
                                                                                                                           {{--class="img-responsive"--}}
                                                                                                                           {{--alt="arrow-bottom"></a>--}}
                            {{--<ul class="dropdown-menu">--}}
                                {{--<li><a href="/{{Lang::getLocale()}}/user-panel#/foreign-address">@lang('panel-errors.foreign_addresses')</a></li>--}}
                                {{--<li><a href="/{{Lang::getLocale()}}/user-panel#/orders">@lang('panel-errors.orders')</a></li>--}}
                                {{--<li><a href="chatdirilma-balansi.html">@lang('panel-errors.balance')</a></li>--}}
                                {{--<li><a href="kuryer-kabinet.html">@lang('panel-errors.kuryer')</a></li>--}}
                                {{--<li><a href="/{{Lang::getLocale()}}/user-panel#/track">@lang('panel-errors.track_order')</a></li>--}}
                                {{--<li><a href="mesajlar.html">Mesajlar</a></li>--}}
                                {{--<li><a href="/{{Lang::getLocale()}}/user-panel#/invoices">@lang('panel-errors.invoices')</a></li>--}}
                                {{--<li><a href="/{{Lang::getLocale()}}/user-panel#/settings">@lang('panel-errors.settings')</a></li>--}}
                                {{--<li><a href="/logout">@lang('common.logout')</a></li>--}}
                            {{--</ul>--}}
                        {{--</li>--}}
                    {{--</ul>--}}

                {{--<ul class="nav navbar-nav navbar-right mobile">--}}
                    {{--<li><a href="nece-sifarish-etmeli.html">NECƏ SİFARİŞ ETMƏLİ?</a></li>--}}
                    {{--<li><a href="tarifler.html">TARİFLƏR</a></li>--}}
                    {{--<li><a href="olkeler.html">ÖLKƏLƏR</a></li>--}}
                    {{--<li><a href="kalkulyator.html">KALKULYATOR</a></li>--}}
                    {{--<li><a href="kuryer.html">KURYER</a></li>--}}
                    {{--<li><a href="magazalar.html">MAĞAZALAR</a></li>--}}
                    {{--<li><a href="elaqe.html">ƏLAQƏ</a></li>--}}
                {{--</ul>--}}
                {{--<ul class="nav navbar-nav navbar-right web">--}}
                    {{--<li class="lg">--}}
                        {{--<div class="col-md-2 px-0">--}}
                            {{--<img src="{{asset('front/img/time.png')}}" style="padding-right: 3px" alt="time">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-10 px-0">--}}
                            {{--<p>İş vaxtı: Bazar ertəsi-şənbə</p>--}}
                            {{--<p>Sifariş et xidməti: 10.00-00.00 </p>--}}
                        {{--</div>--}}
                    {{--</li><li class="lg">--}}
                        {{--<img src="{{asset('front/img/tel.png')}}" alt="tel">--}}
                        {{--<div class="col-md-2 px-0">--}}
                            {{--<img src="{{asset('front/img/time.png')}}" style="padding-right: 3px" alt="time">--}}
                        {{--</div>--}}
                        {{--<div class="col-md-10 px-0">--}}
                            {{--<p>Müştəri xidməti: 10:00 - 00:00</p>--}}
                            {{--<p>Anbar: 10:00-22:00</p>--}}
                        {{--</div>--}}
                    {{--</li>--}}
                    {{--<li><img src="{{asset('front/img/location.png')}}" alt="location">--}}
                        {{--<p>Səbail rayonu, Lermantov küçəsi 113/117</p></li>--}}
                {{--</ul>--}}
                {{--<ul class="contact">--}}
                    {{--<li class="inline"><p><i class="fa fa-calendar"></i><span>İş vaxtı: 1 - 6 günlər</span> <br> <i--}}
                                {{--class="fa fa-clock-o"></i><span>Saat:--}}
                        {{--09:00 - 19:00</span></p></li>--}}
                    {{--<li class="inline"><a href="tel:+994 55 123-45-67"><i class="fa fa-phone"></i><span>+994 55 123-45-67</span>--}}
                        {{--</a><br> <a href="tel:+994 70 123-45-67"><i class="fa fa-mobile"></i><span>+994 70 123-45-67</span></a>--}}
                    {{--</li>--}}
                    {{--<li><i class="fa fa-map-marker"></i><span>Səbail rayonu, Lermantov küçəsi 113/117</span></li>--}}
                {{--</ul>--}}
            {{--</div>--}}



            @if(Auth::check())
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                    <ul class="nav navbar-nav navbar-right login-list">
                        <li><a href="#"><img src="{{asset('front/img/message.png')}}" alt="message"></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                               aria-expanded="false"><img src="{{asset('front/img/user.png')}}" class="img-responsive"
                                                          alt="user"> {{Auth::user()->name}} {{Auth::user()->surname}}<img src="{{asset('front/img/arrow-bottom-b.png')}}"
                                                                                                                           class="img-responsive"
                                                                                                                           alt="arrow-bottom"></a>
                            <ul class="dropdown-menu">
                                <li><a href="/{{Lang::getLocale()}}/user-panel#/foreign-address">@lang('panel-errors.foreign_addresses')</a></li>
                                <li><a href="/{{Lang::getLocale()}}/user-panel#/orders">@lang('panel-errors.orders')</a></li>
                                <li><a href="/{{Lang::getLocale()}}/user-panel#/balance">@lang('panel-errors.balance')</a></li>
                                <li><a href="/{{Lang::getLocale()}}/user-panel#/track">@lang('panel-errors.track_order')</a></li>
                                <li><a href="/{{Lang::getLocale()}}/user-panel#/messenger">@lang('panel-errors.messages')</a></li>
                                <li><a href="/{{Lang::getLocale()}}/user-panel#/invoices">@lang('panel-errors.invoices')</a></li>
                                <li><a href="/{{Lang::getLocale()}}/user-panel#/settings">@lang('panel-errors.settings')</a></li>
                                <li><a href="/{{Lang::getLocale()}}/user-panel#/courier">@lang('panel-errors.kuryer')</a></li>
                                <li><a href="/logout">@lang('common.logout')</a></li>
                            </ul>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right mobile">
                        <li><a href="/">@lang('country/index.countries')</a></li>
                        <li><a href="/">@lang('home.calculator')</a></li>
                        <li><a href="/">@lang('common.shops')</a></li>
                        <li><a href="/">@lang('common.contact')</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right web">
                        <li class="lg">
                            <div class="col-md-2 px-0">
                                <img src="{{asset('front/img/time.png')}}" style="padding-right: 3px" alt="time">
                            </div>
                            <div class="col-md-10 px-0">
                                <p>@lang('home.iv')</p>
                                <p>@lang('home.se')</p>
                            </div>
                        </li><li class="lg">
                            {{--<img src="{{asset('front/img/tel.png')}}" alt="tel">--}}
                            <div class="col-md-2 px-0">
                                <img src="{{asset('front/img/time.png')}}" style="padding-right: 3px" alt="time">
                            </div>
                            <div class="col-md-10 px-0">
                                <p>@lang('home.mx')</p>
                                <p>@lang('home.a')</p>
                            </div>
                        </li>
                        <li><img src="{{asset('front/img/location.png')}}" alt="location">
                            <p>@lang('home.unvan')</p></li>
                    </ul>
                    <ul class="contact">
                        <li class="inline"><p><i class="fa fa-calendar"></i>
                                <span>@lang('home.iv')</span> <br>
                                <i class="fa fa-clock-o"></i>
                                <span>@lang('home.se')</span></p>
                        </li>
                        <li class="inline"><p><i class="fa fa-calendar"></i>
                                <span>@lang('home.mx')</span> <br>
                                <i class="fa fa-clock-o"></i>
                                <span>@lang('home.a')</span></p>
                        </li>
                        <li class="inline">
                            <a href="tel:*9595">
                                <i class="fa fa-phone"></i>
                                <span>*9595</span>
                            </a>
                            <br>
                            <a href="tel:+994124883578">
                                <i class="fa fa-phone"></i>
                                <span>+994 12 488 3578</span>
                            </a>
                        </li>
                        <li><i class="fa fa-map-marker"></i><span>@lang('home.unvan')</span></li>
                    </ul>
                </div>
                @else
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                    <ul class="nav navbar-nav navbar-right registration-buttons">
                        <li><a href="/register" class="btn-effect">@lang('register.register')</a></li>
                        <li>
                            <button type="button" class="btn btn-effect" data-toggle="modal"
                                    data-target=".bs-example-modal-sm">@lang('common.cabinet')
                            </button>
                        </li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right mobile">
                        <li><a href="{{route('country')}}">@lang('country/index.countries')</a></li>
                        <li><a href="{{route('calculator')}}">@lang('home.calculator')</a></li>
                        <li><a href="{{route('shop')}}">@lang('common.shops')</a></li>
                        <li><a href="{{route('contact.index')}}">@lang('common.contact')</a></li>
                    </ul>
                    <ul class="nav navbar-nav navbar-right web">
                        <li class="lg">
                            <div class="col-md-2 px-0">
                                <img src="{{asset('front/img/time.png')}}" style="padding-right: 3px" alt="time">
                            </div>
                            <div class="col-md-10 px-0">
                                <p>@lang('home.iv')</p>
                                <p>@lang('home.se')</p>
                            </div>
                        </li><li class="lg">
                            {{--<img src="{{asset('front/img/tel.png')}}" alt="tel">--}}
                            <div class="col-md-2 px-0">
                                <img src="{{asset('front/img/time.png')}}" style="padding-right: 3px" alt="time">
                            </div>
                            <div class="col-md-10 px-0">
                                <p>@lang('home.mx')</p>
                                <p>@lang('home.a')</p>
                            </div>
                        </li>
                        <li><img src="{{asset('front/img/location.png')}}" alt="location">
                            <p>@lang('home.unvan')</p></li>
                    </ul>
                    <ul class="contact">
                        <li class="inline"><p><i class="fa fa-calendar"></i>
                                <span>@lang('home.iv')</span> <br>
                                <i class="fa fa-clock-o"></i>
                                <span>@lang('home.se')</span></p>
                        </li>
                        <li class="inline"><p><i class="fa fa-calendar"></i>
                                <span>@lang('home.mx')</span> <br>
                                <i class="fa fa-clock-o"></i>
                                <span>@lang('home.a')</span></p>
                        </li>
                        <li class="inline">
                            <a href="tel:*9595">
                                <i class="fa fa-phone"></i>
                                <span>*9595</span>
                            </a>
                            <br>
                            <a href="tel:+994124883578">
                                <i class="fa fa-phone"></i>
                                <span>+994 12 488 3578</span>
                            </a>
                        </li>
                        <li><i class="fa fa-map-marker"></i><span>@lang('home.unvan')</span></li>
                    </ul>
                </div>
            @endif

        </div>
    </nav>
</header>
