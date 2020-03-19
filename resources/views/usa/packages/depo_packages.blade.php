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
                    {{--<li><a id="sipharisler" data-status="1" data-toggle="tab" href="" class="btn-effect">SİPHARİŞLER</a></li>--}}
                    <li><a id="depo_packages" data-status="1" class="btn-effect  {{$status=='packages' ? 'active' : ''}}" href="{{url('/depo_packages')}}">Depo Packages</a></li>
                    <li><a id="depo_packages" data-status="1" class="btn-effect  {{$status=='packages_confirmed' ? 'active' : ''}}" href="{{url('/depo_packages_confirmed')}}">Confirmed</a></li>
                    <li><a id="sipharisler" data-status="1" href="{{url('/invoices/get/index?status=1')}}" class="btn-effect {{$status==1 ? 'active' : ''}}">ORDERS</a></li>
                    <li><a id="anbar" data-status="2" href="{{url('/invoices/get/index?status=2')}}" class="btn-effect {{$status==2 ? 'active' : ''}} ">DEPOT</a></li>
                    <li><a id="yolda" data-status="3" href="{{url('/invoices/get/index?status=3')}}" class="btn-effect {{$status==3 ? 'active' : ''}}">ON THE WAY</a></li>
                    <li><a href="{{url('sack')}}" class="btn-effect">SACKS</a></li>
                    <li><a href="{{url('dispatch')}}" class="btn-effect">DISPATCH</a></li>
                    <li><a href="{{url('invoices/get/stored')}}" class="btn-effect {{$status==33 ? 'active' : ''}}">STORED BOXES</a></li>
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
                        add package
                    </a></li>
                <?php
                    }
                ?>

                {{--<li><a type="button"  class="btn-effect orange_border" href="/invoices/waybillAll">
                        Print All waybill
                    </a></li>--}}
                <li style="display:{{$display}}">
                    <form action="{{url('invoices/change-all-stauses')}}" method="post" id="selected_inputs">

                        <input value="send selected" type="submit" class="btn-effect orange_inner" >

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
                        @php($count=1)
                        <thead>
                        <tr>
                            <th></th>
                            <th>User ID</th>
                            <th>User Code</th>
                            <th>Invoice ID</th>
                            <th>Tracking</th>
                            <th>Weight / Dims</th>
                            <th>Store</th>
                            <th>Quantity</th>
                            <th>Contents</th>
                            <th>Value & Data</th>
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
                        <tr class="tr_{{$value->id}}">
                            <td>{{$count++}}</td>
                            <td>
                                {{$value->user_id}}
                            </td>
                            <td>
                                {{$value->user_code}}
                            </td>
                            <td>
                                {{$value->invoice_id}}
                            </td>

                            <td>
                                {{$value->tracking}}
                            </td>
                            <td>
                                {{$value->weight}} <br />
                                {{$value->length}}x{{$value->width}}x{{$value->height}}
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
                                    echo "WAITING";
                                    ?>
                                    <a type="button" target="_blank" class="btn btn-success" id="edit-item-package" data-item-id="{{$value->id}}">Confirm</a>
                                <?php
                                }elseif($value->status_id == 1){
                                    echo "ACTIVE";
                                    ?>
                                    <button type="button"  class="blue btn-effect printHawb" id="printHawb" data-id="{{$value->invoice_id}}">
                                        <i class="fa fa-print"></i>
                                    </button>
                                    <?php
                                        if($value->price>0){
                                    ?>
                                    <a type="button"  class="btn btn-success addPackage" data-id="{{$value->id}}">Add Depo</a>
                                    <?php }?>
                                <?php
                                }
                                ?>
                                 <a type="button" target="_blank" class="btn btn-danger deletePackage" data-item-id="{{$value->id}}">Delete</a>

                            </td>
                        </tr>
                        @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="edit-modal-package" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Change Package</h4>
            </div>
            <div class="modal-body" id="edit-modal-package-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updatePackageSubmit">Save changes</button>
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

<!----------Package------------->
<div class="modal fade" id="addPackage" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">ADD PACKAGE</h5>
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
                                        <label for="shop">User ID</label>
                                        <input type="text" name="user_code" class="form-control user_code_all" id="shop"
                                               placeholder="User ID" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="shop">User text</label>
                                        <input type="text" name="user_name" class="form-control user_code_all" id="shop"
                                               placeholder="User text" required>
                                    </div>
                                    {{--<div class="form-group">
                                        <label for="file" class="form-group">
                                            <span id="fileName"></span>
                                            <span><img src="{{asset('front/img/beyenname-yukle.png')}}"
                                                       alt="upload-invoice"> Invoice</span>
                                            <input type="file" class="form-control-file" id="file"
                                                   name="invoice">
                                        </label>
                                    </div>--}}
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
                                        <input type="number" class="form-control" id="value" name="price" placeholder="Product price">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="count">Tracking</label>
                                        <input type="text" class="form-control" name="order_number" id="orderNumber"
                                               required
                                               placeholder="Tracking No">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="value">Order date</label>
                                        <input type="date" class="form-control" id="orderDate" name="order_date" value="<?= date("Y-m-d")?>"
                                               required placeholder="Order date">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Weight (kq)</label>
                            <input type="number" class="form-control" name="weight" placeholder="Ex: 3.401"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Length (inch)</label>
                            <input type="number" class="form-control" name="length" placeholder="Ex: 3.401"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Width (inch)</label>
                            <input type="number" class="form-control" name="width" placeholder="Ex: 5.40"/>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Height (inch)</label>
                            <input type="number" class="form-control" name="height" placeholder="Ex: 14.40"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button id="savePackage" type="button" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

@endsection
