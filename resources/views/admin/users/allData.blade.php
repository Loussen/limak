@extends('layouts.admin')
@section('styles')
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

        .success{
            color: white !important;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper animatedParent animateOnce">
        <div style="width: 100%;" class="container">

            <form action="/admin/user/allData" style="margin-bottom: 15px;">
                <div class="row">
                    <div class="col-4 form-group has-right-icon m-0 focused">
                        <input id="uniqid" name="uniqid" class="form-control" placeholder="İstifadəçi kodu ilə" type="text">
                    </div>
                    <div class="col-4 form-group has-right-icon m-0 focused">
                        <input id="phone" name="phone" class="form-control" placeholder="İstifadəçi telefon nomresi ilə" type="text">
                    </div>
                    <div class="col-4 form-group has-right-icon m-0 focused">
                        <button class="btn btn-info" type="submit"> AXTAR</button>
                        <a class="btn btn-info" href="https://limak.az/admin/user/list/">Bütün istifadəçilər</a>
                    </div>
                </div>
            </form>

            @if($user_id)
            <section class="paper-card">
                <div class="row" style="font-size: 16px; font-weight: bolder; line-height: 50px;">
                    <div class="col-md-9">
                        <div class="row" style="">
                            <span class="col-md-3"> <b>Adi</b> </span>
                            <span class="col-md-3"> {{ $info->name }}</span>
                            <span class="col-md-3"> <b>Soyadi</b></span>
                            <span class="col-md-3"> {{ $info->surname }}</span>
                        </div>
                        <div class="row">
                            <span class="col-md-3"><b>Qeydiyyat tarixi</b></span>
                            <span class="col-md-3"> {{ date('Y-m-d H:i', strtotime($info->created_at)) }}</span>
                            <span class="col-md-3"><b>Telefon nomresi</b> </span>
                            <span class="col-md-3"> {{ $contact->name }}</span>
                        </div>

                        <div class="row">
                            <span class="col-md-3"> <b>Email</b> </span>
                            <span class="col-md-3"> {{ $info->email }}</span>
                            <span class="col-md-3">  <b>Müştəri kodu</b> </span>
                            <span class="col-md-3"> {{ $info->uniqid }}</span>
                        </div>
                        <div class="row">
                            <span class="col-md-3"> <b>Fin kod</b> </span>
                            <span class="col-md-3"> {{ $info->pin }}</span>
                            <span class="col-md-3">  <b>Şəxsiyyət No</b> </span>
                            <span class="col-md-3"> {{ $info->serial_number }}</span>
                        </div>

                        <div class="row">
                            <span class="col-md-3"> <b>Unvan</b></span>
                            <span class="col-md-9"> {{ $info->address }}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <span class="btn btn-success">Catdirilma Balans {{ $info->balance }}</span>
                        <br />
                        <br />
                        <textarea class="form-control" id="notification" placeholder="Bildiris göndər"></textarea>
                        <button class="btn btn-info" onclick="sendEmail('{{ $info->email }}')"> Email ilə </button>
                        <button class="btn btn-info" onclick="sendSms('{{ $contact->name }}')"> SMS ilə </button>
                    </div>
                </div>
            </section>

            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Sifarişlər</strong></h3>
                                <br>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding" >
                                <a style="font-size: 15px;" class="btn btn-light {{empty(\Illuminate\Support\Facades\Input::get('type')) ? 'success' : ''}}" href="https://www.limak.az/admin/user/allData/6">Hamısı</a>
                                <a style="font-size: 15px;" class="btn btn-light  {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') == 0 ? 'success' : ''}}" href="?type=0">
                                    Gələn sifarişlər
                                </a>
                                <a style="font-size: 15px;" class="btn btn-light  {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') == 1 ? 'success' : ''}}" href="?type=1">
                                    Sifariş verildi
                                </a>
                                <a style="font-size: 15px;" class="btn btn-light {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') == 2 ? 'success' : ''}}" href="?type=2">
                                    xaricdeki anbardadir
                                </a>
                                <a style="font-size: 15px;" class="btn btn-light {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') == 3 ? 'success' : ''}} " href="?type=3">
                                    yoldadir
                                </a>
                                <a style="font-size: 15px;" class="btn btn-light {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') == 4 ? 'success' : ''}}" href="?type=4">
                                    Baki anbari
                                </a>
                                <a style="font-size: 15px;" class="btn btn-light {{!empty(\Illuminate\Support\Facades\Input::get('courier')) && \Illuminate\Support\Facades\Input::get('courier') == 1 ? 'success' : ''}} " href="?type=0&courier=0">
                                    Kuryerde
                                </a>
                                <a style="font-size: 15px;" class="btn btn-light {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') == 5 ? 'success' : ''}}" href="?type=5">
                                    Tehvil verildi
                                </a>
                                <a style="font-size: 15px;" class="btn btn-light {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') == 7 ? 'success' : ''}}" href="?type=7">
                                    Karta qaytarlanlar
                                </a>
                                <a style="font-size: 15px;" class="btn btn-light {{!empty(\Illuminate\Support\Facades\Input::get('type')) && \Illuminate\Support\Facades\Input::get('type') == 14 ? 'success' : ''}}" href="?type=14">
                                    Səbətində
                                </a>
                                <table class="table table-hovered table-striped">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Sifarişin linki</th>
                                        <th>Qeyd</th>
                                        <th>Baglama sayi</th>
                                        <th>Tipi</th>
                                        <th>Sifariş tarixi</th>
                                        <th>Status</th>
                                        <th>Qiymət</th>
                                        <th>Qeyd</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($incr = 0)
                                    @foreach($response as $order)
                                        @foreach($order->products as $product)
                                            <tr>
                                                <td>{{++$incr}}</td>
                                                <td><a style="color: #3f51b5!important;" href="{{$product->extras->link}}"><strong>Keçid</strong></a></td>
                                                <td><a style="color: #3f51b5!important;" href="/admin/accept#/invoice-upload/{{$product->rel_user_product_id}}"><strong>Düzəliş</strong></a></td>
                                                <td>{{$product->description}}</td>
                                                <td>{{$product->quantity}}</td>
                                                <td>{{$product->product_type_name}}</td>
                                                <td>{{date('d.m.Y H:i' , strtotime($product->created_at))}}</td>
                                                <td>{{$order->is_ordered == 1 && $product->statuses->id != 6?'Sifariş verildi': $product->statuses->name}}</td>
                                                <td>{{$product->price}}</td>
                                                <td><textarea onblur="saveComment({{$product->id}}, this)">{{$product->comment}}</textarea></td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </section>

            <section class="paper-card">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="box">
                            <div class="box-header">
                                <h3 class="box-title"><strong>Mesajlar</strong></h3>
                                <br>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body no-padding">
                                <table class="table table-hovered table-striped">
                                    <thead>
                                    <tr>
                                        <th>№</th>
                                        <th>Mesaj</th>
                                        <th>Tarix</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($incr = 0)
                                        @foreach($messages as $message)
                                            <tr>
                                                <td>{{++$incr}}</td>
                                                <td>{{$message->message}} @if($info->uniqid !== $message->from_user_id) (limak) @endif</td>
                                                <td>{{$message->created_at}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </section>
            @endif
        </div>
    </div>
    <script>
        saveComment = function(id, obj){
            data ='comment='+obj.value+'&id='+id;

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", '/admin/product/comment', true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
            xhttp.send(data);
        };
        sendSms = function(phone){
            var notification = document.getElementById('notification').value;
            data ='notification='+notification+'&phone='+phone;

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", '/admin/user/sendSms', true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
            xhttp.send(data);
        };
        sendEmail = function(email){
            var notification = document.getElementById('notification').value;
            data ='notification='+notification+'&email='+email;

            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", '/admin/user/sendEmail', true);
            xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");
            xhttp.send(data);
        };
    </script>
@endsection
@section('scripts')
@endsection
