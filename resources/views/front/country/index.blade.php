@extends('layouts.front')
@section('content')
    <section class="page-block olkeler tarif">
    <div class="container">
        <div class="page-head">
            <img src="{{asset('front/img/olkeler-page.png')}}" alt="country">
            <h1>@lang('country/index.countries')</h1>
        </div>
        <div class="row">
            @foreach($countries as $country)
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="block">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="top">
                                    <img src="{{url(Storage::url($country->file))}}" alt="{{$country->name}}">
                                    <img src="{{asset('front/img/plane.png')}}" alt="plane">
                                </div>
                                <ul>
                                    @foreach($country->tariffs as $tariff)
                                        <li>{{$tariff->name}}<span>{{$tariff->price}} $</span></li>
                                    @endforeach
                                </ul>
                                <p>@lang('home.link-info-text')</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection