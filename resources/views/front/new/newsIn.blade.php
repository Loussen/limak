@extends('layouts.front_main')

@section('content')
    <section class="news-inner">
        @include('front.components.breadcrumb' ,['section' => 'announcement'])
        <div class="news-body">
            <div class="container">
                <div class="row relative">
                    <div class="col-md-9 col-sm-8 col-xs-12">
                        <div class="news-left">
                            @if($news)
                                <div class="row">
                                    <div class="col-xs-12">
                                        <img src="{{url(Storage::url($news->file))}}" alt="new_1"
                                             class="img-responsive">
                                        <div class="news-content">
                                            <h3>{{$news->name}}</h3>
                                            <span style="color: #AAAAAA !important;"><i class="fa fa-calendar" aria-hidden="true"></i> {{$news->created_at}}</span>
                                            <div class="news-parag">
                                                {!! $news->description !!}
                                                {!! $news->content !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <p style="text-align: center; font-size: 20px;font-weight: bold;">@lang('common.no-content')</p>
                            @endif
                            <hr>
                            <div class="row">
                                <div class="col-xs-12">
                                    {{--<button type="button" class="btn-fb">--}}
                                    {{--<i class="fa fa-thumbs-up" aria-hidden="true"></i>--}}
                                    {{--<b> Like</b> 112K--}}
                                    {{--</button>--}}
                                    {{--<button type="button" class="btn-share">--}}
                                    {{--<b> Share</b>--}}
                                    {{--</button>--}}
                                    <div class="fb-share-button"
                                         data-href="{{ Request::url() }}"
                                         data-layout="button_count">
                                    </div>
                                    {{--<div class="social-right">--}}
                                    {{--<span class="social-share">Paylaş:</span>--}}
                                    {{--<ul class="social-icons">--}}
                                    {{--<li>--}}
                                    {{--<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ Request::url() }}">--}}
                                    {{--<i class="fa fa-facebook"></i>--}}
                                    {{--</a>--}}
                                    {{--</li>--}}
                                    {{--<li>--}}
                                    {{--<a target="_blank" href="https://twitter.com/share?url={{ Request::url() }}">--}}
                                    {{--<img src="/front/img/twitter.png" alt="twitter">--}}
                                    {{--</a>--}}
                                    {{--</li>--}}
                                    {{--</ul>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-12">
                        <div class="news-right">
                            {{--<div class="right-top">
                                <h5>Yeniliklərdən xəbərdar olmaq üçün
                                    Limak.Az-a abunə olun</h5>
                                <form action="...">
                                    <div class="input-group border-radius">
                                        <label>
                                            <input type="text" name="length" class="form-control"
                                                   placeholder="E-mail adresinizi qeyd edin">
                                        </label>
                                    </div>
                                    <button type="button" class="btn-effect">GÖNDƏR</button>
                                </form>
                            </div>--}}
                            <div class="right-bottom">
                                @foreach($allNews as $item)
                                    <div class="right-content">
                                        <h4>
                                            <a href="{{url(app()->getLocale() . '/blog/'.$item->id.'/'.Illuminate\Support\Str::slug($item->name, '-', str_replace('_', '-', app()->getLocale())))}}">
                                            {{$item->name}}
                                            </a>
                                        </h4>
                                        <span><i class="fa fa-calendar" aria-hidden="true"></i> {{$item->created_at}}</span>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

<style>
    .news-inner .news-left span{
        color: black !important;
    }
</style>
