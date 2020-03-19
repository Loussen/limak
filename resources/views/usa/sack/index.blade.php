<?php
use Illuminate\Support\Facades\Session;
?>
@extends('tr.layouts.admin_anbar_tr')
@section('styles')
    <link rel="stylesheet" href="{{asset('tr/assets/css/app.css')}}">

    <link href="{{asset('js/sweetalert/sweetalert2.min.css')}}"/>
    <style>
        @media (min-width: 1200px) {
            .container {
                max-width: 100% !important;
            }
        }
        .box-body tr td {
            text-align: left;
        }
        .table tr th {
            text-align: left;
        }
        td h6 {
            color: #101010;
        }
        table.table tbody td {
            padding: 24px !important;
            border-bottom: 1px solid #59b35e38 !important;
        }
        input.light::placeholder {
            color: rgba(0, 0, 0, 0.67) !important;
        }
        .fadeInUpShort{
            opacity: 100 !important;
        }
        #addInvoice .modal-dialog{
            top: 40%;
        }
        .row{
            display: block;
            width: 100%;
        }
    </style>
@endsection
@section('header')
    <header>
        <nav class="navbar navbar-default navbar-fixed-top custom" style="padding: 0; ">
            <div class="container-fluid" style="display: -webkit-box;">
                <div class="row">
                    <div class="col-md-3">
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
                    <div class="col-md-9 text-center middle content">
                        <form class="formSearch">
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input name="fullname" type="text" class="form-control" placeholder="Name Surname">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <input name="user_code" type="text" class="form-control" placeholder="User code">
                                    </div>
                                </div>
                                <input type="hidden" name="status" >
                                <div class="col-md-2">
                                    <input class="btn-effect forSearch" type="submit" value="Search">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

@endsection
@section('content')

    <div class="content relative animatedParent animateOnce">
        <div id="siparisler" class="custom-container" style="margin-top: 20px;">
            <div class="">
                <div class="buttons_list content">
                    <ul class="buttons_list">
                        @php
                            $last30type=null;
                            $status=null;
                        @endphp
                        {{--{{strpos(Request::url(), 'sack') !== false}}--}}
                        {{--<li><a id="sipharisler" data-status="1" data-toggle="tab" href="" class="btn-effect">SİPHARİŞLER</a></li>--}}
                        <li><a id="foreign" data-status="1" class="btn-effect" href="{{url('/packages')}}">Packages</a></li>
                        <li><a id="sipharisler" data-status="1" href="{{url('/invoices/get/index?status=1')}}" class="btn-effect {{$status==1 ? 'active' : ''}}">ORDERS</a></li>
                        <li><a id="anbar" data-status="2" href="{{url('/invoices/get/index?status=2')}}" class="btn-effect {{$status==2 ? 'active' : ''}} ">DEPOT</a></li>
                        <li><a id="yolda" data-status="3" href="{{url('/invoices/get/index?status=3')}}" class="btn-effect {{$status==3 ? 'active' : ''}}">ON THE WAY</a></li>
                        <li><a href="{{url('sack')}}"  class="btn-effect active">SACKS</a></li>
                        <li><a href="{{url('dispatch')}}" class="btn-effect">DISPATCH</a></li>
                        <li><a href="{{url('invoices/get/index?status=3&type=higher')}}" class="btn-effect {{$status==33 ? 'active' : ''}}">STORED BOXES</a></li>
                        <li><a href="#" class="btn-effect">RETURNS</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="custom-container">
            <section class="paper-card">
                <div class="animated fadeInUpShort">
                    <div id="search_first" class="row">
                        <div class="row">
                            <?php
                            if($dispatch!=null){
                                echo '<div class="col-md-2"><a href="'. URL::previous().'" class="btn btn-success text-right">&laquo; geri</a></div>';
                                echo "<div class='col-md-10'><h1>".$dispatch->consignment." numaralı sevkiyat: </h1></div>";
                            }
                            ?>
                        </div>
                        <div class="col-md-2 text-left">
                            <div>
                                <h1>SACKS</h1>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addInvoice"> + add</button>
                        </div>
                    </div>
                    @if(Session::has('message'))
                        <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                    @endif
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card no-b">
                                <div class="card-body no-p">
                                    <div class="table-responsive">
                                        <table class="table tableStorage table-hover">
                                            <tbody>

                                            @foreach($data as $item)
                                                <tr class="no-b">
                                                    <td> ID {{ $item->id }}</td>
                                                    <td> Invoice no {{ $item->invoice_no }}</td>
                                                    <td>
                                                        <form action="/sack/addInvoice" method="post" enctype="multipart/form-data">
                                                            {{--<input type="text" name="invoice_id"  onkeyup="this.form.submit()" class="form-control invoice_no col-6"  placeholder="Siparis numarasi"/>--}}
                                                            <input type="text" name="invoice_id" class="addInvoiceOnKeyUp" class="form-control invoice_no col-6"  placeholder="Order number"/>
                                                            <input type="hidden" name="sack_id" value="{{ $item->id }}">
                                                            <input type="hidden" name="count" value="{{$item->count}}">
                                                        </form>
                                                    </td>
                                                    <td>

                                                        Order count <a class="sack_count_{{ $item->id }}" href="/sack/invoices/{{ $item->id }}"> {{ $item->count }}</a>
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
                </div>
            </section>
        </div>
        <div class="modal fade" id="addInvoice"  role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">

                <div class="modal-content">
                    <form id="invoice-form" action="/sack" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Çuval əlavə et</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="shop">Invoys kodu</label>
                                        <input type="text" name="code" class="form-control" id="code" placeholder="Invoys kodu" required>
                                    </div>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <div class="form-group">
                                        <label for="shop">Məhsul tipi</label>
                                        <select class="form-control" id="type" name="type">
                                            @foreach($productTypes as $type)
                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-1 col-md-offset-9">
                                    <a type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</a>
                                </div>
                                <div class="col-md-1">
                                    <button id="saveInvoice" type="submit" class="btn btn-primary">Gönder</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">

                </div>
            </div>

        </div>
    </div>
    </div>


@endsection

@section('scripts')
<script>
    $("#saveSack").click(function () {
        var $invoiceForm = $('#sack-form');
        var formData = $invoiceForm.serializeArray();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                method: "POST",
                url: '/invoices',
                data: formData,
                processData: false,
                contentType: false,
                success: function (result) {
                    if (result.status === 200) {

                    }
                }
            });
        ;
    });
</script>
@endsection
