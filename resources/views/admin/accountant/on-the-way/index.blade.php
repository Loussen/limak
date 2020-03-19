@extends('layouts.admin')
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
    </style>
@endsection
@section('content')
    <div class="content-wrapper relative animatedParent animateOnce">
        <section class="paper-card">
            <div class="animated fadeInUpShort">
                <div id="search_second" class="row">
                    <div class="col-md-2">
                        <div>
                            <div class="form-group has-right-icon m-0 focused">
                                <input id="pinRoad" name="pin" class="form-control light" placeholder="FIN ilə axtar" type="text">
                                <i class="icon-search"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 text-left">
                        <div>
                            <div class="form-group has-right-icon m-0 focused">
                                <input id="telRoad" name="tel" class="form-control light" placeholder="Nömrə ilə axtar" type="text">
                                <i class="icon-search"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card no-b">
                            <div class="card-header white pb-0">
                                <div class="d-flex justify-content-between">
                                    <div class="align-self-center">
                                        <ul class="nav nav-pills mb-3" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link r-20  active show" id="w3--tab2" data-toggle="tab" href="#w3-tab2" role="tab" aria-controls="tab2" aria-selected="false">Yoldadır</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="align-self-center">
                                        <h5 style="color: #04a8f4;font-size: 16px;">İnvoyslar</h5>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body no-p">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="w3-tab2" role="tabpanel" aria-labelledby="w3-tab2">
                                        <div class="table-responsive">
                                            <table class="table tableRoad table-hover">
                                                <tbody>
                                                @if(count($dataInRoad) === 0)
                                                    <tr colspan="7" ><td style="font-size: 22px;font-weight: bold;color: #04a8f457;" class="text-center" >İnvoys yoxdur</td></tr>
                                                @endif
                                                @foreach($dataInRoad as $value)
                                                    <tr class="no-b">
                                                        <td style="width: 17%;" class="table-img">
                                                            <h5 class="font-weight-bold">{{$value->relUserProducts->users->name}} {{$value->relUserProducts->users->surname}}</h5>
                                                        </td>
                                                        <td>
                                                            <i class="icon-email" ></i>
                                                            <span>{{$value->relUserProducts->users->email}}</span>
                                                            <br>

                                                            @if($value->relUserProducts->users->userContacts)
                                                                @foreach($value->relUserProducts->users->userContacts as $contact)
                                                                    <i class="icon-phone" ></i>
                                                                    <span> {{$contact->name}}</span>
                                                                    <br>
                                                                @endforeach
                                                            @endif
                                                            <i class="icon-address-card" ></i>
                                                            <span> {{$value->relUserProducts->users->pin}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Mağaza</h6>
                                                            <span>{{$value->products->shop_name}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Məhsulun tipi</h6>
                                                            <span>{{$value->products->productTypes->name}}</span>
                                                        </td>
                                                        <td>
                                                            <h6>Məhsulun sayı</h6>
                                                            <span>{{$value->products->quantity}}</span>

                                                        </td>
                                                        <td>
                                                            <h6>Qiymət</h6>
                                                            <div style="font-size: 16px;color: #56b759;font-weight: bold;" class="d-none d-lg-block">{{$value->products->price}}</div>
                                                        </td>
                                                        <td ><div class="d-none d-lg-block">
                                                            </div></td>
                                                        <td>
                                                            <div class="d-none d-lg-block">
                                                                <span style="color: #4CAF50;font-weight: bold;"><i class="icon icon-data_usage"></i> {{$value->invoiceStatus->name}}</span><br>
                                                                <span><i class="icon icon-timer"></i> {{$value->updated_at}}</span>
                                                            </div>
                                                        </td>
                                                        <td >
                                                            <a style="cursor: pointer;" download  href="{{url($value->file)}}" class="btn-fab btn-fab-sm btn-warning shadow text-white"><i class="icon-file"></i></a>
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
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>
    <script>

        var tab = 'road';
        var table = 'tableRoad';
        var latestSearch = false;
        var defaultSearch = function () {
            $.ajax({
                'type': 'GET',
                url: "{{url('/admin/accountant-way')}}?tab=" + tab,
                success: function ($response) {
                    $('.' + table).html($response);
                }
            });
            blickCheck();
        }
        $('.search-container').find('input').each(function() {
            $(this).on('keyup', function(e){
                if(e.keyCode === 13)
                {
                    console.log('key press');
                    $('#searchForm').submit();
                }
            });
        });

        getRoadData = function () {
            $.ajax({
                'type': 'GET',
                url: "{{url('/admin/accountant-way')}}?tab=road&pin="+ $('#pinRoad').val()+ '&tel=' + $('#telRoad').val(),
                success: function($response) {
                    if($response.length === 17) {
                        $('.tableRoad').html('<tr colspan="7" ><td style="font-size: 22px;font-weight: bold;color: #04a8f457;" class="text-center" >İnvoys yoxdur</td></tr>');
                    } else {
                        $('.tableRoad').html(`<tbody>` + $response + `</tbody>`);
                    }
                }
            });
            blickCheck();
        }

        $('#pinRoad').keyup(delay(function(e){
            e.preventDefault();
            getRoadData();
            latestSearch = getRoadData;
        }, 200));

        $('#telRoad').keyup(delay(function(e){
            e.preventDefault();
            getRoadData();
            latestSearch = getRoadData;
        }, 200));

        function delay(callback, ms) {
            var timer = 0;
            return function() {
                var context = this, args = arguments;
                clearTimeout(timer);
                timer = setTimeout(function () {
                    callback.apply(context, args);
                }, ms || 0);
            };
        }

        $('#w3--tab2').click(function() {
            getRoadData();
            tab = 'road';
            table = 'tableRoad';
            $('#search_first').css('display', 'none');
            $('#search_second').css('display', 'flex');
        });
    </script>
@endsection
