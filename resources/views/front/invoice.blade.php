{{--<htmL>
<body>
<form method="post" action="{{route('invoice.store')}}" enctype="multipart/form-data">
    <input name="product_type_id" value="1" />
    <input name="price" value="12.45" />
    <input name="quantity" value="3" />
    <input name="shop_name" value="Test" />
    <input name="description" value="Test desc" />
    <input name="invoice" type="file" />
    <button type="submit">Submit</button>
</form>
</body>
</htmL>--}}

@extends('layouts.front')

@section('content')
    <section class="page-block">
        <div class="container">
            <div class="page-head">
                <img src="{{asset('front/img/beyanname-yukle.png')}}" alt="beyanname-yukle">
                <h1>@lang('invoice/index.upload-invoice')</h1>
            </div>

            <div style="display: none;" class="alert alert-success" id="invoice-success">
            </div>

            <div class="block">
                <div class="invoice-heading">
                    <p>@lang('invoice/index.invoice-heading')</p>
                </div>
                <form id="invoice-form" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="shop">@lang('invoice/index.shop')</label>
                                        <input type="text" name="shop_name" class="form-control" id="shop" placeholder="@lang('invoice/index.shop')" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="tip">@lang('invoice/index.product-type')</label>
                                        <select class="selectpicker form-control" name="product_type_id" id="tip" title="@lang('invoice/index.product-type')" required>
                                            @foreach($productTypes as $productType)
                                                <option value="{{$productType->id}}">{{$productType->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="count">@lang('invoice/index.count')</label>
                                        <input type="number" class="form-control" name="quantity" id="count" required
                                               placeholder="@lang('invoice/index.count')">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="value">@lang('invoice/index.product-price') (TL)</label>
                                        <input type="number" class="form-control" id="value" name="price" required placeholder="@lang('invoice/index.product-price')">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="count">@lang('invoice/index.orderNumber')</label>
                                        <input type="text" class="form-control" name="order_number" id="orderNumber" required
                                               placeholder="@lang('invoice/index.orderNumber')">
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 col-xs-12">
                                    <div class="input-group">
                                        <label for="value">@lang('invoice/index.orderDate')</label>
                                        <input type="date" class="form-control" id="orderDate" name="order_date" required placeholder="@lang('invoice/index.orderDate')">
                                    </div>
                                </div>
                                {{--<div class="col-md-4 col-sm-12 col-xs-12">--}}
                                    {{--<div class="input-group">--}}
                                        {{--<label for="value">@lang('invoice/index.promo_kod')</label>--}}
                                        {{--<input type="text" class="form-control" id="promo_code" name="promo_code"  placeholder="@lang('invoice/index.promo_kod')">--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="input-group">
                                <label for="description">@lang('invoice/index.comment')</label>
                                <textarea name="description" class="form-control" rows="5" id="description"
                                          placeholder="@lang('invoice/index.comment')"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 count-end">
                            <button type="button" id="send-invoice" class="btn-effect">@lang('invoice/index.send')</button>
                            <label for="file" class="form-group">
                                <span id="fileName"></span>
                                <span><img src="{{asset('front/img/beyenname-yukle.png')}}" alt="beyanname-yukle"> @lang('invoice/index.file')</span>
                                <input type="file" class="form-control-file" id="file" name="invoice">
                            </label>
                            <svg data-toggle="modal" data-target="#exampleModal" style="vertical-align: middle;margin-top: -5px;font-size: 21px;color: #2e6da4;" aria-hidden="true" data-prefix="fas" data-icon="question-circle" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-question-circle fa-w-16 fa-fw fa-2x"><path fill="currentColor" d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zM262.655 90c-54.497 0-89.255 22.957-116.549 63.758-3.536 5.286-2.353 12.415 2.715 16.258l34.699 26.31c5.205 3.947 12.621 3.008 16.665-2.122 17.864-22.658 30.113-35.797 57.303-35.797 20.429 0 45.698 13.148 45.698 32.958 0 14.976-12.363 22.667-32.534 33.976C247.128 238.528 216 254.941 216 296v4c0 6.627 5.373 12 12 12h56c6.627 0 12-5.373 12-12v-1.333c0-28.462 83.186-29.647 83.186-106.667 0-58.002-60.165-102-116.531-102zM256 338c-25.365 0-46 20.635-46 46 0 25.364 20.635 46 46 46s46-20.636 46-46c0-25.365-20.635-46-46-46z" class=""></path></svg>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @lang('invoice/index.notificationModalText')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript" src="{{asset('front/js/validation/dist/jquery.validate.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('front/js/validation/dist/localization/messages_'. str_replace('_', '-', app()->getLocale()) .'.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#invoice-form').validate({
                messages: {
                    shop_name: $.validator.messages.shop_name,
                    product_type_id: $.validator.messages.product_type_id,
                    quantity: $.validator.messages.quantity,
                    price: $.validator.messages.price,
                    invoice: $.validator.messages.invoice,
                    order_number: $.validator.messages.order_number,
                    order_date: $.validator.messages.order_date,
                }
            });

            $("#send-invoice").click(function () {
                $invoiceForm = $('#invoice-form');

                if ($invoiceForm.valid()) {
                    var frmData = new FormData();
                    var formData = $invoiceForm.serializeArray();
                    var invoice = $('#file')[0].files[0];

                    $.each(formData, function(_, kv) {
                        frmData.append(kv.name, kv.value);
                    });

                    frmData.append('invoice', invoice);

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        method: "POST",
                        url: '/invoice',
                        data: frmData,
                        processData: false,
                        contentType: false,
                        success: function (result) {
                            if (result.status === 200) {
                                $("#invoice-success").fadeTo(2000, 500).text(result.message).fadeOut(500, function(){
                                    $("#invoice-success").fadeOut(500);
                                });
                                $("#fileName").text("");
                                $('#invoice-form').find("input[type=text], textarea, input[type=file], input[type=number]").val("");
                            }
                        }
                    });
                }
            });

            $("#file").change(function () {
                var invoice = $(this)[0].files[0];

                $("#fileName").text(invoice.name);
            })
        });

        $('#question-mark').click(function() {
            console.log('test');
            swal('asda asdasd asdsa ');
        });
    </script>
@endsection
