<div class="row">
    <div class="form-group col-md-6">
        <h4>Paket ID: {{$data->id}}</h4>
        <h4>Muşteri: {{$data->user_id}}</h4>
        <label>Bağlama İD: Yok</label>
{{--
        <input type="text" class="form-control" value="{{$data->invoice_id}}" name="package_invoice_id" id="package_invoice_id" placeholder="123"/>
--}}
    </div>

    <form id="invoice-form" action="/invoice" method="post" enctype="multipart/form-data">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="shop">Istifadeci kodu</label>
                            <input type="text" name="user_code" class="form-control user_code_all" id="form_user_id" value="{{$data->user_id}}"
                                   placeholder="Istifadeci kodu" required>
                            <input type="hidden" value="{{$data->id}}" name="depo_package_id" id="package_id">

                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="file" class="form-group">
                                <span id="fileName"></span>
                                <span><img src="{{asset('front/img/beyenname-yukle.png')}}"
                                           alt="beyanname-yukle"> @lang('invoice/index.file')</span>
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
                            <label for="shop">@lang('invoice/index.shop')</label>
                            <input type="text" name="shop_name" class="form-control" id="shop" value="{{$data->store}}"
                                   placeholder="@lang('invoice/index.shop')" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="tip">@lang('invoice/index.product-type')</label>
                            <input type="text" class="form-control" name="product_type_name" value="{{$data->description}}" id="product_type_name" required placeholder="@lang('invoice/index.product-type')">
                            {{--<select class="selectpicker form-control" name="product_type_name" id="tip"
                                    title="@lang('invoice/index.product-type')" required>
                                @foreach($productTypes as $productType)
                                    <option value="{{$productType->name}}">{{$productType->name}}</option>
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
                            <label for="count">@lang('invoice/index.count')</label>
                            <input type="number" class="form-control" name="quantity" id="count" value="{{$data->quantity}}"
                                   required
                                   placeholder="@lang('invoice/index.count')">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="value">@lang('invoice/index.product-price') (TL)</label>
                            <input type="number" class="form-control" id="value" name="price" required value="{{$data->price}}"
                                   placeholder="@lang('invoice/index.product-price')">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="row">
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="count">@lang('invoice/index.orderNumber')</label>
                            <input type="text" class="form-control" name="order_number" id="orderNumber" value="{{$data->tracking}}"
                                   required
                                   placeholder="@lang('invoice/index.orderNumber')">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label for="value">@lang('invoice/index.orderDate')</label>
                            <input type="date" class="form-control" id="orderDate" name="order_date" value="{{$data->order_date}}"
                                   required placeholder="@lang('invoice/index.orderDate')">
                        </div>
                    </div>
                </div>
            </div>
    </form>



</div>

