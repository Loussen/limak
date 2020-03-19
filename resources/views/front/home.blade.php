@extends('layouts.front')
@section('content')
    <section class="main-slider">
        <div class="owl-carousel owl-theme">
            @foreach($sliders as $slider)
                <div>
                    <div class="owl-text">
                        {{--<h1 class="owl-title">{{$slider->name}}</h1>--}}
                        <a href="/{{App::getLocale()}}/order/link/insert" class="btn-effect">@lang('home.order-now')</a>
                    </div>
                    <picture>
                        <source media="(max-width: 425px)" srcset="{{url(Storage::url($slider->mobile_file))}}" class="img-responsive">
                        <img src="{{url(Storage::url($slider->file))}}" class="img-responsive" alt="slide-{{$slider->id}}">
                    </picture>
                    <a href="/{{App::getLocale()}}/order/link/insert" class="btn-effect mobile-button">@lang('home.order-now')</a>
                </div>
            @endforeach
        </div>
    </section>
    <section class="advertise-block">
        <div class="container">
            <div class="section-title">
                <h3>@lang('home.announcements')</h3>
            </div>
            <div class="owl-carousel owl-theme">
                @foreach($news as $item)
                    <div class="item">
                        <div class="thumbnail">
                            <div class="thumbnail-header">
                                <img src="{{url(Storage::url($item->file))}}" alt="news-01">
                            </div>
                            <div class="caption">
                                <h4><a href="{{route('newsIn', ['id' => $item->id, 'slug' => Illuminate\Support\Str::slug($item->name, '-', str_replace('_', '-', app()->getLocale()))])}}">{{$item->name}}</a></h4>
                                <p>
                                    <span><img src="{{asset('front/img/calendar.png')}}" alt="calendar">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('Y.m.d')}}</span>
                                    <a href="{{route('newsIn', ['id' => $item->id, 'slug' => Illuminate\Support\Str::slug($item->name, '-', str_replace('_', '-', app()->getLocale()))])}}">@lang('home.more')<img src="{{asset('front/img/more-arrow-right.png')}}" alt="arrow-right"></a>
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <section class="work">
        <div class="container">
            <div class="section-title">
                <h3>@lang('home.activities')</h3>
            </div>
            <div class="block">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="block-inner">
                            <img src="{{asset('front/img/box.png')}}" class="img-responsive" alt="box">
                            <p class="head">2 @lang('home.times')</p>
                            <p>@lang('home.delivery')</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="block-inner">
                            <img src="{{asset('front/img/money.png')}}" class="img-responsive" alt="box">
                            <p class="head"><sup>$</sup>2.5 {{--<small>(<sup>&#8380;</sup>6)</small>--}} - <sup>$</sup>5.5 {{--<small>(<sup>&#8380;</sup>9.4)</small>--}}</p>
                            <p>@lang('home.min-tariffs')</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="block-inner">
                            <img src="{{asset('front/img/courier.png')}}" class="img-responsive" alt="box">
                            <p class="head">@lang('home.extremely')</p>
                            <p>@lang('home.courier')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="count">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="block">
                        <h3><img src="{{asset('front/img/calculator-h.png')}}" alt="calculator">@lang('home.calculator')</h3>
                        <form action="...">
                            <div class="row">
                                <div class="left-side col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="olke">@lang('home.country')</label>
                                        <select class="form-control selectpicker" name="olke" id="country">
                                            <option value="tr">@lang('common.turkey')</option>{{--
                                            <option value="usa">Amerika</option>
                                            <option value="ch">Çin</option>--}}
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label for="length">@lang('home.length')</label>
                                        <input type="text" name="length" class="form-control" id="length">
                                        <div class="input-group-btn">
                                            <select class="selectpicker form-control" id="length-type">
                                                <option selected value="sm">@lang('home.sm')</option>
                                                <option value="m">@lang('home.m')</option>
                                                <option value="dm">@lang('home.dm')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="height">@lang('home.height')</label>
                                        <input type="text" class="form-control" name="height" id="height">
                                        <div class="input-group-btn">
                                            <select class="selectpicker form-control" id="height-type">
                                                <option value="sm">@lang('home.sm')</option>
                                                <option value="m">@lang('home.m')</option>
                                                <option value="dm">@lang('home.dm')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="right-side col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="count">@lang('home.lacing')</label>
                                        <input type="text" class="form-control" name="count" id="count">
                                    </div>
                                    <div class="input-group">
                                        <label for="width">@lang('home.width')</label>
                                        <input type="text" class="form-control" id="width" name="width">
                                        <div class="input-group-btn">
                                            <select class="selectpicker form-control" id="width-type">
                                                <option value="sm">@lang('home.sm')</option>
                                                <option value="m">@lang('home.m')</option>
                                                <option value="dm">@lang('home.dm')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="weight">@lang('home.weight')</label>
                                        <input type="text" class="form-control" name="weight" id="weight">
                                        <div class="input-group-btn">
                                            <select class="selectpicker form-control" id="weight-type">
                                                <option value="kq">@lang('home.kq')</option>
                                                <option value="qram">@lang('home.qram')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                                    <button class="btn-effect" id="calculate">@lang('home.calculate')</button>
                                    <p>@lang('home.total'): <span class="all_count">$0,00</span></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="call-center col-md-6 col-sm-12 col-xs-12">
                    <div class="thumbnail">
                        <img src="{{asset('front/img/call-center-img.png')}}" class="img-responsive" alt="news-01">
                        <div class="caption">
                            <img src="{{asset('front/img/call-center.png')}}" alt="calendar">
                            <p style="font-size: 20px;">
                                *9595 <br />
                                +994125058797 <br />
                                +994508249595 (whatsapp)
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="tarif">
        <div class="container">
            <div class="section-title">
                <h3>@lang('home.tariffs')</h3>
            </div>
            @if(!empty($tariffsForTr))
            <div class="block">
                <div class="row">
                    <div class="col-md-12">
                        <div class="top">
                            <img src="{{!empty($tariffsForTr) ? url(Storage::url($tariffsForTr->file)) : ''}}" alt="flag-tr">
                            <img src="{{asset('front/img/plane.png')}}" alt="plane">
                        </div>
                        <ul class="nav nav-tabs country-inner-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#turkey" aria-controls="turkey" role="tab" data-toggle="tab">
                                    Türkiyə-Bakı
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#turkey-ganja" aria-controls="turkey-ganja" role="tab" data-toggle="tab">
                                    Türkiyə-Gəncə
                                </a>
                            </li>
                            <li role="presentation">
                                <span>Ardı var...</span>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade in active" id="turkey">
                                <ul>
                                    @foreach($country->tariffs as $tariff)
                                        <li>{{$tariff->name}}<span>{{$tariff->price}} $</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="turkey-ganja">
                                <ul>
                                    <li>0 dan - 0,25 kq dək<span>2.50 $</span></li>
                                    <li>0,25 kq dan - 0.5 kq dək<span>3.50 $</span></li>
                                    <li>0,5 kq dan - 0.7 kq dək<span>4.50 $</span></li>
                                    <li>0,7 kq dan - 1 kq dək<span>5.50 $</span></li>
                                </ul>
                            </div>
                        </div>
                        <p>@lang('home.link-info-text')</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    {{--<section class="link">--}}
        {{--<div class="container">--}}
            {{--<div class="section-title">--}}
                {{--<h3>@lang('home.link-calc')</h3>--}}
            {{--</div>--}}
            {{--<div class="block">--}}
                {{--<div class="row">--}}
                    {{--<div class="left-side col-md-6 col-sm-6 col-xs-12">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-8 col-sm-6 col-xs-12">--}}
                                {{--<div class="input-group">--}}
                                    {{--<label for="coountlink">@lang('home.link-amount') TL</label>--}}
                                    {{--<input type="text" name="countlink" class="form-control" id="coountlink"--}}
                                           {{--placeholder="@lang('home.link-amount-placeholder')">--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-4 col-sm-6 col-xs-12">--}}
                                {{--<p class="heading">@lang('home.link-amount-for-pay')</p>--}}
                                {{--<p>0.00 AZN</p>--}}
                            {{--</div>--}}
                            {{--<div class="bottom-text col-md-12 col-sm-12 col-xs-12">--}}
                                {{--<p class="heading">@lang('home.link-info')</p>--}}
                                {{--<span>@lang('home.link-info-text')</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="right-side col-md-6 col-sm-6 col-xs-12">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-md-12 col-sm-12 col-xs-12">--}}
                                {{--<p class="heading">@lang('home.link-how-calc')</p>--}}
                                {{--<span>@lang('home.link-how-calc-text')</span>--}}
                            {{--</div>--}}
                            {{--<div class="col-md-12 col-sm-12 col-xs-12 link-note-{{ str_replace('_', '-', app()->getLocale()) }}">--}}
                                {{--<p class="heading">@lang('home.link-note'):</p>--}}
                                {{--<span>@lang('home.link-note-text')</span>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}
    <div class="logo-slide">
        <div class="container">
            <div class="owl-carousel  owl-theme">
                <div><img src="{{asset('front/img/defacto.png')}}" class="img-responsive" alt="defacto"></div>
                <div><img src="{{asset('front/img/gitdigidiyor.png')}}" alt="gitdigidiyor"></div>
                <div><img src="{{asset('front/img/koton.png')}}" alt="koton"></div>
                <div><img src="{{asset('front/img/markafoni.png')}}" alt="markafoni"></div>
                <div><img src="{{asset('front/img/n11com.png')}}" alt="n11com"></div>
                <div><img src="{{asset('front/img/trendyol.png')}}" alt="trendyol"></div>
            </div>
        </div>
    </div>
@endsection
