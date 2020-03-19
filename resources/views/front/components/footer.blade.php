<footer>
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-2 col-xs-2">
                    <img src="{{asset('front/img/limak-footer-logo.png')}}" alt="logogray">
                </div>
                <div class="col-md-8 col-sm-10 col-xs-10">
                    <div class="cards">
                        {{--<img src="{{asset('front/img/logo-emanat.png')}}" alt="emanat">--}}
                        <img src="{{asset('front/img/master-card-logo.png')}}" alt="mastercard">
                        <img src="{{asset('front/img/visa-logo.png')}}" alt="visa">
                    </div>
                    <ul class="social-icons">
                        <li class="social-icons"><a href="https://www.facebook.com/Limakaz"><i class="fa fa-facebook"></i></a></li>
                        {{--<li class="social-icons"><a href="#"><i class="fa fa-twitter"></i></a></li>--}}
                        <li class="social-icons"><a href="https://www.instagram.com/limak.az/"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-middle">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <p>@lang('footer.mini-about')</p>
                </div>
                <div class="col-md-1 col-sm-4 col-xs-6">
                    <ul>
                        <li><a href="/">@lang('common.shops') </a></li>
                        {{--<li><a href="{{route('country')}}">Tariflər </a></li>--}}
                        <li><a href="{/">@lang('country/index.countries') </a></li>
                    </ul>
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6">
                    <ul>
                        <li><a href="/">@lang('home.faq')</a></li>
                        <li><a href="/">@lang('home.calculator') </a></li>
                        <li><a href="/">@lang('common.contact') </a></li>
                    </ul>
                </div>
                @php($staticPages = staticPages(Lang::getLocale(), 3))
                @if(!empty($staticPages) && count($staticPages) > 0)
                    @foreach($staticPages as $pageArr)
                        <div class="col-md-2 col-sm-4 col-xs-6">
                            <ul>
                                @foreach($pageArr as $page)
                                <li><a href="/{{Lang::getLocale()}}/{{$page->slug}}">{{$page->name}}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @endif
                <div class="col-md-2 col-sm-12 col-xs-12">
                    <ul class="contact">
                        <li><a href="tel:*9595"><i class="fa fa-phone"></i><span>*9595</span>
                            </a>
                        </li>
                        <li><a href="tel:+994125058797"><i class="fa fa-phone"></i><span>+994125058797</span>
                            </a>
                        </li>
                        <li><a href="tel:+994508249595"><i class="fa fa-phone"></i><span>+994508249595</span>
                            </a>
                        </li>
                        <li><i class="fa fa-map-marker"></i><span>@lang('home.unvan')</span></li>
                    </ul>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="cards">
                        {{--<img src="{{asset('front/img/logo-emanat.png')}}" alt="emanat">--}}
                        <img src="{{asset('front/img/master-card-logo.png')}}" alt="mastercard">
                        <img src="{{asset('front/img/visa-logo.png')}}" alt="visa">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <span> © 2018  Limak.az   |   @lang('footer.copyright')</span>
            <div class="footer-links">
                @php($staticPage = staticPage('deliverypolicy', Lang::getLocale()))
                @if(!empty($staticPage))
                    <a href="/{{Lang::getLocale()}}/{{$staticPage->slug}}">{{$staticPage->name}}</a>
                @endif
                @php($staticPage = staticPage('privacypolicy', Lang::getLocale()))
                @if(!empty($staticPage))
                    <a href="/{{Lang::getLocale()}}/{{$staticPage->slug}}">{{$staticPage->name}}</a>
                @endif
            </div>
        </div>
    </div>
</footer>
