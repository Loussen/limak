@extends('layouts.front_main')

@section('content')
    <section class="brands">
        @include('front.components.breadcrumb' ,['section' => 'shop'])
        <div class="news-body about">
            <div class="container">
                <div class="row relative">
                    <div class="col-md-3 col-sm-12 col-xs-12 web-xs">
                        <div class="brands-left">
                            <div class="left-top">
                                <div class="left-head relative">
                                    <h4>@lang('shop.shops_per_country')</h4>
                                </div>
                                <ul class="list-unstyled">
                                    <li class="{{ $country =='tr' ? 'active' : null }}">
                                        <a href="{{route('front.shop','tr')}}">@lang('common.turkey')</a>
                                    </li>
                                    <li class="{{ $country =='usa' ? 'active' : null }}">
                                        <a href="{{route('front.shop','usa')}}">@lang('shop.usa')</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="left-bottom">
                                <div class="left-head relative">
                                    <h4>@lang('panel-errors.shop_category')</h4>
                                </div>
                                <ul class="list-unstyled">
                                    <li class="{{ app('request')->input('cat') == '' ? 'active' : null }}">
                                        <a href="?cat=">@lang('shop.common')</a>
                                    </li>
                                    @foreach($types as $item)
                                        <li class="{{ app('request')->input('cat') ==$item->id ? 'active' : null }}">
                                            <a href="?cat={{ $item->id }}">{{ $item->name }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-3 col-sm-4 col-xs-12 mobile-xs">
                        <div class="brands-left">
                            <div class="left-top-mob block">
                                <div class="left-head relative">
                                    <h4>@lang('shop.shops_per_country')</h4>
                                </div>
                                <div class="input-group border-radius">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            @if($country=='tr') @lang('common.turkey')
                                            @elseif($country == 'usa') @lang('shop.usa')
                                            @endif
                                            <span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                            <li class="{{ $country =='tr' ? 'active' : null }}">
                                                <a href="/{{Lang::getLocale()}}/shops/country/tr">@lang('common.turkey')</a>
                                            </li>
                                            <li class="{{ $country =='usa' ? 'active' : null }}">
                                                <a href="/{{Lang::getLocale()}}/shops/country/usa">@lang('common.usa')</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            {{--<div class="left-bottom-mob block">
                                <div class="left-head relative">
                                    <h4>Mağaza kateqoriyası</h4>
                                </div>
                                <div class="input-group border-radius">
                                    <select class="form-control selectpicker" name="general" title="Ümumi">
                                        <option value="0">Ümumi</option>
                                        <option value="1">Geyim</option>
                                        <option value="2">Ayaqqabı</option>
                                        <option value="3">Elektronika</option>
                                        <option value="4">Qab-qacaq</option>
                                        <option value="5">Geyim</option>
                                        <option value="6">Ayaqqabı</option>
                                    </select>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="brands-right">
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2>@lang('shop.common_category')</h2>
                                </div>
                            </div>
                            <div class="row">
                                @foreach($shops as $shop)
                                    <div class="col-md-4">
                                        <a href="{{$shop->url}}" target="_blank" rel="nofollow">
                                            <div class="right-logo relative">
                                                <img src="{{url(Storage::url('shops/'.$shop->id.'.png'))}}" alt="{{$shop->name}} - dən çatdırılma">
                                                <span>{{$shop->name}}</span>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
