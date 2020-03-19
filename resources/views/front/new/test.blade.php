@extends('layouts.front_main')

@section('content')
<section class="slide">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-sm-12 col-xs-12 main-slider">
                <div class="owl-carousel owl-theme">
                    <div class="item">
                        <picture>
                            <source media="(max-width: 768px)" srcset="{{ asset('front/new/img/slideimg.png')}}">
                            <img src="{{ asset('front/new/img/slideimg.png')}}" class="img-responsive"
                                 alt="">
                            <button type="button" class="transparent" data-target=".video-modal" data-toggle="modal"><img src="{{ asset('front/new/img/video-play.png')}}"></button>
                        </picture>
                    </div>
                    @foreach($sliders as $slider)
                    <div class="item">
                        <picture>
                            <source media="(max-width: 768px)" srcset="{{ url(Storage::url($slider->file)) }}">
                            <img src="{{ url(Storage::url($slider->file)) }}" class="img-responsive"
                                 alt="slide-{{$slider->id}}">
                        </picture>
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4 col-sm-12 col-xs-12 right-side">
                <div class="block">
                    <h4>@lang('home.calculator')<span id="reset_calculator">@lang('home.reset_calculator')</span></h4>
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="input-group">
                                <select class="form-control selectpicker" id="country" title="Ölkə">
                                    <option selected value="tr">@lang('common.turkey')</option>
                                    {{--
                                    <option value="rusiya">Rusiya</option>
                                    --}}
                                    {{--
                                    <option value="azerbaycan">Azərbaycan</option>
                                    --}}
                                </select>
                            </div>
                            <div class="input-group">
                                <label>
                                    <input type="text" name="length" class="form-control inputText" id="length"
                                           placeholder=" ">
                                    <span>@lang('home.length')</span>
                                </label>
                                <div class="input-group-btn">
                                    <select class="selectpicker form-control" id="length-type">
                                        <option value="sm">@lang('home.sm')</option>
                                        <option value="m">@lang('home.m')</option>
                                        <option value="dm">@lang('home.dm')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>
                                    <input type="text" class="form-control inputText" name="height" id="height"
                                           placeholder=" ">
                                    <span>@lang('home.height')</span>
                                </label>
                                <div class="input-group-btn">
                                    <select class="selectpicker form-control" id="height-type">
                                        <option value="sm">@lang('home.sm')</option>
                                        <option value="m">@lang('home.m')</option>
                                        <option value="dm">@lang('home.dm')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group border-radius">
                                <label>
                                    <input type="text" id="count" class="form-control inputText" placeholder=" ">
                                    <span>@lang('home.lacing')</span>
                                </label>
                            </div>
                            <div class="input-group">
                                <label>
                                    <input type="text" name="width" id="width" class="form-control inputText"
                                           placeholder=" ">
                                    <span>@lang('home.width')</span>
                                </label>
                                <div class="input-group-btn">
                                    <select id="width-type" class="selectpicker form-control">
                                        <option value="sm">@lang('home.sm')</option>
                                        <option value="m">@lang('home.m')</option>
                                        <option value="dm">@lang('home.dm')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>
                                    <input type="text" class="form-control inputText" id="weight" placeholder=" ">
                                    <span>@lang('home.weight')</span>
                                </label>
                                <div class="input-group-btn">
                                    <select class="selectpicker form-control" id="weight-type">
                                        <option value="kq">@lang('home.kq')</option>
                                        <option value="qram">@lang('home.qram')</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="row">
                                <div class="col-xs-6">
                                    <button id="calculate" class="btn-effect border-btn">@lang('home.calculate')
                                    </button>
                                </div>
                                <div class="col-xs-6">
                                    <p class="count">@lang('home.total'): <b class="all_count">$0,00</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="how_it_works">
    <div class="container">
        <div class="">
            <h1>@lang('home.how_works')</h1>
            <div class="block col-xs-12">
                <div class="row">
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="block-inner">
                            <img src="{{ asset('front/new/img/mehsulunu_sec.png')  }}" class="img-responsive" alt="box">
                            <p class="head">@lang('home.register')</p>
                            <p class="text">@lang('home.register-text')</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="block-inner">
                            <img src="{{ asset('front/new/img/box.png')  }}" class="img-responsive" alt="box">
                            <p class="head">@lang('home.send-us')</p>
                            <p class="text">@lang('home.send-us-text')</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="block-inner">
                            <img src="{{ asset('front/new/img/courier.png')  }}" class="img-responsive" alt="box">
                            <p class="head">@lang('home.packages-to-you-home')</p>
                            <p class="text">@lang('home.packages-to-you-home-text')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="tarifs">
    <div class="container">
        <div class="section-header">
            <h2>@lang('home.tariffs')</h2>
        </div>
        <div class="block col-xs-12">
            @if( $tariffsForTr && count($tariffsForTr->tariffs))
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="top">
                    <img src="{{ asset('front/new/img/turkey.png') }}" alt="flag-tr">
                    <p class="head">@lang('home.delivery-from-turkey')</p>
                </div>
                <ul>
                    @foreach($tariffsForTr->tariffs as $tariff)
                    <li>{{$tariff->name}}<span>{{$tariff->price}} $</span></li>
                    @endforeach
                </ul>
                <div class="bottom-text">
                    @lang('home.tariffs_tr')
                </div>
            </div>
            @endif
            @if($tariffsForUS && count($tariffsForUS->tariffs) > 0)
            <div class="col-md-6 col-sm-6 col-xs-12">
                <div class="top">
                    <img src="{{ asset('front/new/img/united-states-of-america.png') }}" alt="flag-tr">
                    <p class="head">@lang('home.delivery-from-us')</p>
                </div>
                <ul>
                    @foreach($tariffsForUS->tariffs as $tariff)
                    <li>{{$tariff->name}}<span>{{$tariff->price}} $</span></li>
                    @endforeach
                </ul>
                <div class="bottom-text">
                    <p>Limak Sizə bağlamalarınızın çəkisindən asılı olaraq, 2.5-5.5 dollar arası qiymətlərlə
                        sərfəli çatdırılma təklif edir. </p>
                    <p>Sifariş olunan bağlamanın çatdırılma haqqı çəkiyə görə hesablanır. Ölçüsü 1 metrdən böyük
                        olan bağlamaların çatdırılma haqqı isə həcmi çəkiyə görə hesablanır.</p>
                </div>
            </div>
            @endif

        </div>
    </div>
</section>
<section class="last-news">
    <div class="container">
        <div class="section-header">
            <h2>@lang('home.announcements')</h2>
        </div>
        <div class="row">
            @foreach($news as $item)
            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="overlay">
                    {{-- <a
                        href="{{route('newsIn', ['id' => $item->id, 'slug' => Illuminate\Support\Str::slug($item->name, '-', str_replace('_', '-', app()->getLocale()))])}}">--}}
                    <a href="{{url('/blog/'.$item->id.'/'.Illuminate\Support\Str::slug($item->name, '-', str_replace('_', '-', app()->getLocale())))}}">
                        <img src="{{url(Storage::url($item->file))}}" class="img-responsive" alt="news-01">
                        <div class="caption">
                            <h4>{{$item->name}}</h4>
                            <p>
                                <i class="fa fa-calendar"></i>27.09.2018
                            </p>
                        </div>
                    </a>
                </div>
            </div>
            @endforeach
            {{--
            <div class="col-xs-12 text-center">--}}
                {{--<a href="#" class="btn-effect border-btn">@lang('home.all-news')</a>--}}
                {{--
            </div>
            --}}
        </div>
    </div>
</section>
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
<!--<div class="subscribe">
    <div class="container">
        <div class="row">
            <div class="col-md-7 col-sm-7 col-xs-12">
                <h3>@lang('home.subscribe-text')</h3>
            </div>
            <div class="col-md-5 col-sm-5 col-xs-12 text-right">
                <div class="input-group">
                    <label>
                        <input type="email" class="form-control inputText" placeholder=" ">
                        <span>@lang('home.enter-email')</span>
                    </label>
                    <button type="submit" class="btn-effect">@lang('home.be-subscribe')</button>
                </div>
            </div>
        </div>
    </div>
</div>-->
<div class="modal fade video-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <iframe src="https://www.youtube.com/embed/NE9d1G88rEQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
        </div>
    </div>
</div>
@endsection