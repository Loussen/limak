@extends('layouts.front')

@section('content')
    <section class="page-block count magaza">
        <div class="container">
            <div class="page-head">
                <img src="{{asset('front/img/magazalar-page.png')}}" alt="magaza">
                <h1>@lang('common.shops')</h1>
            </div>
            <div class="row">
                @foreach($shops as $shop)
                    <div class="col-md-3 col-sm-6 col-xs-6">
                        <a href="{{$shop->url}}" target="_blank" rel="nofollow">
                            <div class="block">
                                <img src="{{asset('front/img/urls.png')}}" alt="{{$shop->name}}">
                                <span>{{$shop->name}}</span>
                            </div>
                            <div class="block">
                                <img src="{{url(Storage::url('shops/'.$shop->id.'.png'))}}" alt="{{$shop->name}} - dən çatdırılma">
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
