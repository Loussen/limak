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
                                    @lang('panel-errors.from_turkey')
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
                            @foreach($countries as $index => $country)
                                <div role="tabpanel" class="tab-pane fade {{ $index ==0 ? ' in active' : '' }}" id="tab{{ $country->id }}">
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
                            @endforeach
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
