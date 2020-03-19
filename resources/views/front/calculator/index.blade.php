@extends('layouts.front')
@section('content')
    <section class="page-block count">
        <div class="container">
            <div class="page-head">
                <img src="{{asset('front/img/kalkulyator-page.png')}}" alt="kalkulyator">
                <h1>@lang('home.calculator')</h1>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="block">
                        <form action="...">
                            <div class="row">
                                <div class="left-side col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="olke">@lang('home.country')</label>
                                        <select class="form-control selectpicker" name="olke" id="country">
                                            <option value="tr">@lang('common.turkey')</option>
                                            {{--<option value="usa">Amerika</option>
                                            <option value="ch">Çin</option>--}}
                                        </select>
                                    </div>
                                    <div class="input-group">
                                        <label for="length">@lang('home.length')</label>
                                        <input type="text" name="length" class="form-control" id="length">
                                        <div class="input-group-btn">
                                            <select class="selectpicker form-control" id="length-type">
                                                <option selected value="sm">@lang('home.sm')</option>
                                                <option value="m">@lang('home.m')</option>
                                                <option value="dm">@lang('home.dm')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="height">@lang('home.height')</label>
                                        <input type="text" class="form-control" name="height" id="height">
                                        <div class="input-group-btn">
                                            <select class="selectpicker form-control" id="height-type">
                                                <option value="sm">@lang('home.sm')</option>
                                                <option value="m">@lang('home.m')</option>
                                                <option value="dm">@lang('home.dm')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="right-side col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="count">@lang('home.lacing')</label>
                                        <input type="text" class="form-control" name="count" id="count">
                                    </div>
                                    <div class="input-group">
                                        <label for="width">@lang('home.width')</label>
                                        <input type="text" class="form-control" id="width" name="width">
                                        <div class="input-group-btn">
                                            <select class="selectpicker form-control" id="width-type">
                                                <option value="sm">@lang('home.sm')</option>
                                                <option value="m">@lang('home.m')</option>
                                                <option value="dm">@lang('home.dm')</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <label for="weight">@lang('home.weight')</label>
                                        <input type="text" class="form-control" name="weight" id="weight">
                                        <div class="input-group-btn">
                                            <select class="selectpicker form-control" id="weight-type">
                                                <option value="kq">@lang('home.kq')</option>
                                                <option value="qram">@lang('home.qram')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                                    <button class="btn-effect" id="calculate">@lang('home.calculate')</button>
                                    <p>@lang('home.total'): <span class="all_count">$0,00</span></p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="right-side col-md-6 col-sm-12 col-xs-12">
                    <div class="block">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="bottom-text">
                                    <div class="input-group">
                                        <label for="countlink">@lang('country/index.cal-for-url')</label>
                                        <input type="number" name="countlink" class="form-control" id="countlink"
                                               placeholder="@lang('home.link-amount-placeholder')">
                                    </div>
                                    <p class="heading">@lang('home.link-amount-for-pay')</p>
                                    <p id="general-count" class="general-count">0.00 AZN</p>
                                </div>
                            </div>
                            <div class="information col-md-12 col-sm-12 col-xs-12">
                                <p class="heading">@lang('home.link-info')</p>
                                <span>@lang('home.link-info-text')</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="end-part col-md-12 col-sm-12 col-xs-12">
                    <div class="block">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="inner">
                                    <p class="heading">@lang('home.link-how-calc')</p>
                                    <span>@lang('home.link-how-calc-text')</span>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="inner">
                                    <p class="heading">@lang('home.link-note'):</p>
                                    <span>@lang('home.link-note-text')</span>
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
    <script type="text/javascript">
        $("#countlink").keyup(function (e) {
            setTimeout(function () {

                const value = e.target.value;

                $.ajax({
                    method: 'POST',
                    url: '/calculate-currency',
                    data: {value: value},
                    success: function (result) {
                        if (result.status === 200) {
                            $("#general-count").html(result.result.tl + " TL və ya " + result.result.manat + " AZN");
                        }
                    }
                })
            }, 100)
        });
    </script>
@endsection