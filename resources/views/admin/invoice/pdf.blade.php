<div style="border: 1px solid #000; padding: 10px;border-radius: 6px;">
    <div style="padding: 5px; margin-bottom: 5px; border: 1px solid #000; border-radius: 4px;">
        <div style="text-align: center; margin-top: 10px;">
            {!! $barcode !!}
            <p style="text-align: center; font-size: 12px; margin-bottom: 4px; line-height: 18px;">{{$purchaseNo}}</p>
        </div>
    </div>
    <div style="border: 1px solid #000;padding: 0 10px 10px; margin-bottom: 10px;border-radius: 4px;">
        <p style="font-weight: bold; margin-bottom: 10px;font-size: 11px;">{{$product->shop_name}} - {{$product->invoices[0]->order_tracking_number}}</p>
        <table>
            <thead>
            <tr>
                <th width="100px" style="text-align: left; font-size: 10px;">Category</th>
                <th></th>
                <th width="70px;" style="text-align: left; font-size: 10px; margin-right: 10px;">Weight</th>
                <th width="200px;" style="text-align: left; font-size: 10px;">Dimensions</th>
                <th width="100px;" style="text-align: left; font-size: 10px;">Price</th>
                <th width="100px;" style="text-align: left; font-size: 10px;">Shipping</th>
            </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="font-size: 10px;">{{$product->products_type_id ? $product->productTypes->name: $product->product_type_name}}</td>
                    <td style="font-size: 10px;"></td>
                    <td style="font-size: 10px;">{{$product->invoices[0]->weight}} KG</td>
                    <td style="font-size: 10px;">{{$product->invoices[0]->width}}sm x {{$product->invoices[0]->height}}sm x {{$product->invoices[0]->length}}sm</td>
{{--
                    <td style="font-size: 10px;">{{$product->price}} TL</td>
--}}
                    <td style="font-size: 10px;">{{$product->invoices[0]->price}} TL</td>
                    <td style="font-size: 10px;">{{$product->invoices[0]->shipping_price}} $</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div style="border: 1px solid #000;padding: 0 10px 10px; border-radius: 4px;">
        <p style="font-weight: bold; margin-bottom: 0; font-size: 16px; line-height: 12px;">{{$user->name}} {{$user->surname}} -
            <?php
                if($product->invoices[0]->corporate==1){
                    echo "1".str_pad($product->invoices[0]->client_id, 6, 0, STR_PAD_LEFT);
                }else{
                    echo $user->uniqid;
                }
            ?> </p>
        <dl style="margin-bottom: 0;">
            <div style="margin-bottom: 0;">
                <dt style="float: left; font-weight: bold; font-size: 10px;width: 8%;">Date:</dt>
                <dd style="float: left; font-size: 10px; text-align: left; margin-bottom: 0;">    {{date("d.m.Y H:i")}}</dd>
                <br clear="all">
            </div>
            <div style="margin-bottom: 0;">
                <dt style="float: left; font-size: 10px; font-weight: bold; width: 8%;">Address:</dt>
                <dd style="float: left; font-size: 10px; text-align: left; margin-bottom: 0;">{{$user->address}}</dd>
                <br clear="all">
            </div>
            <div style="margin-bottom: 0;">
                <dt style="float: left; font-size: 10px; font-weight: bold; width: 8%;">Country:</dt>
                <dd style="float: left; font-size: 10px; text-align: left; margin-bottom: 0;">Azerbaijan</dd>
                <br clear="all">
            </div>
        </dl>
    </div>
    <div>
        <div style="float: left;width:49%">
            <p style="font-weight: bold;text-align: left; margin-bottom: 0; font-size: 14px; line-height: 10px;padding-left:10px">www.limak.az | info@limak.az | *9595

            </p>
         </div>
        <div style="float: right;width: 49%">
            <p style="font-weight: bold;text-align: right !important; margin-bottom: 0; font-size: 16px; line-height: 10px;padding-right: 10px;"><?php
                if($product->invoices[0]->corporate==1){
                    echo $user->uniqid;
                }
                ?> -> TR->{{$regions[$product->invoices[0]->region_id]}}
            </p>
        </div>
        <div style="clear: both"></div>
    </div>

</div>
