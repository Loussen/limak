@extends('layouts.front_main')
@section('content')
    <section class="countries brands tarifs">
        @include('front.components.breadcrumb',['section' => 'countries'])
        <div class="countries-body">
            <div class="container">
                <div class="row relative">
                    <div class="col-xs-12">
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#tab1" aria-controls="home" role="tab" data-toggle="tab" class="order-img1">
                                    @lang('common.turkey')
                                </a>
                            </li>
                            <li role="presentation">
                                <a href="#tab2" aria-controls="home" role="tab" data-toggle="tab" class="order-img2">
                                    @lang('common.usa')
                                </a>
                            </li>
                            <!--<li role="presentation">
                                <a href="#2" aria-controls="profile" role="tab" data-toggle="tab" class="order-img2">Amerika`dan</a>
                            </li>
                            <li role="presentation">
                                <a href="#3" aria-controls="messages" role="tab" data-toggle="tab" class="order-img3">Rusiya`dan</a>
                            </li>
                            <li role="presentation">
                                <a href="#4" aria-controls="settings" role="tab" data-toggle="tab" class="order-img4">Almaniya`dan</a>
                            </li>
                            <li class="last" role="presentation">
                                <a href="#5" aria-controls="settings" role="tab" data-toggle="tab" class="order-img5">Dubay`dan</a>
                            </li>-->
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">

                            <div role="tabpanel" class="tab-pane fade in active" id="tab1">
                                <div class="countries-content block">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="top">
                                                <img src="{{ asset('front/new/img/turkey.png') }}" alt="flag-tr">
                                                <p class="head">@lang('country/index.delivery-from-turkey')</p>
                                            </div>
                                            <ul class="nav nav-tabs country-inner-tabs" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#turkey" aria-controls="turkey" role="tab" data-toggle="tab">
                                                        @lang('home.turkey-baku')
                                                    </a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#turkey-ganja" aria-controls="turkey-ganja" role="tab" data-toggle="tab">
                                                        @lang('home.turkey-ganja')
                                                    </a>
                                                </li>
                                                <li role="presentation">
                                                    <span>@lang('home.have-more')</span>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="turkey">
                                                     <ul>
                                                         @lang('home.tr-baku-tariffs')
                                                     </ul>
                                                 </div>
                                                <div role="tabpanel" class="tab-pane fade" id="turkey-ganja">
                                                    <ul>
                                                        @lang('home.tr-ganja-tariffs')
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="bottom-text">
                                                <p>@lang('home.link-info-text')</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <img src="{{ asset('front/new/img/countries.png') }}" alt="success" class="img-responsive center-block">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane fade" id="tab2">
                                <div class="countries-content block">
                                    <div class="row">
                                        <div class="col-sm-6 col-xs-12">
                                            <div class="top">
                                                <img src="{{ asset('front/new/img/united-states-of-america.png') }}" alt="flag-usa">
                                                <p class="head">@lang('country/index.delivery-from-usa')</p>
                                            </div>
                                            <ul class="nav nav-tabs country-inner-tabs" role="tablist">
                                                <li role="presentation" class="active">
                                                    <a href="#usa" aria-controls="turkey" role="tab" data-toggle="tab">
                                                        @lang('home.usa-baku')
                                                    </a>
                                                </li>
                                                <li role="presentation">
                                                    <a href="#usa-ganja" aria-controls="turkey-ganja" role="tab" data-toggle="tab">
                                                        @lang('home.usa-ganja')
                                                    </a>
                                                </li>
                                                <li role="presentation">
                                                    <span>@lang('home.have-more')</span>
                                                </li>
                                            </ul>
                                            <div class="tab-content">
                                                <div role="tabpanel" class="tab-pane fade in active" id="usa">
                                                    <ul>
                                                        @lang('home.usa-baku-tariffs')
                                                    </ul>
                                                </div>
                                                <div role="tabpanel" class="tab-pane fade" id="usa-ganja">
                                                    <ul>
                                                        @lang('home.usa-ganja-tariffs')
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="bottom-text">
                                                <p>@lang('home.link-info-text')</p>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-xs-12">
                                            <img src="{{ asset('front/new/img/countries.png') }}" alt="success" class="img-responsive center-block">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{--<section class="page-block olkeler tarif">--}}
        {{--<div class="container">--}}
            {{--<div class="page-head">--}}
                {{--<img src="{{asset('front/img/olkeler-page.png')}}" alt="country">--}}
                {{--<h1>@lang('country/index.countries')</h1>--}}
            {{--</div>--}}
            {{--<div class="row">--}}
                {{--@foreach($countries as $country)--}}
                    {{--<div class="col-md-6 col-sm-12 col-xs-12">--}}
                        {{--<div class="block">--}}
                            {{--<div class="row">--}}
                                {{--<div class="col-md-12">--}}
                                    {{--<div class="top">--}}
                                        {{--<img src="{{url(Storage::url($country->file))}}" alt="{{$country->name}}">--}}
                                        {{--<img src="{{asset('front/img/plane.png')}}" alt="plane">--}}
                                    {{--</div>--}}
                                    {{--<ul>--}}
                                        {{--@foreach($country->tariffs as $tariff)--}}
                                            {{--<li>{{$tariff->name}}<span>{{$tariff->price}} $</span></li>--}}
                                        {{--@endforeach--}}
                                    {{--</ul>--}}
                                    {{--<p>@lang('home.link-info-text')</p>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--@endforeach--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</section>--}}
@endsection
