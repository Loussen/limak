@extends('usa.layouts.admin_anbar')

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
                                        <input name="fullname" type="text" class="form-control" placeholder="Name Lastname">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input name="user_code" type="text" class="form-control" placeholder="User code">
                                    </div>
                                </div>
                                <input type="hidden" name="status" value="{{$status}}">
                                <div class="col-md-1">
                                    <input class="btn-effect forSearch" type="submit" value="Search">
                                </div>
                                <div class="col-md-1">
                                    <a href="{{url('logout')}}" class="btn-effect" style="background: red;padding: 10px"><i class="fa fa-sign-out"></i> Exit</a>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </nav>
    </header>
    <div class="content">
        <div id="siparisler" class="custom-container">
            <div class="">
                <div class="buttons_list content">
                    <ul class="buttons_list">
                        @php
                            if($last30type=='higher') $status=33;
                        @endphp
                        {{--<li><a id="sipharisler" data-status="1" data-toggle="tab" href="" class="btn-effect">SİPHARİŞLER</a></li>--}}
                        <li><a id="depo_packages" data-status="1" class="btn-effect  {{$status=='packages' ? 'active' : ''}}" href="{{url('/depo_packages')}}">Depo Packages</a></li>
                        <li><a id="depo_packages_confirmed" data-status="1" class="btn-effect  {{$status=='packages_confirmed' ? 'active' : ''}}" href="{{url('/depo_packages_confirmed')}}">Confirmed</a></li>
                        <li><a id="sipharisler" data-status="1" href="{{url('/invoices/get/index?status=1')}}" class="btn-effect {{$status==1 ? 'active' : ''}}">ORDERS</a></li>
                        <li><a id="anbar" data-status="2" href="{{url('/invoices/get/index?status=2')}}" class="btn-effect {{$status==2 ? 'active' : ''}} ">DEPOT</a></li>
                        <li><a id="yolda" data-status="3" href="{{url('/invoices/get/index?status=3')}}" class="btn-effect {{$status==3 ? 'active' : ''}}">ON THE WAY</a></li>
                        <li><a id="gomruk" data-status="11" href="{{url('/invoices/get/index?status=11')}}" class="btn-effect {{$status==11 ? 'active' : ''}}">CUSTOM</a></li>
                        <li><a href="{{url('sack')}}" class="btn-effect">SACKS</a></li>
                        <li><a href="{{url('dispatch')}}" class="btn-effect">DISPATCH</a></li>
                        <li><a href="{{url('invoices/get/stored')}}" class="btn-effect active">STORED BOXES</a></li>
                        <li><a href="#" class="btn-effect">RETURNS</a></li>
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
                            Count: {{count($data)}}
                        </p>
                    </li>
                    <li>
                        <p class="badge" style="padding: 5px 10px;margin: 11px 0 0;display:{{$display}}">
                            @php($weight=0)
                            @foreach($data as $value)
                                @php($weight=$weight+$value->weight)
                            @endforeach
                            Weight: {{$weight}} kg
                        </p>
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
                                <th>No</th>
                                <th>Type</th>
                                <th>Last30</th>
                                <th>User name,surnam, code</th>
                                <th>Phone,  E-maili</th>
                                <th>Corporate</th>
                                <th>Purchase No</th>
                                <th>Shop</th>
                                <th>Product count</th>
                                <th>Content</th>
                                <th>Price</th>
                                <th>Comment</th>
                                <th>Waybl</th>
                                <th></th>

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
                                @php($last30days=number_format($value->price + $value->shipping_price, 2))
                                <tr class="tr_{{$value->id}}
                                @if($value->status_id==13)
                                        danger
@elseif($value->status_id==12 && ($value->user_last30 + $value->price + $value->shipping_price)>1000)
                                        danger
@else success
                                        @endif;
                                        ">

                                    @php($price=$value->quantity*$value->price)
                                    <input type="hidden" value="{{$price}}">
                                    <td>{{$count++}}</td>
                                    <td><b>{{$value->status_id==12?' Auto': ' Manual'}}</b>
                                    </td>
                                    <td> {{number_format((float)($value->user_last30 + $value->price + $value->shipping_price), 2, '.', '')}}
                                    </td>
                                    <td>
                                        <?php
                                        if($value->client_id>0 and $value->corporate==1){
                                        ?>
                                        {{$value->client_name}} {{$value->client_surname}}
                                        <b>1{{str_pad($value->client_id,6,"0",STR_PAD_LEFT)}}</b>
                                        <?php
                                        }elseif($value->person_id>0){
                                            ?>
                                            {{$value->person_name}} {{$value->person_surname}}
                                            <b>{{$value->auto_id}}</b>
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
                                    <td>{{intval($value->client_id)>0 ? $value->uniqid: '0'}}</td>
                                    <td>{{$value->purchase_no}}</td>
                                    <td>{{$value->shop_name}}</td>
                                    <td>{{$value->quantity}}</td>
                                    {{--<td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{!empty($value->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</td>--}}
                                    <td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{$value->product_type_name}}</td>
                                    <td>{{$value->price}}USD</td>
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
                                        @if($value->status_id==12 && (float)($value->user_last30 + $value->price + $value->shipping_price)<=1000)
                                            <a href="javascript:void(0)" data-id="{{$value->id}}" data-status="{{$value->status_id}}" class="back-invoice-storage1 pink btn-effect id_{{$value->id}}" style="display:{{$value->weight>0 ? 'show' : 'none'}}" >
                                                OK
                                            </a>
                                        @endif
                                    </td>
                                </tr>
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
                                            <label for="value">@lang('invoice/index.product-price') ($)</label>
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
