<header>
    <nav class="navbar navbar-default nav-top {{Auth::check() && Auth::user()->is_premium==1?"navbar-premium": ''}}">
        <div class="container">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('front.static.pages.userRules') }}">@lang('common.rules')</a></li>
                <li><a href="{{ route('front.static.pages.userQuestions') }}">@lang('common.questions')</a></li>
                <li><a href="{{ route('front.shop','tr') }}">@lang('common.shops')</a></li>
            </ul>
            @if( !Auth::check())
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#" data-toggle="modal" data-target=".bs-example-modal-sm">@lang('common.log')</a>
                        <div class="login modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
                            <div class="modal-dialog modal-sm" role="document">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('login') }}">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">
                                            <img src="{{ asset('front/new/img/close1.png') }}" alt="close" class="">
                                            <!--<img src="{{ asset('front/new/img/close-modal.png') }}" alt="close" class="mobile">-->
                                        </span>
                                        </button>
                                        <div class="row">
                                            <div class="login-left col-sm-6 col-xs-12">
                                                <h3>@lang('register.login')</h3>
                                                <div class="input-group border-radius">
                                                    <label>
                                                        <input name="email" required type="text"  class="form-control inputText" placeholder=" ">
                                                        <span>@lang('register.email') *</span>
                                                    </label>
                                                </div>
                                                <div class="input-group border-radius">
                                                    <label>
                                                        <input name="password" required type="password" required  class="form-control inputText" placeholder=" ">
                                                        <span>@lang('register.password')* </span>
                                                    </label>
                                                </div>
                                                <div class="save-password">
                                                    <label class="check-button">
                                                        <span>@lang('panel-errors.saveme')</span>
                                                        <input type="checkbox" >
                                                        <span class="checkmark"></span>
                                                    </label>
                                                    <a href="/{{Lang::getLocale()}}/password/reset">@lang('breadcrumb.forgot_password')</a>
                                                </div>
                                                <div class="btn-password">
                                                    <button type="submit" href="" class="btn-enter btn-effect">@lang('common.log')</button>
                                                    <a href="{{ route('register') }}" class="btn-log btn-effect">@lang('common.registration')</a>
                                                </div>
                                            </div>
                                            <div class="login-right col-sm-6">
                                                <img src="{{ asset('front/new/img/pop-back.png') }}" class="pop-bg img-responsive" alt="pop_back">
                                                <img src="{{ asset('front/new/img/popup.png') }}" alt="popup" class="pop-img">
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li><a href="{{ route('register') }}">@lang('common.registration')</a></li>
                </ul>
            @endif
            @if( Auth::check() )
                <ul class="nav navbar-nav navbar-right logined-menu">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">
                            @if(Auth::user()->is_premium==1)
                                <img src="{{ asset('front/new/img/diamond.png') }}"> Premium hesab |
                            @endif
                                {{Auth::user()->name}} {{Auth::user()->surname}}
                            <img src="{{ asset('front/new/img/chevron.png') }}" class="img-responsive"
                                                                     alt="arrow-bottom"></a>
                        <ul class="dropdown-menu">
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/">@lang('panel-errors.user_panel')</a></li>
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/foreign-address">@lang('panel-errors.foreign_addresses')</a></li>
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/orders">@lang('panel-errors.orders')</a></li>
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/invoices">@lang('panel-errors.invoices')</a></li>
{{--
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/track">@lang('panel-errors.track_order')</a></li>
--}}
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/balance">AZN @lang('panel-errors.balance')</a></li>
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/balanceTl">TL @lang('panel-errors.balance')</a></li>
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/courier">@lang('panel-errors.courier')</a></li>
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/questions">@lang('panel-errors.request')</a></li>
                            <li><a href="/{{Lang::getLocale()}}/site/user-panel#/settings">@lang('panel-errors.settings')</a></li>
                            @if(Auth::user()->corporate==1)
                                <li><a href="/{{Lang::getLocale()}}/site/user-panel#/clients">@lang('panel-errors.corporate_clients')</a></li>
                            @endif
                            <li><a href="/logout">@lang('panel-errors.logout')</a></li>
                        </ul>
                    </li>
                </ul>
            @endif
        </div>
    </nav>
    <nav class="navbar navbar-default nav-bottom {{ Auth::check() ? 'logined' : ''}}">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <img src="{{ asset('front/new/img/menu-mob.png') }}" alt="menu">
                </button>
                <a class="navbar-brand" href="{{ route('front.main') }}">
                    <img src="{{ asset('front/new/img/Limak-Logo.png') }}" class="img-responsive" alt="logo">
                </a>
                <ul class="mobile navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">AZE <img src="{{ asset('front/new/img/chevron.png') }}" class="img-responsive"
                                                          alt="arrow-bottom"></a>
                        <ul class="dropdown-menu">
                            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                @if($localeCode != 'en')
                                <li>
                                    <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                        {{ $properties['native'] }}
                                    </a>
                                </li>
                                @endif

                            @endforeach
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="collapse-menu">
                <ul class="nav navbar-nav navbar-right web">
                    <li class="tel">
                        <a href="tel:*9595">*9595 <span>@lang('common.support_center')</span></a>
                    </li>
                    <li>
                        @if(Auth::check())
                            <a class="btn-effect blue" href="/{{Lang::getLocale()}}/site/user-panel#/invoice">@lang('invoice/index.upload-invoice')</a>
                        @endif
                        <a class="btn-effect" href="/{{Lang::getLocale()}}/site/user-panel#/order">@lang('home.order-now')</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-left">
                    <li class="top tel mobile">
                        <a href="tel:*9595">*9595 <span>DƏSTƏK <br> XƏTTİ</span></a>
                        <button class="close-menu"><img src="{{ asset('front/new/img/close-menu.png') }}" alt="lose"></button>
                    </li>
                    <li class="dropdown web">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                           aria-expanded="false">{{ str_replace('_', '-', app()->getLocale()) }}
                            <!--<img src="{{ asset('front/new/img/chevron.png') }}" class="img-responsive" alt="arrow-bottom">--></a>
                        <ul class="dropdown-menu">
                        @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                        @if($localeCode != 'en')
                        <li>
                            <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                {{ $localeCode }}
                            </a>
                        </li>
                                @endif
                        @endforeach
                    </ul>
                    </li>

                    <li class="mobile">
                        @if(Auth::check())
                        <a href="/{{Lang::getLocale()}}/site/user-panel#/invoice" class="btn-effect blue">@lang('invoice/index.upload-invoice')</a>
                        @endif
                        <a href="/{{Lang::getLocale()}}/site/user-panel#/order" class="btn-effect">@lang('home.order-now')</a>
                    </li>

                    <li class="mobile">
                    </li>
                    <li ><a href="{{ route('front.country') }}">@lang('country/index.countries')</a></li>
                    <li><a href="{{ route('front.calculator') }}">@lang('home.calculator')</a></li>
                    <li><a href="/{{Lang::getLocale()}}/about-us">@lang('home.about')</a></li>
                    <li><a href="{{ route('front.contact.index') }}">@lang('common.contact')</a></li>
                </ul>
                <ul class="mobile list-time">
                    <li>@lang('home.iv')</li>
                    <li><b>@lang('home.order_service'):</b></li>
                    <li>@lang('home.service_time')</li>
                    <li><b>@lang('home.customer_service'):</b></li>
                    <li>@lang('home.service_time')</li>
                    <li><b>@lang('home.depot'):</b></li>
                    <li>@lang('home.depot_time')</li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="overlay-menu"></div>
</header>
