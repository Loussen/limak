<?php
use Illuminate\Support\Facades\Session;
?>
@extends('tr.layouts.admin_anbar_tr')
@section('styles')
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
                            <a class="navbar-brand" href="{{url('')}}" style="padding: 15px 15px;"><img src="{{asset('/admin/img/logo_black2.png')}}" alt="logo" class="img-responsive"></a>
                        </div>
                    </div>
                    <div class="col-md-9 text-center middle content">
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
                                <input type="hidden" name="status" >
                                <div class="col-md-2">
                                    <input class="btn-effect forSearch" type="submit" value="ARA">
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

    <div class="content relative animatedParent animateOnce" >
        <div class="custom-container">
            <div id="siparisler" class="" style="margin-top: 20px;">
                <div class="">
                    <div class="buttons_list content">
                        <ul class="buttons_list">
                            @php
                                $last30type=null;
                                $status=null;
                            @endphp
                            {{--<li><a id="sipharisler" data-status="1" data-toggle="tab" href="" class="btn-effect">SİPHARİŞLER</a></li>--}}
                            <li><a id="foreign" data-status="1" class="btn-effect" href="{{url('/packages')}}">Packages</a></li>
                            <li><a id="sipharisler" data-status="1" href="{{url('/invoices/get/index?status=1')}}" class="btn-effect {{$status==1 ? 'active' : ''}}">ORDERS</a></li>
                            <li><a id="anbar" data-status="2" href="{{url('/invoices/get/index?status=2')}}" class="btn-effect {{$status==2 ? 'active' : ''}} ">DEPOT</a></li>
                            <li><a id="yolda" data-status="3" href="{{url('/invoices/get/index?status=3')}}" class="btn-effect {{$status==3 ? 'active' : ''}}">ON THE WAY</a></li>
                            <li><a href="{{url('sack')}}" class="btn-effect">SACK</a></li>
                            <li><a href="{{url('dispatch')}}" class="btn-effect active">DISPATCH</a></li>
                            <li><a href="{{url('invoices/get/index?status=3&type=higher')}}" class="btn-effect {{$status==33 ? 'active' : ''}}">STORED BOXES</a></li>
                            <li><a href="#" class="btn-effect">RETURNS</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <section class="paper-card">
                <div class="animated block">
                    <div id="search_first" class="row">
                        <div class="col-md-2 text-left">
                            <div>
                                <h1>Dispatch</h1>
                            </div>
                        </div>
                        <div class="col-md-6 text-left">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addInvoice"> + add </button>
                        </div>
                    </div>
                </div>
                @if(Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="block transport-block">
                            <div class="card no-b">
                                <div class="card-body no-p">
                                    <div class="table-responsive">
                                        <table class="table tableStorage table-hover">
                                            <thead>
                                            <tr>
                                                <th> ID</th>
                                                <th> Consignment no</th>
                                                <th> Total price</th>
                                                <th> Sent date</th>
                                                <th> ETGB</th>
                                                <th> ETGB date</th>
                                                <th> Total weight</th>
                                                <th> Add sack</th>
                                                <th> Sack count</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($data as $item)
                                                <tr class="no-b">
                                                    <td>{{ $item->id }}</td>
                                                    <td>{{ $item->consignment }}</td>
                                                    <td>{{ $item->total_price }}</td>
                                                    <td>{{ $item->dispatch_date }}</td>
                                                    <td>{{ $item->etgb }}</td>
                                                    <td>{{ $item->etgb_date }}</td>
                                                    <td>{{ $item->total_weight }}</td>
                                                    <td>
                                                        <form action="dispatch/addSack" method="post" enctype="multipart/form-data">
                                                            <input type="text" name="invoice_no" class="form-control invoice_no col-6"  placeholder="Sack number"/>
                                                            <input type="hidden" name="dispatch_id" value="{{ $item->id }}">
                                                            <input class="btn-info col-6" type="submit" value="Add">
                                                        </form>
                                                    </td>
                                                    <td>
                                                        <a href="/sack?dispatch_id={{ $item->id }}"> {{ $item->count }}</a></td>
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
    </div>
    <div class="modal fade" id="addInvoice" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form id="invoice-form" action="/dispatch" method="post" enctype="multipart/form-data">
            <div class="modal-content">
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
                                <label for="shop">Konşimento no</label>
                                <input type="text" name="consignment" class="form-control" id="code" placeholder="Konşimento no" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">Toplam fiyat</label>
                                <input type="text" name="total_price" class="form-control" id="code" placeholder="Toplam fiyat" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">ETGB</label>
                                <input type="text" name="etgb" class="form-control" id="code" placeholder="ETGB" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">ETGB tarihi</label>
                                <input type="date" name="etgb_date" class="form-control" id="code" placeholder="ETGB tarihi" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">Gönderim tarihi</label>
                                <input type="date" name="dispatch_date" class="form-control" id="code" placeholder="Gönderim tarihi" required>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="shop">Toplam ağırlık</label>
                                <input type="text" name="total_weight" class="form-control" id="code" placeholder="Gönderim tarihi" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-secondary" data-dismiss="modal">Bağla</a>
                    <button id="saveInvoice" type="submit" class="btn btn-effect">Gönder</button>
                </div>
            </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <style>
        .content.animatedParent {
            margin-top: 120px;
        }

        .content.animatedParent .animated.block {
            padding: 20px;
        }

        .content.animatedParent .animated h1 {
            margin: 0;
        }

        #addInvoice .modal-title {
            display: inline-block;
        }

        .paper-card .block.transport-block {
            padding: 20px 0;
        }
    </style>
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
    });
</script>
@endsection
