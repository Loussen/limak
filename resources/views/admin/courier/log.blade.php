@extends('layouts/admin')
@section('styles')
    <style type="text/css">
        @media (min-width: 1200px) {
            .container {
                max-width: 100% !important;
            }
        }
        .limac-modal {
            position: fixed;
            top: 0;
            left: 0;
            background: rgba(0, 0, 0, 0.4);
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0;
            -webkit-transition: 400ms;
            -moz-transition: 400ms;
            -ms-transition: 400ms;
            -o-transition: 400ms;
            transition: 400ms;
        }

        .limac-modal.show-modal {
            z-index: 9999;
            opacity: 1;
        }

        .limac-modal-dialog {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translate(-50%, -50%);
            background: #fff;
            width: 1000px;
            max-height: 90%;
            overflow-y: auto;
            border-radius: 3px;
            padding: 15px;
            -webkit-transition: 200ms;
            -moz-transition: 200ms;
            -ms-transition: 200ms;
            -o-transition: 200ms;
            transition: 200ms;
        }

        .limac-modal.show-modal .limac-modal-dialog {
            transform: translate(-50%, 0);
        }

        .limac-modal-close {
            position: absolute;
            top: 13px;
            right: 13px;
            cursor: pointer;
        }

        .limac-modal-close span {
            font-size: 17px;
        }

        .limac-model-header h3 {
            padding-bottom: 12px;
            font-weight: bolder;
            text-align: center;
        }

        .table td {
            color: #000;
            font-weight: 400;
        }

        .loading {
            border: 4px solid #f3f3f3;
            border-radius: 50%;
            border-top: 4px solid #3498db;
            width: 26px;
            height: 26px;
            -webkit-animation: spin-data-v-d1d35872 1s linear infinite;
            animation: spin-data-v-d1d35872 1s linear infinite;
            position: absolute;
            top: 45px;
            left: 49%;
        }
    </style>
@endsection
@section('content')
    <div id="courier-container">
        <div class="content-wrapper animatedParent animateOnce">
            <div style="width: 100%;" class="container">
                <section class="paper-card">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="box text-left">
                                <div class="box-header">
                                    <h3 class="box-title text-center"><strong>Kuryer - Arxiv</strong></h3>
                                    <br>
                                </div>
                                <div class="box-body no-padding">
                                    @if (count($couriers) === 0)
                                        <div class="empty-content"><p class="text-center" style="font-size: 18px;font-weight: bold;">Məlumat yoxdur</p></div>
                                    @endif

                                    @if (count($couriers) > 0)
                                        <table class="table dtr-details" width="100%">
                                            <tr>
                                                <th>Sifarişçinin adı</th>
                                                <th>Sifarişçinin soyadı</th>
                                                <th>Sifarişçinin emaili</th>
                                                <th>Sifarişçinin nömrəsi</th>
                                                <th>Sifarişçinin ünvanı</th>
                                                <th>İnvoyslar</th>
                                            </tr>
                                            @foreach($couriers as $order)
                                                <tr>
                                                    <td>{{$order->users->name}}</td>
                                                    <td>{{$order->users->surname}}</td>
                                                    <td>{{$order->users->email}}</td>
                                                    <td>{{$order->phone}}</td>
                                                    <td>{{$order->city}} {{$order->district}} {{$order->street}} {{$order->village}} {{$order->home}}</td>
                                                    <td><button onclick="productsList({{$order['invoices']}})" class="btn btn-primary">Məhsullar</button></td>
                                                </tr>
                                            @endforeach
                                        </table>
                                     @endif
                                </div>
                                <div class="col-md-12">
                                    {{ $couriers->appends($params)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="limac-modal">
            <div class="limac-modal-dialog">
                <div class="limac-model-header">
                    <h3>İnvoyslar</h3>
                    <div id="closeModal" class="limac-modal-close">
                        <span class="icon-close"></span>
                    </div>
                </div>
                <div class="limac-modal-body">

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        function productsList (data) {
            var limacModal = $('.limac-modal');
            var modalData = '';

            limacModal.addClass('show-modal');

            data.forEach((invoice, index) => {
                modalData += `
                <div>
                    <div class=""><h3 style="display: inline-block;margin-right: 10px;">Invoys - ${count(index)}:</h3> <a href="${invoice.file}" target="_blank" class="btn btn-primary btn-sm">Sənəd</a></div>
                    <div class="text-center"><h2 style="margin-bottom: 10px;">Məhsul</h2></div>
                    <table style="margin-bottom: 10px;" class="table table-striped">
                        <tr>
                            <th>Tip</th>
                            <th>Say</th>
                            <th>Mağaza</th>
                            <th>Qeyd</th>
                        </tr>
                        <tr>
                            <td>${invoice.products.products_type_id ? invoice.products.products_type.name : invoice.products.product_type_name}</td>
                            <td>${invoice.products.quantity}</td>
                            <td>${invoice.products.shop_name}</td>
                            <td>${invoice.products.description}</td>
                        </tr>
                    </table>
                    <br>
                </div>
                `;
            });

            $('.limac-modal-body').html(modalData);

            $("#closeModal").click(function () {
                limacModal.removeClass('show-modal');
            });

            function count(index) {
                return index + 1;
            }
        }
    </script>
@endsection
