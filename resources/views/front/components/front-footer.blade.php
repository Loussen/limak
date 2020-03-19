<footer id="main-footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <img src="{{ asset('front/new/img/logo-white.png') }}" class="img-responsive" alt="logo-white">
                    <p>@lang('footer.mini-about')</p>
                    <span class="follow-social">@lang('footer.follow_us')</span>
                    <ul class="social-icons">
                        <li><a target="_blank" href="https://www.facebook.com/Limakaz/"><i class="fa fa-facebook"></i></a></li>
                        <li><a target="_blank" href="https://www.instagram.com/limak.az/"><img src="{{ asset('front/new/img/instagram.png') }}" alt="instagram"></a></li>
                        <li><a target="_blank" href="https://twitter.com/limak_az"><img src="{{ asset('front/new/img/twitter.png') }}" alt="twitter"></a></li>
                    </ul>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <h4>@lang('footer.sections')</h4>
                    <ul class="menu-list seperated">
                        <li ><a href="{{route('front.country')}}">@lang('country/index.countries')</a></li>
                        <li><a href="{{route('front.calculator')}}">@lang('home.calculator')</a></li>
                        <li><a href="{{route('front.shop','tr')}}">@lang('common.shops')</a></li>
                        <li><a href="/{{Lang::getLocale()}}/about-us">@lang('home.about')</a></li>
                        <li><a href="/{{Lang::getLocale()}}/gizlilik-sertleri">@lang('home.privacy')</a></li>
                        {{--<li><a href="#">Suallar</a></li>--}}
                        <li><a href="{{route('front.contact.index')}}">@lang('common.contact')</a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-12">
                    {{--<h4>Qaydalar</h4>--}}
                    {{--<ul class="menu-list">--}}
                        {{--<li>--}}
                            {{--<a href="#">Daşınma qaydaları</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#">Sifariş qaydaları</a>--}}
                        {{--</li>--}}
                        {{--<li>--}}
                            {{--<a href="#">İstifadəçi qaydaları</a>--}}
                        {{--</li>--}}
                    {{--</ul>--}}
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <ul class="text-right">
                        <li class="tel">
                            <a href="tel:*9595">*9595 <span>@lang('common.support_center')</span></a>
                        </li>
                        <li class="tel num">
                            <a href="tel:+99450 824 9595">+99450 824 9595</a>
                        </li>
                        <li>@lang('common.address') @lang('common.city_country')</li>
                        <li><span class="follow-social">@lang('footer.soon')</span></li>
                        <li>
                            <a href="https://play.google.com/store/apps/details?id=az.limak" target="_blank"><img src="{{ asset('front/new/img/google-play.png') }}" alt="google-play"></a>
                            <a href="#"><img src="{{ asset('front/new/img/appstore.png') }}" alt="app-store"></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <span>© 2018 Limak.az | @lang('footer.copyright')</span>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 text-right">
                    <img src="{{ asset('front/new/img/visa-logo.png')  }}" alt="visa" class="img-responsive">
                    <img src="{{ asset('front/new/img/master-card-logo.png') }}" alt="master-card" class="img-responsive">
                </div>
            </div>
        </div>
    </div>
</footer>