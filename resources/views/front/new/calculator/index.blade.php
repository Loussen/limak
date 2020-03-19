@extends('layouts.front_main')
@section('content')
    <section class="calculator brands content">
        @include('front.components.breadcrumb' ,['section' => 'calculator'])
        <div class="news-body">
            <div class="container">
                <div class="row relative">
                    <div class="col-md-5 col-sm-12 col-xs-12">
                        <div class="calculator-left">
                            <div class="left-top">
                                <div class="block">
                                    <h4>@lang('home.calculator')
                                        <button id="reset_calculator" type="button" class="transparent">@lang('home.reset_calculator')</button>
                                    </h4>
                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="input-group">
                                                <select class="form-control selectpicker"  id="country" title="Ölkə">
                                                    <option selected value="1">@lang('common.turkey')</option>
                                                    <option value="2">@lang('common.usa')</option>
                                                    {{--<option value="rusiya">Rusiya</option>--}}
                                                    {{--<option value="azerbaycan">Azərbaycan</option>--}}
                                                </select>
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
                                            <div class="input-group">
                                                <label>
                                                    <input type="text" name="width" id="width" class="form-control inputText" placeholder=" ">
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
                                            <div class="input-group">
                                                <select class="form-control selectpicker"  id="region" title="Region">
                                                    <option selected value="1">Bakı</option>
                                                    <option value="2">Gəncə</option>
                                                    {{--<option value="rusiya">Rusiya</option>--}}
                                                    {{--<option value="azerbaycan">Azərbaycan</option>--}}
                                                </select>
                                            </div>
                                            <div class="input-group border-radius">
                                                <label>
                                                    <input type="text" id="count"  class="form-control inputText" placeholder=" ">
                                                    <span>@lang('home.lacing')</span>
                                                </label>
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


                                        </div>
                                        <div class="col-xs-12">
                                            <div class="row">
                                                <div class="col-xs-6">
                                                    <button id="calculate" class="btn-effect border-btn">@lang('home.calculate')</button>
                                                </div>
                                                <div class="col-xs-6">
                                                    <p class="count">@lang('home.total'): <b class="all_count">$0,00</b></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="left-bottom">
                                <div id="currency" class="block price web">
                                    <h4>@lang('currency.currency_calculator')</h4>
                                    {{--<button id="reset_currency" type="button" class="return"><img src="{{ asset('front/new/img/return.png') }}" alt="return"></button>--}}
                                    <form action="...">
                                        <div class="input-group border-radius">
                                            <input id="from" type="number" class="form-control" placeholder="0.0">
                                            <select id="from_currency" class="form-control selectpicker" name="money">
                                                <option value="azn">AZN</option>
                                                <option value="usd">USD</option>
                                                <option value="try">TRY</option>
                                            </select>
                                        </div>
                                        <div class="input-group border-radius">
                                            <input id="to" type="number" class="form-control" placeholder="0.0">
                                            <select id="to_currency" class="form-control selectpicker" name="money-1">
                                                <option value="azn">AZN</option>
                                                <option selected value="usd">USD</option>
                                                <option value="try">TRY</option>
                                            </select>
                                        </div>
                                    </form>
                                    <span>@lang('currency.calculating')</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                        <div class="calculator-right">
                            <div class="row">
                                <div class="col-xs-12">
                                    @lang('calculator/index.calculator_text')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')

@endsection