@extends('layouts.front_main')

@section('content')
    <section class="invoice content num-spin">
        <div class="container">
            <div class="row">
                <div class="invoice-head col-xs-12">
                    <div class="col-md-4 col-sm-5 col-xs-6">
                        <h4>@lang('invoice/index.upload-invoice')</h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-6">
                        <ol class="breadcrumb web">
                            <li><a href="index.html">@lang('breadcrumb.home')</a></li>
                            <li><a href="panel.html"> @lang('breadcrumb.user-panel')</a></li>
                            <li class="active">@lang('breadcrumb.upload-invoice')</li>
                        </ol>
                        <ol class="breadcrumb mobile">
                            <li><a href="index.html">... </a></li>
                            <li class="active">@lang('breadcrumb.upload-invoice')</li>
                        </ol>
                    </div>
                </div>
                <div class="invoice-body col-xs-12">
                    <div class="row relative">
                        <div class="col-md-9 col-sm-12 col-xs-12">
                            <form action="{{ route('front.invoice.index') }}" method="POST">
                                <div class="invoice-left-side block">
                                    <div class="left-content">
                                        <div class="row">
                                            <div class="col-md-7 col-sm-7 col-xs-6 ">
                                                <div class="input-group border-radius">
                                                    <label>
                                                        <input type="text" name="shop_name" class="form-control inputText" placeholder=" ">
                                                        <span>@lang('invoice/index.shop-name') * </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-5 col-sm-5 col-xs-6">
                                                <div class="input-group border-radius">
                                                    <select class="form-control selectpicker" name="product_type_id" title="@lang('invoice/index.products-in-package') *">
                                                        @foreach($productTypes as $productType)
                                                            <option value="{{$productType->id}}">{{$productType->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="ch-input-1 input-group num-spinner">
                                                    <input name="quantity" placeholder="@lang('invoice/index.product-counts')" type="number" value="" min="1"/>
                                                </div>
                                                <div class="ch-input-2 input-group border-radius">
                                                    <label>
                                                        <input name="price" type="text" class="form-control inputText" placeholder=" ">
                                                        <span>@lang('invoice/index.price') *</span>
                                                    </label>
                                                </div>
                                                <div class="ch-input-2 input-group border-radius">
                                                    <label>
                                                        <input type="text" name="order_number" class="form-control inputText" placeholder=" ">
                                                        <span>@lang('invoice/index.order-watching-code') *</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="invoice-date">
                                                    <h5>@lang('invoice/index.orderDate') *</h5>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <div class="invoice-day input-group border-radius">
                                                    <select class="form-control selectpicker" name="day" title="@lang('invoice/index.day')" id="day">
                                                    </select>
                                                </div>
                                                <div class="invoice-month input-group border-radius">
                                                    <select class="form-control selectpicker" name="month" title="@lang('invoice/index.month')" id="month">
                                                    </select>
                                                </div>
                                                <div class="invoice-year input-group border-radius">
                                                    <select class="form-control selectpicker" name="year" title="@lang('invoice/index.year')" id="year">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="textarea-form form-group">
                                                    <textarea name="description" class="form-control" rows="4" id="comment" placeholder="@lang('invoice/index.description')"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="btn-part text-left">
                                                    {{--<button class="btn-send btn-effect">
                                                        <span>@lang('invoice/index.upload-invoice')</span>
                                                    </button>--}}
                                                    <label for="file" class="invoice-upload">
                                                        <span>@lang('invoice/index.upload-invoice')</span>
                                                        <input type="file" class="form-control-file" id="file" name="invoice">
                                                    </label>
                                                    <button class="btn-effect">
                                                        <span>@lang('invoice/index.send')</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12">
                            <div class="invoice-right block">
                                <h3 class="text-center">@lang('invoice/index.attention')</h3>
                                <p>@lang('invoice/index.attention_text')</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection