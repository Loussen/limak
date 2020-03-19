@extends('tr.layouts.admin_anbar_tr')

@section('header')
    <header>
        <nav class="navbar navbar-default navbar-fixed-top custom">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                    data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="{{url('/')}}"><img src="{{asset('/admin/img/logo_black2.png')}}" alt="logo" class="img-responsive"></a>
                        </div>
                    </div>
                    <div class="col-md-10 text-center middle content">
                        <form class="formSearch">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input name="fullname" type="text" class="form-control" placeholder="İsim, Soyisime göre ara">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input name="user_code" type="text" class="form-control" placeholder="Müşteri koduna göre ara">
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="{{$status}}">
                                <div class="col-md-1">
                                    <input class="btn-effect forSearch" type="submit" value="ARA">
                                </div>
                                <div class="col-md-1">
                                    <a href="{{url('logout')}}" class="btn-effect" style="background: red;padding: 10px"><i class="fa fa-sign-out"></i> Çıxış</a>
                                </div>

                            </div>
                        </form>
                    </div>
                    {{--<div class="col-md-3">--}}
                        {{--<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">--}}
                            {{--<ul class="nav navbar-nav navbar-right">--}}
                                {{--<li class="dropdown">--}}
                                    {{--<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"--}}
                                       {{--aria-expanded="false">Name Surname <span class="caret"></span>--}}
                                        {{--<img src="{{asset('/admin/img/profil-image.jpg')}}" alt="profil"></a>--}}
                                    {{--<ul class="dropdown-menu">--}}
                                        {{--<li><a href="#">Profilim</a></li>--}}
                                        {{--<li><a href="#">Tənzimləmələr</a></li>--}}
                                        {{--<li><a href="#">Çıxış</a></li>--}}
                                    {{--</ul>--}}
                                {{--</li>--}}
                            {{--</ul>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                </div>
            </div>
        </nav>
    </header>
    @php
    $status = 12;
    @endphp
    <div class="content">
        <div id="siparisler" class="custom-container">
            <div class="">
                <div class="buttons_list content">
                    <ul class="buttons_list">
                        @php
                            if($last30type=='higher') $status=33;
                        @endphp
                        {{--<li><a id="sipharisler" data-status="1" data-toggle="tab" href="" class="btn-effect">SİPHARİŞLER</a></li>--}}
                        <li><a id="sipharisler" data-status="1" href="{{url('/invoices/get/index?status=1')}}" class="btn-effect">SİPHARİŞLER</a></li>
                        <li><a id="anbar" data-status="2" href="{{url('/invoices/get/index?status=2')}}" class="btn-effect">ANBAR</a></li>
                        <li><a id="yolda" data-status="3" href="{{url('/invoices/get/index?status=3')}}" class="btn-effect">YOLDA</a></li>
                        <li><a href="{{url('sack')}}" class="btn-effect">ÇUVALLAR</a></li>
                        <li><a href="{{url('dispatch')}}" class="btn-effect">SEFKİYYAT</a></li>
                        <li><a href="{{url('invoices/get/stored')}}" class="btn-effect active">SAKLANAN KOLİLER</a></li>
                        <li><a href="#" class="btn-effect">İADE EDİLENLER</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="content">
        <div class="clearfix"></div>
        <div class="custom-container">
            {{--<div class="row">--}}
                {{--<div class="col-md-12 col-sm-12 col-xs-12">--}}
                    {{--<div class="top-menu">--}}
                        {{--<h4>SİPHARİŞLER</h4>--}}
                        {{--<ol class="breadcrumb">--}}
                            {{--<li><a href="#"><i class="fa fa-home"></i>Sipharişler</a></li>--}}
                        {{--</ol>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            @php($display=($status==1 ? 'none' : 'show'))

            <div class="right_buttons_container" >
                <ul class="right_buttons">

                    <li>
                        <p class="badge" style="padding: 5px 10px;margin: 11px 0 0;display:{{$display}}">
                            Say: {{count($data)}}
                        </p>
                    </li>
                    <li>
                        <p class="badge" style="padding: 5px 10px;margin: 11px 0 0;display:{{$display}}">
                            @php($weight=0)
                            @foreach($data as $value)
                                @php($weight=$weight+$value->weight)
                            @endforeach
                            Çeki: {{$weight}} kq
                        </p>
                    </li>
                    <li>
                        <a type="button" target="_blank"  class="btn-effect" href="/anbar-tr">
                            Fatura
                        </a>
                    </li>
                    <li><a type="button" data-toggle="modal" data-target=".mushteri" class="btn-effect blue_border" href="">
                            Bakü ofİsİne gönder
                        </a></li>
                    <li><a type="button" data-toggle="modal" data-target=".habardar" class="btn-effect orange_border" href="">
                            MüşterİNİ HABARDAR ET
                        </a></li>
                    <li style="display:{{$display}}"><a class="btn-effect blue_inner" data-toggle="modal" data-target="#addInvoice" href="">
                            beyanname Ekle
                        </a></li>
                    <li style="display:{{$display}}">
                        <form action="{{url('invoices/change-all-stauses')}}" method="post" id="selected_inputs">

                            <input value="seÇİlmİŞİ gönder" type="submit" class="btn-effect orange_inner" >

                        </form>
                    </li>

                </ul>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="custom-container table_data">
            <div class="row">
                <div class="inform col-md-12 col-sm-12 col-xs-12">
                    <div class="block table-block">
                        <table class="table table-responsive table-striped anbar">
                            <thead>
                            <tr>
                                <th>Sıra</th>
                                <th>Tip</th>
                                <th>Son30</th>
                                <th>Müşteri İsimi,  Soyisimi, Kodu</th>
                                <th>Müşteri Telefon,  E-maili</th>
                                <th>ID Kodu</th>
                                <th>Purchase No</th>
                                <th>Mağaza</th>
                                <th>Ürün sayı</th>
                                <th>İçerik</th>
                                <th>Fiyat</th>
                                <th>Kayit</th>
                                <th>Waybl</th>
                                <th></th>
                                @if($status==2)
                                <th>
                                    <input type="checkbox" id="checkbox_0">

                                    {{--<div class="checkbox">--}}
                                        {{--<input name="all" type="checkbox" id="checkbox_0">--}}
                                        {{--<label for="checkbox_0"></label>--}}
                                    {{--</div>--}}
                                </th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="search_result">

                            </tbody>
                            <tbody class="anbar_body" style="display:{{$display}}">
                            @if(count($data) === 0)
                                <tr >
                                    <td colspan="13" style="font-size: 22px;font-weight: bold;text-align: center;"
                                        class="text-center">İnvoys yoxdur
                                    </td>
                                </tr>
                            @endif
                            @php($count=1)
                            @foreach($data as $value)
                                @php($last30days=number_format($value->price / $tryToUsd + $value->shipping_price, 2))

                                @if($last30type=='higher')
                                    @if($last30days>1000)
                                        <tr class="tr_{{$value->id}}" style="{{$value->by_bus==1 ? 'background:#e07f7f;color:white' : ''}}">
                                            <td>{{$count++}} </td>
                                           <td>
                                                {{$value->name}} {{$value->surname}}
                                                <b>{{$value->uniqid}}</b>
                                            </td>

                                            <td>
                                               {{$value->phone}}
                                                    <b>{{$value->email}}</b>
                                                @php($pin=$value->pin)
                                            </td>
                                            <td>{{isset($pin) ? $pin : ''}}</td>
                                            <td>{{$value->purchase_no}}</td>
                                            <td>{{$value->shop_name}}</td>
                                            <td>{{$value->quantity}}</td>
                                            {{--<td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{!empty($value->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</td>--}}
                                            <td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{$value->product_type_name}}</td>
                                            <td>{{$value->price}}*{{$value->quantity}} TL</td>
                                            <td>
                                                {{ $value->comment }}
                                            </td>
                                            <td><a href="/invoices/waybill/{{$value->id}}"
                                                   target="_blank" class="btn-info"> Waybill </a></td>
                                            <td style="width: 14%">
                                                @if($status==2)
                                                    <button class="lite-red btn-effect changeVehicle" data-bus="{{$value->by_bus}}" data-id="{{$value->id}}">
                                                        {{--<img src="{{asset('/admin/img/time.png')}}">--}}
                                                        <i class="fa fa-bus"></i>
                                                    </button>
                                                @endif
                                                <button class="lite-green btn-effect updateDimAndWeight"  data-id="{{$value->id}}">
                                                    <img src="{{asset('/admin/img/time.png')}}">
                                                </button>
                                                <button type="button"  class="blue btn-effect printHawb" id="printHawb" data-id="{{$value->id}}">
                                                    <i class="fa fa-print"></i>
                                                </button>
                                                @if($value->file)
                                                    <a download
                                                       href="{{url($value->file)}}"
                                                       class="yellow btn-effect">
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                                @endif
                                                @if($status==1)
                                                <a href="javascript:void(0)" data-id="{{$value->id}}" data-status="{{$value->status_id}}" class="submit-invoice-storage1 pink btn-effect id_{{$value->id}}" style="display:{{$value->weight>0 ? 'show' : 'none'}}">
                                                    ok
                                                </a>
                                                @endif
                                            </td>
                                            @if($status==2)
                                            <td>
                                                <input type="checkbox" class="checkboxes" id="checkbox_{{$value->id}}" name="selected_invoices[]" value="{{$value->id}}">

                                                {{--<div class="checkbox">--}}
                                                    {{--<input type="checkbox" id="checkbox_{{$value->id}}">--}}
                                                    {{--<label for="checkbox_{{$value->id}}"></label>--}}
                                                {{--</div>--}}
                                            </td>
                                            @endif
                                        </tr>
                                        @endif
                                    @else
                                    @if($last30days<1000)
                                        <tr class="tr_{{$value->id}}
                                        @if($value->status_id==13)
                                                danger
                                        @elseif($value->status_id==12 && ($value->user_last30 + $value->price * $tryToUsd + $value->shipping_price)>1000)
                                                danger
                                        @else success
                                        @endif;
                                        ">

                                            @php($price=$value->quantity*$value->price)
                                            <input type="hidden" value="{{$price}}">
                                            <td>{{$count++}}</td>
                                            <td><b>{{$value->status_id==12?' Auto': ' Manual'}}</b>
                                            </td>
                                            <td> {{number_format((float)($value->user_last30 + $value->price * $tryToUsd + $value->shipping_price), 2, '.', '')}}
                                                </td>
                                            <td>
                                                <?php
                                                if($value->client_id>0 and $value->corporate==1){
                                                ?>
                                                {{$value->client_name}} {{$value->client_surname}}
                                                <b>1{{str_pad($value->client_id,6,"0",STR_PAD_LEFT)}}</b>
                                                <?php
                                                }else{
                                                ?>
                                                {{$value->name}} {{$value->surname}}
                                                <b>{{$value->uniqid}}</b>
                                                <?php
                                                }
                                                ?>
                                            </td>

                                            <td>
                                                {{$value->phone}}
                                                <b>{{$value->email}}</b>
                                                @php($pin=$value->pin)
                                            </td>
                                            <td>{{isset($pin) ? $pin : ''}}</td>
                                            <td>{{$value->purchase_no}}</td>
                                            <td>{{$value->shop_name}}</td>
                                            <td>{{$value->quantity}}</td>
                                            {{--<td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{!empty($value->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</td>--}}
                                            <td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{$value->product_type_name}}</td>
                                            <td>{{$value->price}}TL</td>
                                            <td>
                                                {{ $value->comment }}
                                            </td>
                                            <td><a href="/invoices/waybill/{{$value->id}}"
                                                   target="_blank" class="btn-info"> Waybill </a></td>
                                            <td style="width: 14% ">
                                                @if($status==2)
                                                <button class="lite-red btn-effect changeVehicle" data-bus="{{$value->by_bus}}" data-id="{{$value->id}}">
                                                    {{--<img src="{{asset('/admin/img/time.png')}}">--}}
                                                    <i class="fa fa-bus"></i>
                                                </button>
                                                @endif
                                                <button class="lite-green btn-effect updateDimAndWeight"  data-id="{{$value->id}}">
                                                    <img src="{{asset('/admin/img/time.png')}}">
                                                </button>
                                                <button type="button"  class="blue btn-effect printHawb" id="printHawb" data-id="{{$value->id}}">
                                                    <i class="fa fa-print"></i>
                                                </button>
                                                @if($value->file)
                                                    <a download
                                                       href="{{url($value->file)}}"
                                                       class="yellow btn-effect">
                                                        <i class="fa fa-file"></i>
                                                    </a>
                                                @endif
                                                @if($value->status_id==12 && (float)($value->user_last30 + $value->price * $tryToUsd + $value->shipping_price)<=1000)
                                                <a href="javascript:void(0)" data-id="{{$value->id}}" data-status="{{$value->status_id}}" class="back-invoice-storage1 pink btn-effect id_{{$value->id}}" style="display:{{$value->weight>0 ? 'show' : 'none'}}" >
                                                    OK
                                                </a>
                                                @endif
                                            </td>
                                            @if($status==2)
                                            <td>
                                                <input type="checkbox" class="checkboxes" id="checkbox_{{$value->id}}" name="selected_invoices[]" value="{{$value->id}}">

                                                {{--<div class="checkbox">--}}
                                                    {{--<input type="checkbox" id="checkbox_{{$value->id}}" name="selected_invoices[]" value="{{$value->id}}">--}}
                                                    {{--<label for="checkbox_{{$value->id}}"></label>--}}
                                                {{--</div>--}}
                                            </td>
                                            @endif
                                        </tr>
                                    @endif
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                        <nav style="display:{{$display}}">
                            {{--{{ $data->links() }}--}}
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade look beyanname mushteri" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>BAKÜ OFİSİNE GÖNDER</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('/admin/img/Forma1.png')}}" alt="close"></button>
                </div>
                <div class="modal-body">
                    <form id="purchaseForm">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input type="text" name="purchase_no" class="form-control" id="id_code" placeholder="ID Kod">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                                <input type="submit" id="purchase_submit" class="btn-effect green" value="Göndər">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade look beyanname habardar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>MÜŞTERİNİ HABARDAR ET</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('/admin/img/Forma1.png')}}" alt="close"></button>
                </div>
                <div class="modal-body">
                    <form id="invoiceless_form">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input type="text" name="user_uid" class="form-control" id="code" placeholder="Müştəri kodu">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                                <input type="submit" id="" class="send-invoice btn-effect green" value="Göndər">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="hawbUpdateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<h5 class="modal-title" id="exampleModalLabel">Çap - ətraflı</h5>--}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="hawbUpdateForm" method="post">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                    {{--<button id="hawbUpdateBtn" type="button" class="btn btn-primary">Gönder</button>--}}
                    <button id="" type="button" class="btn btn-primary hawbUpdateBtn">Gönder</button>
                </div>
            </div>
        </div>
    </div>
    <div style="opacity: 0; height: 0;" id="hawbData">
    </div>
    <div class="modal fade" id="hawbModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    {{--<h5 class="modal-title" id="exampleModalLabel">Çap - ətraflı</h5>--}}
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="hawbForm" method="post">
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Ağırlık (kq)</label>
                                <input type="number" class="form-control" name="weight" placeholder="Misal: 3.401"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Uzunluk (sm)</label>
                                <input type="number" class="form-control" name="length" placeholder="Misal: 3.401"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Genişlik (sm)</label>
                                <input type="number" class="form-control" name="width" placeholder="Misal: 5.40"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Yükseklik (sm)</label>
                                <input type="number" class="form-control" name="height" placeholder="Misal: 14.40"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                    <button id="hawbBtn" type="button" class="btn btn-primary">Gönder</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoys yüklə</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="invoice-form" action="/invoice" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="shop">Istifadeci kodu</label>
                                            <input type="text" name="user_code" class="form-control user_code_all" id="shop"
                                                   placeholder="Istifadeci kodu" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="file" class="form-group">
                                                <span id="fileName"></span>
                                                <span><img src="{{asset('front/img/beyenname-yukle.png')}}"
                                                           alt="beyanname-yukle"> @lang('invoice/index.file')</span>
                                                <input required type="file" class="form-control-file" id="file"
                                                       name="invoice">
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">

                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="shop">@lang('invoice/index.shop')</label>
                                            <input type="text" name="shop_name" class="form-control" id="shop"
                                                   placeholder="@lang('invoice/index.shop')" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="tip">@lang('invoice/index.product-type')</label>
                                            <select class="selectpicker form-control" name="product_type_name" id="tip"
                                                    title="@lang('invoice/index.product-type')" required>
                                                @foreach($productTypes as $productType)
                                                    <option value="{{$productType->name}}">{{$productType->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="count">@lang('invoice/index.count')</label>
                                            <input type="number" class="form-control" name="quantity" id="count"
                                                   required
                                                   placeholder="@lang('invoice/index.count')">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="value">@lang('invoice/index.product-price') (TL)</label>
                                            <input type="number" class="form-control" id="value" name="price" required
                                                   placeholder="@lang('invoice/index.product-price')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="count">@lang('invoice/index.orderNumber')</label>
                                            <input type="text" class="form-control" name="order_number" id="orderNumber"
                                                   required
                                                   placeholder="@lang('invoice/index.orderNumber')">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="value">@lang('invoice/index.orderDate')</label>
                                            <input type="date" class="form-control" id="orderDate" name="order_date"
                                                   required placeholder="@lang('invoice/index.orderDate')">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                    <button id="saveInvoice" type="button" class="btn btn-primary">Gönder</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
