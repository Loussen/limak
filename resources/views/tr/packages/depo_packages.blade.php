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
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <input name="fullname" type="text" class="form-control" placeholder="İsim, Soyisime göre ara">
                                    </div>
                                </div>
                                <div class="col-md-3">
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
    <div class="content">
        <div id="siparisler" class="custom-container">
            <div class="">
                <div class="buttons_list content">
                    <ul class="buttons_list">
                        {{--<li><a id="sipharisler" data-status="1" data-toggle="tab" href="" class="btn-effect">SİPHARİŞLER</a></li>--}}
                       <li><a id="sipharisler" data-status="1" href="{{url('/invoices/get/index?status=1')}}" class="btn-effect {{$status==1 ? 'active' : ''}}">SİPHARİŞLER</a></li>
                        <li><a id="anbar" data-status="2" href="{{url('/invoices/get/index?status=2')}}" class="btn-effect {{$status==2 ? 'active' : ''}} ">Tüm ANBAR</a></li>
                        <li><a id="anbar" data-status="2" href="{{url('/invoices/get/anbar')}}" class="btn-effect">ANBAR</a></li>
                        <li><a id="anbar" data-status="4" href="{{url('/invoices/get/faturalar')}}" class="btn-effect ">Faturalar</a></li>
                        <li><a id="yolda" data-status="3" href="{{url('/invoices/get/index?status=3')}}" class="btn-effect {{$status==3 ? 'active' : ''}}">YOLDA</a></li>
                        <li><a id="gomruk" data-status="11" href="{{url('/invoices/get/index?status=11')}}" class="btn-effect {{$status==11 ? 'active' : ''}}">GÖMRÜK</a></li>
                        <li><a href="{{url('sack')}}" class="btn-effect">ÇUVALLAR</a></li>
                        <li><a href="{{url('dispatch')}}" class="btn-effect">SEFKİYYAT</a></li>
                        <li><a href="{{url('invoices/get/stored')}}" class="btn-effect {{$status==33 ? 'active' : ''}}">SAKLANAN KOLİLER</a></li>
                        {{--<li><a href="#" class="btn-effect">İADE EDİLENLER</a></li>--}}
                        <li><a id="depo_packages" data-status="1" class="btn-effect  {{$status=='packages' ? 'active' : ''}}" href="{{url('/depo_packages')}}">Paketler</a></li>
                        <li><a id="depo_packages" data-status="1" class="btn-effect  {{$status=='packages_confirmed' ? 'active' : ''}}" href="{{url('/depo_packages_confirmed')}}">Hazir Paketler</a></li>

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

                <?php
                if($status=='packages'){
                ?>
                <li>
                    <?php
                        $to_status = 2;
                        if(isset($_GET["to"])){
                            if(intval($_GET["to"])==0){
                                $to_status = 0;
                            }elseif(intval($_GET["to"])==1){
                                $to_status = 1;
                            }
                        }
                    ?>
                    <select class="form-control"  onchange="if (this.value) window.location.href=this.value">
                        <option value="/depo_packages" <?= $to_status==2?'selected':''?>> Hamisi</option>
                        <option value="/depo_packages?to=0" <?= $to_status==0?'selected':''?>>Kodsuzlar</option>
                        <option value="/depo_packages?to=1" <?= $to_status==1?'selected':''?>>Beklemede olanlar</option>
                    </select>
                </li>
                <?php
                    }
                    ?>
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
                        Ağırlık: {{$weight}} kq
                    </p>
                </li>
                <li>
                    {{--<a type="button" target="_blank"  class="btn-effect" href="/anbar-usa">--}}
                        {{--Bill--}}
                        {{--</a>--}}
                </li>
                {{--<li><a type="button" data-toggle="modal" data-target=".mushteri" class="btn-effect blue_border" href="">
                        Send to Baku office
                    </a></li>
                <li><a type="button" data-toggle="modal" data-target=".habardar" class="btn-effect orange_border" href="">
                        warn customer
                    </a></li>--}}
                <?php
                    if($status=='packages'){
                        ?>

                <li style="display:{{$display}}"><a class="btn-effect blue_inner" data-toggle="modal" data-target="#addPackage" href="">
                        Paket ekle
                    </a></li>
                <?php
                    }
                ?>

                {{--<li><a type="button"  class="btn-effect orange_border" href="/invoices/waybillAll">
                        Print All waybill
                    </a></li>--}}
                {{--<li style="display:{{$display}}">
                    <form action="{{url('invoices/change-all-stauses')}}" method="post" id="selected_inputs">

                        <input value="send selected" type="submit" class="btn-effect orange_inner" >

                    </form>
                </li>--}}

            </ul>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="custom-container table_data">
        <div class="row">
            <div class="inform col-md-12 col-sm-12 col-xs-12">
                <div class="block table-block">
                    <table class="table table-responsive table-striped anbar">
                        @php($count=1)
                        <thead>
                        <tr>
                            <th></th>
                            <th>Müşteri İD</th>
                            <th>Bağlama ID</th>
                            <th>İzleme kodu</th>
                            <th>Kilo / Ölçüler</th>
                            <th>Mağaza</th>
                            <th>Say</th>
                            <th>İçerik</th>
                            <th>Fiyat & Data</th>
                            <th>Status</th>

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
                        <tbody class="anbar_body" style="display:{{$display}}">
                        @if(count($data) === 0)
                        <tr >
                            <td colspan="13" style="font-size: 22px;font-weight: bold;text-align: center;"
                                class="text-center">No Invoice
                            </td>
                        </tr>
                        @endif
                        @foreach($data as $value)
                        <?php

                            $class_to = '';
                            if($status == 'packages' and $to_status == 1 and intval($value->user_id)==0){
                                $class_to = 'hidden';
                            }elseif($status == 'packages' and $to_status == 0 and intval($value->user_id)>0){
                                $class_to = 'hidden';
                            }
                        ?>
                        <?php
                            if($status == 'packages_confirmed'){
                                ?>
                        <tr class="tr_{{$value->id}}  {{$class_to}}" style="{{$value->by_bus==1 ? 'background:#e07f7f;color:white;' : ''}}{{$value->liquid_type==1 ? 'background:#7fbce0;color:white;' : ''}}">
                        <?php
                            }else{
                        ?>
                        <tr class="tr_{{$value->id}} {{$class_to}}">
                        <?php } ?>
                            <td>{{$count++}}</td>
                            <td>
                                {{$value->user_id}}
                            </td>
                            <td>
                                {{$value->invoice_id}}
                            </td>
                            <td>
                                {{$value->tracking}}
                            </td>
                            <td>
                                {{$value->weight}} kg<br />
                                {{$value->length}}cm x {{$value->width}}cm x {{$value->height}}cm
                            </td>
                            <td>
                                {{$value->store}}
                            </td>
                            <td>
                                {{$value->quantity}}
                            </td>
                            <td>
                                {{$value->description}} <br />
                                {{$value->order_date}}
                            </td>
                            <td>
                                {{$value->price}}
                            </td>
                            <td class="align-middle">
                                <?php
                                if($value->status_id == 0){
                                    echo "Beklemede";
                                    ?>
                                    <a type="button" target="_blank" class="btn btn-danger deletePackage" data-item-id="{{$value->id}}">Sil</a>
                                    <a type="button" target="_blank" class="btn btn-success" id="edit-item-package" data-item-id="{{$value->id}}">Kabul et</a>
                                <?php
                                }elseif($value->status_id == 1){
                                    ?>

                                    <button class="lite-green btn-effect updatePackageInvoiceDimAndWeight"  data-id="{{$value->invoice_id}}">
                                        <img src="{{asset('/admin/img/time.png')}}">
                                    </button>
                                    <button class="lite-red btn-effect changeVehiclePackage" data-bus="{{$value->by_bus}}" data-id="{{$value->invoice_id}}">
                                        {{--<img src="{{asset('/admin/img/time.png')}}">--}}
                                        <i class="fa fa-bus"></i>
                                    </button>

                                    <button type="button"  class="blue btn-effect printHawb" id="printHawb" data-id="{{$value->invoice_id}}">
                                        <i class="fa fa-print"></i>
                                    </button>
                                    <a type="button" target="_blank" class="btn btn-danger deletePackage" data-item-id="{{$value->id}}">Sil</a>

                                <a type="button"  data-id="{{$value->id}}"  class="addPackage btn btn-success id_{{$value->invoice_id}}" style="display:{{$value->weight>0 ? 'show' : 'none'}}">Anbara ekle</a>

                                <?php
                                }
                                ?>
                            </td>
                        </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>`
    </div>
</div>

<div class="modal fade" id="edit-modal-package" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Beyenname əlavə et</h4>
            </div>
            <div class="modal-body" id="edit-modal-package-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Bağla</button>
                <button type="button" class="btn btn-primary" id="savePackageInvoice">Əlavə et</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




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
                            <label>Kilo (kq)</label>
                            <input type="number" class="form-control" name="weight" placeholder="Ex: 3.401"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Uzunluk (sm)</label>
                            <input type="number" class="form-control" name="length" placeholder="Ex: 3.401"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>En (sm)</label>
                            <input type="number" class="form-control" name="width" placeholder="Ex: 5.40"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Hündürlük (sm)</label>
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

<!----------Package------------->
<div class="modal fade" id="addPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Paket ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="package-form" action="/package" method="post" enctype="multipart/form-data">
                    <div class="row"    >
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="shop">Müşteri kodu</label>
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
                                        <label for="shop">Mağaza</label>
                                        <input type="text" name="shop_name" class="form-control" id="shop"
                                               placeholder="Shop name" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="tip">İçerik</label>
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
                                        <label for="count">Say</label>
                                        <input type="number" class="form-control" name="quantity" id="count"
                                               required
                                               placeholder="Quantity">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="value">Fiyat (TL)</label>
                                        <input type="number" class="form-control" id="value" name="price" placeholder="Product price">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="count">İzleme kodu</label>
                                        <input type="text" class="form-control" name="order_number" id="orderNumber"
                                               required
                                               placeholder="Tracking No">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="value">Sifariş tarihi</label>
                                        <input type="date" class="form-control" id="orderDate" name="order_date" value="<?= date("Y-m-d")?>"
                                               required placeholder="Order date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--<div class="form-group col-md-6">
                            <label>Kilo (kq)</label>
                            <input type="number" class="form-control" name="weight" placeholder="Ex: 3.401"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Uzunluk (sm)</label>
                            <input type="number" class="form-control" name="length" placeholder="Ex: 3.401"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>En (sm)</label>
                            <input type="number" class="form-control" name="width" placeholder="Ex: 5.40"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Hündürlük (sm)</label>
                            <input type="number" class="form-control" name="height" placeholder="Ex: 14.40"/>
                        </div>--}}
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</button>
                <button id="savePackage" type="button" class="btn btn-primary">Gönder</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
