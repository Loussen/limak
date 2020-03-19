<?php
use Illuminate\Support\Facades\Session;
?>
@extends('tr.layouts.admin_anbar_tr')
@section('styles')

    <link href="{{asset('js/sweetalert/sweetalert2.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('tr/assets/css/app.css')}}">
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

    <div class="content relative animatedParent animateOnce">
        <div id="siparisler" class="custom-container" style="margin-top: 20px;">
            <div class="">
                <div class="buttons_list content">
                    <ul class="buttons_list">
                        @php
                            $status=null;
                        @endphp
                        {{--{{strpos(Request::url(), 'sack') !== false}}--}}
                        {{--<li><a id="sipharisler" data-status="1" data-toggle="tab" href="" class="btn-effect">SİPHARİŞLER</a></li>--}}
                        <li><a id="sipharisler" data-status="1" href="{{url('/invoices/get/index?status=1')}}" class="btn-effect {{$status==1 ? 'active' : ''}}">SİPHARİŞLER</a></li>
                        <li><a id="anbar" data-status="2" href="{{url('/invoices/get/index?status=2')}}" class="btn-effect {{$status==2 ? 'active' : ''}} ">ANBAR</a></li>
                        <li><a id="yolda" data-status="3" href="{{url('/invoices/get/index?status=3')}}" class="btn-effect {{$status==3 ? 'active' : ''}}">YOLDA</a></li>
                        <li><a href="{{url('sack')}}"  class="btn-effect active">ÇUVALLAR</a></li>
                        <li><a href="{{url('dispatch')}}" class="btn-effect">SEFKİYYAT</a></li>
                        <li><a href="{{url('invoices/get/index?status=3&type=higher')}}" class="btn-effect {{$status==33 ? 'active' : ''}}">SAKLANAN KOLİLER</a></li>
                        <li><a href="#" class="btn-effect">İADE EDİLENLER</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="clearfix"></div>
        <div class="custom-container">
            <section class="paper-card">
                <div class="animated fadeInUpShort">
                    <div id="search_first" class="row">
                        <div class="col-md-6 text-left">
                            <div>
                                <h1><b>{{isset($data[0])?$data[0]->invoice_no:''}}</b> Numaralı Çuvaldakı bağlamalar</h1>
                            </div>
                        </div>
                        <div class="col-md-offset-4 col-md-2 col-xs-12 text-right">
                                <form method="post" id="selected_inputs_sack_invoices">

                                    <input value="Çuvaldan çıkar" type="submit" class="btn-effect orange_inner" >

                                </form>
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
                                        <audio id="sound1" src="/sounds/error.wav"></audio>
                                        <audio id="sound2" src="/sounds/success.wav"></audio>
                                        <table class="table tableStorage table-hover">
                                            <tbody>
                                            <tr>
                                                <th>Bağlama id: </th>
                                                <th>Fayl</th>
                                                <th>Tarih</th>
                                                <th>Kullanıcı</th>
                                                <th>İçerik</th>
                                                <th>Fiyat</th>
                                                <th>Çuval numarası</th>
                                                <th></th>
                                            </tr>
                                            @if(count($data)==0)
                                                <p class="alert alert-danger">Paket əlavə edilməyib</p>
                                            @endif
                                            @foreach($data as $item)
                                                <tr class="no-b">
                                                    <td> {{ $item->invoice_id }}</td>
                                                    <td>  <a href="{{ $item->file }}" target="_blank">{{ $item->file }}</a></td>
                                                    <td> {{ $item->created_at }}</td>
                                                    <td> {{ $item->name." ".$item->surname." | ".$item->uniqid }}</td>
                                                    <td> {{ $item->product_type_name }}</td>
                                                    <td> {{ $item->price }}</td>
                                                    <td>
                                                        {{ $item->invoice_no }}
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" class="checkboxes" id="checkbox_{{$item->id}}" name="selected_invoices[]" value="{{$item->id}}">
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
    </div>


@endsection