@extends('layouts.front_main')

@section('content')
    <section class="rules brands">
        @include('front.components.breadcrumb' ,['section' => 'rules'])
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
                                    <li class="active">
                                        <a href="/{{Lang::getLocale()}}/istifadeci-qaydalari">@lang('common.rules')</a>
                                    </li>
                                    <li>
                                        <a href="/{{Lang::getLocale()}}/suallar">@lang('common.questions')</a>
                                    </li>
                                    <li>
                                        <a href="/{{Lang::getLocale()}}/gizlilik-sertleri">@lang('home.privacy')</a>
                                    </li>
                                    <li>
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
                                        @lang('common.rules')
                                        <span><i class="fa fa-angle-down" aria-hidden="true"></i></span>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                        <li class="active">
                                            <a href="/{{Lang::getLocale()}}/istifadeci-qaydalari">@lang('common.rules')</a>
                                        </li>
                                        <li>
                                            <a href="/{{Lang::getLocale()}}/suallar">@lang('common.questions')</a>
                                        </li>
                                        <li>
                                            <a href="/{{Lang::getLocale()}}/gizlilik-sertleri">@lang('home.privacy')</a>
                                        </li>
                                        <li>
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
                                    <h2>@lang('page.limak_user_rules')</h2>
                                    <div class="panel-group" id="accordion">
                                        @php($count=0)
                                        @foreach($data as $item)
                                            <div class="panel panel-default {{($count==0 ? 'active' : '')}}">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#{{$count}}collapseOne">
                                                            {{$item->question}}
                                                        </a>
                                                    </h4>
                                                </div>
                                                <div id="{{$count}}collapseOne" class="panel-collapse collapse {{($count==0 ? 'in' : '')}}">
                                                    <div class="panel-body">
                                                        <?php
                                                            echo html_entity_decode($item->answer)
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                            @php($count++)
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>@endsection
