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
                        <li><a href="{{url('invoices/get/stored')}}" class="btn-effect {{$status==33 ? 'active' : ''}}">STORED BOXES</a></li>
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
                            Weight: {{$weight}} kq
                        </p>
                    </li>
                    <li>
                        {{--<a type="button" target="_blank"  class="btn-effect" href="/anbar-usa">--}}
                            {{--Bill--}}
                        {{--</a>--}}
                    </li>
                    <li><a type="button" data-toggle="modal" data-target=".mushteri" class="btn-effect blue_border" href="">
                            Send to Baku office
                        </a></li>
                    <li><a type="button" data-toggle="modal" data-target=".habardar" class="btn-effect orange_border" href="">
                            warn customer
                        </a></li>
                    <li style="display:{{$display}}"><a class="btn-effect blue_inner" data-toggle="modal" data-target="#addInvoice" href="">
                            upload invoice
                        </a></li>
                    <li><a type="button"  class="btn-effect orange_border" href="/invoices/waybillAll">
                            Print All waybill
                        </a>
                    </li>

                    <?php
                        if($status == 3){
                            ?>
                        <li>
                            <form action="{{url('invoices/change-all-stauses-custom')}}" method="post" id="selected_inputs_custom">

                                <input value="SEND SELECTED CUSTOM" type="submit" class="btn-effect" >

                            </form>
                        </li>
                    <?php
                        }else{
                            ?>
                        <li style="display:{{$display}}">
                            <form action="{{url('invoices/change-all-stauses')}}" method="post" id="selected_inputs">

                                <input value="send selected" type="submit" class="btn-effect orange_inner" >

                            </form>
                        </li>
                    <?php
                        }
                    ?>





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
                                <th>Order</th>
                                <th>Customer name,  surname, code</th>
                                <th>Customer phone,  E-mail</th>
                                <th>ID Code</th>
                                <th>Tracking</th>
                                <th>Purchase No</th>
                                <th>Shop</th>
                                <th>Quantity</th>
                                <th>Content</th>
                                <th>Price</th>
                                <th>Record</th>
                                <th>Waybl</th>
                                <th></th>
                                @if($status==2 or $status==3)
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
                                        class="text-center">No Invoice
                                    </td>
                                </tr>
                            @endif
                            @php($count=1)
                            @foreach($data as $value)
                                @php($last30days=number_format($value->price / $tryToUsd + $value->shipping_price, 2))

                                @if($last30type=='higher')
                                    @if($last30days>1000)
                                        <tr class="tr_{{$value->id}}" style="{{$value->by_bus==1 ? 'background:#e07f7f;color:white' : ''}}">
                                            <td>{{$count++}}</td>
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
                                                   <b>{{substr($value->person_company,0,1)}}{{$value->person_id}}</b>
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
                                            <td>{{$value->order_tracking_number}}</td>
                                            <td>{{$value->purchase_no}}</td>
                                            <td>{{$value->shop_name}}</td>
                                            <td>{{$value->quantity}}</td>
                                            {{--<td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{!empty($value->productTypes) ? $value->products->productTypes->name: $value->products->product_type_name}}</td>--}}
                                            <td style="max-width: 220px;overflow: hidden;word-break: break-all;">{{$value->product_type_name}}</td>
                                            <td>{{$value->price}}*{{$value->quantity}} USD</td>
                                            <td>
                                                {{ $value->comment }}
                                            </td>
                                            <td><a href="/invoices/waybill/{{$value->id}}"
                                                   target="_blank" class="btn-info"> Waybill </a></td>
                                            <td style="width: 14%">
                                                @if($status==2)
                                                    {{--<button class="lite-red btn-effect changeVehicle" data-bus="{{$value->by_bus}}" data-id="{{$value->id}}">
                                                        --}}{{--<img src="{{asset('/admin/img/time.png')}}">--}}{{--
                                                        <i class="fa fa-bus"></i>
                                                    </button>--}}
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
                                            @if($status==2 or $status==3)
                                            <td>
                                                <input type="checkbox" class="checkboxes" id="checkbox_{{$value->id}}" name="selected_invoices[]" value="{{$value->id}}">
                                            </td>
                                            @endif
                                        </tr>
                                        @endif
                                    @else
                                    @if($last30days<1000)
                                        <tr class="tr_{{$value->id}}" style="{{$value->by_bus==1 ? 'background:#e07f7f;color:white' : ''}}">
                                            @php($price=$value->quantity*$value->price)
                                            <input type="hidden" value="{{$price}}">
                                            <td>{{$count++}}</td>
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
                                                <b>{{substr($value->person_company,0,1)}}{{$value->person_id}}</b>
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
                                            <td>{{$value->order_tracking_number}}</td>
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
                                                {{--<button class="lite-red btn-effect changeVehicle" data-bus="{{$value->by_bus}}" data-id="{{$value->id}}">
                                                    --}}{{--<img src="{{asset('/admin/img/time.png')}}">--}}{{--
                                                    <i class="fa fa-bus"></i>
                                                </button>--}}
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
                                                <a href="javascript:void(0)" data-id="{{$value->id}}" data-status="{{$value->status_id}}" class="submit-invoice-storage1 pink btn-effect id_{{$value->id}}" style="display:{{$value->weight>0 ? 'show' : 'none'}}" >
                                                    ok
                                                </a>
                                                @endif
                                            </td>
                                            @if($status==2 or $status==3)
                                            <td>
                                                <input type="checkbox" class="checkboxes" id="checkbox_{{$value->id}}" name="selected_invoices[]" value="{{$value->id}}">

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
                    <h5>SEND TO BAKU</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('/admin/img/Forma1.png')}}" alt="close"></button>
                </div>
                <div class="modal-body">
                    <form id="purchaseForm">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input type="text" name="purchase_no" class="form-control" id="id_code" placeholder="ID Code">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                                <input type="submit" id="purchase_submit" class="btn-effect green" value="Send">
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
                    <h5>WARN CUSTOMER</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><img src="{{asset('/admin/img/Forma1.png')}}" alt="close"></button>
                </div>
                <div class="modal-body">
                    <form id="invoiceless_form">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="input-group">
                                    <input type="text" name="user_uid" class="form-control" id="code" placeholder="Customer code">
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                                <input type="submit" id="" class="send-invoice btn-effect green" value="Send">
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    {{--<button id="hawbUpdateBtn" type="button" class="btn btn-primary">Gönder</button>--}}
                    <button id="" type="button" class="btn btn-primary hawbUpdateBtn">Send</button>
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
                                <label>Weight (kq)</label>
                                <input type="number" class="form-control" name="weight" placeholder="Ex: 3.401"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Length (sm)</label>
                                <input type="number" class="form-control" name="length" placeholder="Ex: 3.401"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Width (sm)</label>
                                <input type="number" class="form-control" name="width" placeholder="Ex: 5.40"/>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Height (sm)</label>
                                <input type="number" class="form-control" name="height" placeholder="Ex: 14.40"/>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="hawbBtn" type="button" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Invoice</h5>
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
                                            <label for="shop">User code</label>
                                            <input type="text" name="user_code" class="form-control user_code_all" id="shop"
                                                   placeholder="User code" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="file" class="form-group">
                                                <span id="fileName"></span>
                                                <span><img src="{{asset('front/img/beyenname-yukle.png')}}"
                                                           alt="upload-invoice"> Invoice</span>
                                                <input type="file" class="form-control-file" id="file"
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
                                            <label for="shop">Shop name</label>
                                            <input type="text" name="shop_name" class="form-control" id="shop"
                                                   placeholder="Shop name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="tip">Product Type</label>
                                            <input type="text" class="form-control" name="product_type_name" id="product_type_name" required placeholder="Product Type">

                                            {{--<select class="selectpicker form-control" name="product_type_id" id="tip"
                                                    title="Product Type" required>
                                                @foreach($productTypes as $productType)
                                                    <option value="{{$productType->id}}">{{$productType->name}}</option>
                                                @endforeach
                                            </select>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="count">Quantity</label>
                                            <input type="number" class="form-control" name="quantity" id="count"
                                                   required
                                                   placeholder="Quantity">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="value">Product price (USD)</label>
                                            <input type="number" class="form-control" id="value" name="price" required
                                                   placeholder="Product price">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="count">Purchase number</label>
                                            <input type="text" class="form-control" name="order_number" id="orderNumber"
                                                   required
                                                   placeholder="Purchase number">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xs-12">
                                        <div class="form-group">
                                            <label for="value">Order date</label>
                                            <input type="date" class="form-control" id="orderDate" name="order_date"
                                                   required placeholder="Order date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button id="saveInvoice" type="button" class="btn btn-primary">Send</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
