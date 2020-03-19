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
        .table.table-of-list td {
            padding: 12px !important;
            color: #000000a3;
            font-weight: bold;
            text-align: left;
        }
        @media (min-width: 1024px) {
            .modal-dialog {
                max-width: 1000px;
                margin: 1.75rem auto;
            }
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper relative animatedParent animateOnce">
        <section class="paper-card">
            <div class="row" >
                <div class="col-md-12">
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
                </div>
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table tableRoad table-hover">
                            <tbody>
                            @if(count($data) === 0)
                                <tr colspan="7" ><td style="font-size: 22px;font-weight: bold;color: #04a8f457;" class="text-center" >yoxdur</td></tr>
                            @endif
                            @foreach($data as $value)
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
                                    <td width="130px">
                                        <h6>Alıcı</h6>
                                        <span>{{$value->buyer->name}} {{$value->buyer->surname}}</span>
                                    </td>
                                    <td>
                                        <h6>Qiymət</h6>
                                        <div style="font-size: 16px;color: #56b759;font-weight: bold;" class="d-none d-lg-block">{{$value->relUserProducts->price}}</div>
                                    </td>
                                    <td>
                                        <h6>Mesaj</h6>
                                        <div style="font-size: 16px;color: #56b759;font-weight: bold;" class="d-none d-lg-block">{{$value->note}}</div>
                                    </td>
                                    <td>
                                        <div class="d-none d-lg-block">
                                            <span style="color: #d43838;font-weight: bold;"><i class="icon icon-data_usage"></i> {{$value->relUserProducts->statuses->name}}</span><br>
                                            <span><i class="icon icon-timer"></i> {{$value->updated_at}}</span>
                                        </div>
                                    </td>
                                    <td >
                                        <a style="cursor: pointer;" data-id="{{$value->relUserProducts->id}}" class="products_advanced btn-fab btn-fab-sm btn-primary shadow text-white"><i class="icon-more_vert"></i></a>
                                    </td>
                                    <td >
                                        <a style="cursor: pointer;" data-id="{{$value->relUserProducts->id}}" data-trans="{{$value->relUSerProducts->transaction_id}}" class="transaction_refuse btn-fab btn-fab-sm btn-primary shadow text-white"><i class="icon-cash-register"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <!-- Modal -->
        <div id="product_modal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-center" id="exampleModalLabel">Məhsullar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Bağla</button>
                        {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset('js/sweetalert/sweetalert2.all.min.js')}}"></script>

    <script>
        $('#pinRoad').keyup(delay(function(e){
            e.preventDefault();
            getRoadData();
        }, 200));

        $('#telRoad').keyup(delay(function(e){
            e.preventDefault();
            getRoadData();
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

        var bindMoreClick = function() {
            $('.products_advanced').unbind('click');
            $(document).on('click','.products_advanced', function () {
                $.ajax({
                    'type': 'GET',
                    url: '{{url("/admin/rejection-for-accountant/products")}}/' + $(this).attr('data-id'),
                    success: function (response) {
                        if(response) {
                            $('.modal-body').html(response);
                            $('#product_modal').modal('show');
                        }
                    }
                });
            });
        };
        bindMoreClick();
        $(document).on('click', '.transaction_refuse', function() {
            console.log($(this).attr('data-id'), $(this).attr('data-trans'));
            var id = $(this).attr('data-id');
            var transaction = $(this).attr('data-trans');
            swal({
                title: 'Əminsizmi?',
                text: "İstifadəçinin ödənişi hesabına geri qaytarılacaqdır!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Təsdiqlə'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        type: 'POST',
                        url: '{{url("/admin/rejection-accept-accountant")}}',
                        data: {id: id, transaction: transaction},
                        success: function(response) {
                            getRoadData();
                        }
                    });
                    swal(
                        'Təsdiqləndi!',
                        'seçilmiş invoys sonrakı mərhələyə əlavə edildi',
                        'success'
                    )
                }
            })
        });

        getRoadData = function () {
            $.ajax({
                'type': 'GET',
                url: "{{url('/admin/rejection-for-accountant')}}?pin="+ $('#pinRoad').val()+ '&tel=' + $('#telRoad').val(),
                success: function($response) {
                    $('.tableRoad').html($response);
                }
            });
            bindMoreClick();
        }
    </script>
@endsection
