@extends('layouts.front_main')

@section('content')
    <section class="about brands">
        <?php
        $section = (\Request::is('*haqqimizda') || \Request::is('*o-nas') || \Request::is('*about')) ? 'about' : 'privacy';
        ?>
            @include('front.components.breadcrumb' ,['section' => $section])
        <div class="news-body">
            <div class="container">
                <div class="row relative">
                    <div class="col-md-3 col-sm-4 col-xs-12 web-xs">
                        <div class="brands-left">
                            <div class="left-top">
                                <div class="left-head relative">
                                    <h4>@lang('page.auxiliary_sections')</h4>
                                </div>
                                <ul class="list-unstyled">
                                    <li>
                                        <a href="/{{Lang::getLocale()}}/istifadeci-qaydalari">@lang('common.rules')</a>
                                    </li>
                                    <li>
                                        <a href="/{{Lang::getLocale()}}/suallar">@lang('common.questions')</a>
                                    </li>
                                    <li class="{{ (\Request::is('*gizlilik-sertleri')) ? 'active' : '' }}">
                                        <a href="/{{Lang::getLocale()}}/gizlilik-sertleri">@lang('home.privacy')</a>
                                    </li>
                                    <li class="{{ (\Request::is('*about-us')) ? 'active' : '' }}">
                                        <a href="/{{Lang::getLocale()}}/about-us">@lang('home.about')</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-12 mobile-xs">
                        <div class="brands-left">
                            <div class="left-top-mob block">
                                <div class="left-head relative">
                                    <h4>@lang('page.auxiliary_sections')</h4>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        {{ (\Request::is('*gizlilik-sertleri')) ? __('breadcrumb.privacy') : __('breadcrumb.about') }}
                                        <span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li>
                                            <a href="/{{Lang::getLocale()}}/istifadeci-qaydalari">@lang('common.rules')</a>
                                        </li>
                                        <li>
                                            <a href="/{{Lang::getLocale()}}/suallar">@lang('common.questions')</a>
                                        </li>
                                        <li class="{{ (\Request::is('*gizlilik-sertleri')) ? 'active' : '' }}">
                                            <a href="/{{Lang::getLocale()}}/gizlilik-sertleri">@lang('home.privacy')</a>
                                        </li>
                                        <li class="{{ (\Request::is('*haqqimizda')) ? 'active' : '' }}">
                                            <a href="/{{Lang::getLocale()}}/about-us">@lang('home.about')</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9 col-sm-8 col-xs-12">
                        <div class="brands-right">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="about-img relative">
                                        <img src="{{ asset('front/new/img/about_back.png') }}" alt="about_back" class="img-responsive">
                                        <img src="{{ asset('front/new/img/about_logo.png') }}" alt="about_logo" class="about-logo">
                                    </div>
                                    @foreach($pageContent as $component)
                                        @foreach($component->data as $data)
                                            @if($data->locale === Lang::getLocale())
                                                <h4>{{$data->name}}</h4>
                                                {!! $data->description !!}
                                            @endif
                                        @endforeach
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
